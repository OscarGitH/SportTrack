<?php
require_once('SqliteConnection.php');

// User Data Access Object
class UserDAO {
    private static UserDAO $dao;

    // Constructor
    private function __construct() {}

    // Get the instance of the UserDAO
    public static function getInstance(): UserDAO {
        if(!isset(self::$dao)) {
            self::$dao= new UserDAO();
        }
        return self::$dao;
    }

    // Find a user by their ID
    public final function find(int $userId): User {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM User WHERE userId = :userId";
        $stmt = $dbc->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = new User();
        $user->init($row['userId'], $row['lastName'], $row['firstName'], $row['birthDate'], $row['gender'], $row['height'], $row['weight'], $row['email'], $row['password']);
        return $user;
    }

    public final function findByEmail(string $email): ?User {
        $dbc = SqliteConnection::getInstance()->getConnection();        
        $query = "SELECT * FROM User WHERE email = :email";
        $stmt = $dbc->prepare($query); 
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            // Un utilisateur avec cet email a été trouvé, retournez l'objet User
            $user = new User();
            $user->init($row['userId'], $row['lastName'], $row['firstName'], $row['birthDate'], $row['gender'], $row['height'], $row['weight'], $row['email'], $row['password']);
            return $user;
        } else {
            // Aucun utilisateur avec cet email n'a été trouvé, retournez null
            return null;
        }
    }
    

    // Find all users
    public final function findAll(): array {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM User ORDER BY lastName, firstName";
        $stmt = $dbc->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
        return $results;
    }

    // Insert a new user
    public final function insert(User $user): void {
        if ($user instanceof User) {
            $dbc = SqliteConnection::getInstance()->getConnection();
            // Prepare the SQL statement
            $query = "INSERT INTO User (lastName, firstName, birthDate, gender, height, weight, email, password";
            
            // Check if userId is set, if yes, include it in the query
            if ($user->getUserId() !== null) {
                $query .= ", userId";
            }
            
            $query .= ") VALUES (:lastName, :firstName, :birthDate, :gender, :height, :weight, :email, :password";
            
            // Check if userId is set, if yes, include it in the parameter binding
            if ($user->getUserId() !== null) {
                $query .= ", :userId";
            }
            
            $query .= ")";
            
            $stmt = $dbc->prepare($query);
            // Bind the parameters
            $stmt->bindValue(':lastName', $user->getLastName(), PDO::PARAM_STR);
            $stmt->bindValue(':firstName', $user->getFirstName(), PDO::PARAM_STR);
            $stmt->bindValue(':birthDate', $user->getBirthDate(), PDO::PARAM_STR);
            $stmt->bindValue(':gender', $user->getGender(), PDO::PARAM_STR);
            $stmt->bindValue(':height', $user->getHeight(), PDO::PARAM_STR);
            $stmt->bindValue(':weight', $user->getWeight(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
    
            // Check if userId is set, if yes, bind it to the parameter
            if ($user->getUserId() !== null) {
                $stmt->bindValue(':userId', $user->getUserId(), PDO::PARAM_INT);
            }
    
            // Execute the prepared statement
            $stmt->execute();
            $user->setUserId($dbc->lastInsertId());
        }
    }    
    
    // Delete a user
    public function delete(User $user): void {
        if ($user instanceof User){
            $dbc = SqliteConnection::getInstance()->getConnection();
            $query = "DELETE FROM User WHERE userId = :userId";
            $stmt = $dbc->prepare($query);
            $stmt->bindValue(':userId', $user->getUserId(), PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
    // Update a user
    public function update(User $user): void {
        if ($user instanceof User) {
            $dbc = SqliteConnection::getInstance()->getConnection();
            
            if ($user->getUserId() === null) {
                throw new Exception("Cannot update a user with a null ID.");
            }
            
            $checkQuery = "SELECT COUNT(*) FROM User WHERE userId = :userId";
            $checkStmt = $dbc->prepare($checkQuery);
            $checkStmt->bindValue(':userId', $user->getUserId(), PDO::PARAM_INT);
            $checkStmt->execute();
            $rowCount = $checkStmt->fetchColumn();
    
            if ($rowCount > 0) {
                $query = "UPDATE User SET lastName = :lastName, firstName = :firstName, birthDate = :birthDate, gender = :gender, height = :height, weight = :weight, email = :email, password = :password WHERE userId = :userId";
                $stmt = $dbc->prepare($query);
                $stmt->bindValue(':userId', $user->getUserId(), PDO::PARAM_INT);
                $stmt->bindValue(':lastName', $user->getLastName(), PDO::PARAM_STR);
                $stmt->bindValue(':firstName', $user->getFirstName(), PDO::PARAM_STR);
                $stmt->bindValue(':birthDate', $user->getBirthDate(), PDO::PARAM_STR);
                $stmt->bindValue(':gender', $user->getGender(), PDO::PARAM_STR);
                $stmt->bindValue(':height', $user->getHeight(), PDO::PARAM_STR);
                $stmt->bindValue(':weight', $user->getWeight(), PDO::PARAM_STR);
                $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
                $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
                $stmt->execute();
            } else {
                throw new Exception("User ID does not exist in the database.");
            }
        }
    }

    // Delete all users
    public function deleteAll(): void {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "DELETE FROM User";
        $stmt = $dbc->prepare($query);
        $stmt->execute();
    }  

    // Reset the auto increment
    public function resetAutoIncrement(): void {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "DELETE FROM SQLITE_SEQUENCE WHERE name = 'User'";
        $stmt = $dbc->prepare($query);
        $stmt->execute();
    }
    
    // Connect a user by their email and password
    public function connectUserByEmail($email, $password) {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM User WHERE email = :email AND password = :password";
        $stmt = $dbc->prepare($query); 
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user = new User();
            $user->init(
                $result['userId'],
                $result['lastName'],
                $result['firstName'],
                $result['birthDate'],
                $result['gender'],
                $result['height'],
                $result['weight'],
                $result['email'],
                $result['password']
            );
            return $user;
        } else {
            return null; // User not found
        }
    }          
}
?>

