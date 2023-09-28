<?php
require_once('SqliteConnection.php');

// Data Data Access Object
class DataDAO {
    private static DataDAO $dao;

    // Constructor
    private function __construct() {}

    // Get the instance of the DataDAO
    public static function getInstance(): DataDAO {
        if (!isset(self::$dao)) {
            self::$dao = new DataDAO();
        }
        return self::$dao;
    }

    // Find a data by its ID
    public final function find(int $id): Data {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM data WHERE dataId = :dataId";
        $stmt = $dbc->prepare($query);
        $stmt->bindValue(':dataId', $id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Data');
        return $results[0];
    }

    // Find all data
    public final function findAll(): array {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM data";
        $stmt = $dbc->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Data');
        return $results;
    }

    // Find data by activity ID
    public final function findByActivityId(int $activityId): array {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM data WHERE activityId = :activityId";
        $stmt = $dbc->prepare($query);
        $stmt->bindValue(':activityId', $activityId, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Data');
        return $results;
    }

    // Insert a new data
    public final function insert(Data $donnees): void {
        if ($donnees instanceof Data) {
            $dbc = SqliteConnection::getInstance()->getConnection();
            // Préparez la requête SQL
            $query = "INSERT INTO Data(activityId, date, description, time, heartRate, latitude, longitude, altitude";

            // Verifier si l'ID de la donnée est défini, si oui, incluez-le dans la requête
            if ($donnees->getDataId() !== null) {
                $query .= ", dataId";
            }

            $query .= ") VALUES (:activityId, :date, :description, :time, :heartRate, :latitude, :longitude, :altitude";

            // Verifier si l'ID de la donnée est défini, si oui, incluez-le dans la requête
            if ($donnees->getDataId() !== null) {
                $query .= ", :dataId";
            }

            $query .= ")";

            $stmt = $dbc->prepare($query);
            // Lier les paramètres
            $stmt->bindValue(':activityId', $donnees->getActivityId(), PDO::PARAM_INT);
            $stmt->bindValue(':date', $donnees->getDate(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $donnees->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(':time', $donnees->getTime(), PDO::PARAM_STR);
            $stmt->bindValue(':heartRate', $donnees->getHeartRate(), PDO::PARAM_INT);
            $stmt->bindValue(':latitude', $donnees->getLatitude(), PDO::PARAM_STR);
            $stmt->bindValue(':longitude', $donnees->getLongitude(), PDO::PARAM_STR);
            $stmt->bindValue(':altitude', $donnees->getAltitude(), PDO::PARAM_STR);
            
            // Verifier si l'ID de la donnée est défini, si oui, incluez-le dans la requête
            if ($donnees->getDataId() !== null) {
                $stmt->bindValue(':dataId', $donnees->getDataId(), PDO::PARAM_INT);
            }

            // Exécutez la requête préparée
            $stmt->execute();            
            $donnees->setDataId($dbc->lastInsertId());
        }
    }
    
    // Delete a data by its id
    public function delete(Data $data): void {
        if ($data instanceof Data) {
            $dbc = SqliteConnection::getInstance()->getConnection();
            // Préparez la requête SQL
            $query = "DELETE FROM Data WHERE dataId = :dataId";
            $stmt = $dbc->prepare($query);
            // Liez les paramètres
            $stmt->bindValue(':dataId', $data->getDataId(), PDO::PARAM_INT);
            // Exécutez la requête préparée
            $stmt->execute();
        }
    }

    // Delete all data
    public function deleteAll(): void {
        $dbc = SqliteConnection::getInstance()->getConnection();
        // Préparez la requête SQL
        $query = "DELETE FROM Data";
        $stmt = $dbc->prepare($query);
        // Exécutez la requête préparée
        $stmt->execute();
    }

    // Update a data
    public function update(Data $data): void {
        if ($data instanceof Data) {
            $dbc = SqliteConnection::getInstance()->getConnection();

            // Vérifie si l'ID de la donnée existe dans la base de données
            $checkQuery = "SELECT COUNT(*) FROM Data WHERE dataId = :dataId";
            $checkStmt = $dbc->prepare($checkQuery);
            $checkStmt->bindValue(':dataId', $data->getDataId(), PDO::PARAM_INT);
            $checkStmt->execute();
            $rowCount = $checkStmt->fetchColumn();

            if ($rowCount > 0) {
                // L'ID de la donnée existe, on peut effectuer la mise à jour
                $query = "UPDATE Data SET activityId = :activityId, date = :date, description = :description, time = :time, heartRate = :heartRate, latitude = :latitude, longitude = :longitude, altitude = :altitude WHERE dataId = :dataId";
                $stmt = $dbc->prepare($query);

                // Lie les paramètres de mise à jour ici
                $stmt->bindValue(':dataId', $data->getDataId(), PDO::PARAM_INT);
                $stmt->bindValue(':activityId', $data->getActivityId(), PDO::PARAM_INT);
                $stmt->bindValue(':date', $data->getDate(), PDO::PARAM_STR);
                $stmt->bindValue(':description', $data->getDescription(), PDO::PARAM_STR);
                $stmt->bindValue(':time', $data->getTime(), PDO::PARAM_STR);
                $stmt->bindValue(':heartRate', $data->getHeartRate(), PDO::PARAM_INT);
                $stmt->bindValue(':latitude', $data->getLatitude(), PDO::PARAM_STR);
                $stmt->bindValue(':longitude', $data->getLongitude(), PDO::PARAM_STR);
                $stmt->bindValue(':altitude', $data->getAltitude(), PDO::PARAM_STR);

                // Exécute la requête préparée
                $stmt->execute();

            } else {
                // L'ID de la donnée n'existe pas dans la base de données
                throw new Exception("L'ID de la donnée n'existe pas dans la base de données.");
            }
        }
    }

    // Reset the auto increment
    public function resetAutoIncrement(): void {
        $dbc = SqliteConnection::getInstance()->getConnection();
        // Préparez la requête SQL
        $query = "DELETE FROM sqlite_sequence WHERE name = 'Data'";
        $stmt = $dbc->prepare($query);
        // Exécutez la requête préparée
        $stmt->execute();
    }
}
?>
