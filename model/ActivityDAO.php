<?php
require_once('SqliteConnection.php');

// Activity Data Access Object
class ActivityDAO {
    private static ActivityDAO $dao;

    // Constructor
    private function __construct() {}

    // Get the instance of the ActivityDAO
    public static function getInstance(): ActivityDAO {
        if (!isset(self::$dao)) {
            self::$dao = new ActivityDAO();
        }
        return self::$dao;
    }

    // Find an activity by its ID
    public final function find(int $id): Activity {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Activity WHERE activityId = :activityId";
        $stmt = $dbc->prepare($query);
        $stmt->bindValue(':activityId', $id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Activity');
        return $results[0];
    }

    // Find all activities
    public final function findAll(): array {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Activity";
        $stmt = $dbc->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Activity');
        return $results;
    }

    // Find activities by user ID
    public final function findByIdUser(int $id): array {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Activity WHERE userId = :userId";
        $stmt = $dbc->prepare($query);
        $stmt->bindValue(':userId', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Activity');
        return $results;
    }

    // Find activities by user email
    public final function findByEmail(string $mail): array {
        $dbc = SqliteConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Activity WHERE userId = (SELECT userId FROM User WHERE email = :mail)";
        $stmt = $dbc->prepare($query);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, 'Activity');
        return $results;
    }

    // Insert a new activity
    public final function insert(Activity $activity): void {
        if ($activity instanceof Activity) {
            $dbc = SqliteConnection::getInstance()->getConnection();
            // Préparez la requête SQL
            $query = "INSERT INTO Activity(userId, date, description, time, distance, averageSpeed, maxSpeed, totalAltitude, averageHeartRate, maxHeartRate, minHeartRate";

            // Vérifiez si l'ID de l'activité est défini, si oui, incluez-le dans la requête
            if ($activity->getActivityId() !== null) {
                $query .= ", activityId";
            }

            $query .= ") VALUES (:userId, :date, :description, :time, :distance, :averageSpeed, :maxSpeed, :totalAltitude, :averageHeartRate, :maxHeartRate, :minHeartRate";

            // Vérifiez si l'ID de l'activité est défini, si oui, liez-le à la requête
            if ($activity->getActivityId() !== null) {
                $query .= ", :activityId";
            }

            $query .= ")";

            $stmt = $dbc->prepare($query);
            // Liez les paramètres
            $stmt->bindValue(':userId', $activity->getUserId(), PDO::PARAM_INT);
            $stmt->bindValue(':date', $activity->getDate(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $activity->getDescription(), PDO::PARAM_STR);
            $stmt->bindValue(':time', $activity->getTime(), PDO::PARAM_STR);
            $stmt->bindValue(':distance', $activity->getDistance(), PDO::PARAM_STR);
            $stmt->bindValue(':averageSpeed', $activity->getAverageSpeed(), PDO::PARAM_STR);
            $stmt->bindValue(':maxSpeed', $activity->getMaxSpeed(), PDO::PARAM_STR);
            $stmt->bindValue(':totalAltitude', $activity->getTotalAltitude(), PDO::PARAM_STR);
            $stmt->bindValue(':averageHeartRate', $activity->getAverageHeartRate(), PDO::PARAM_INT);
            $stmt->bindValue(':maxHeartRate', $activity->getMaxHeartRate(), PDO::PARAM_INT);
            $stmt->bindValue(':minHeartRate', $activity->getMinHeartRate(), PDO::PARAM_INT);

            // Vérifiez si l'ID de l'activité est défini, si oui, liez-le à la requête
            if ($activity->getActivityId() !== null) {
                $stmt->bindValue(':activityId', $activity->getActivityId(), PDO::PARAM_INT);
            }

            // ajouter l'id au Activity
            $activity->setActivityId($dbc->lastInsertId());

            // Exécutez la requête préparée
            $stmt->execute();
        }
    }

    // Delete an activity
    public function delete(Activity $activity): void {
        if ($activity instanceof Activity) {
            $dbc = SqliteConnection::getInstance()->getConnection();
            // Préparez la requête SQL
            $query = "DELETE FROM Activity WHERE activityId = :activityId";
            $stmt = $dbc->prepare($query);
            // Liez les paramètres
            $stmt->bindValue(':activityId', $activity->getActivityId(), PDO::PARAM_INT);
            // Exécutez la requête préparée
            $stmt->execute();
        }
    }

    // Delete all activities
    public function deleteAll(): void {
        $dbc = SqliteConnection::getInstance()->getConnection();
        // Préparez la requête SQL
        $query = "DELETE FROM Activity";
        $stmt = $dbc->prepare($query);
        // Exécutez la requête préparée
        $stmt->execute();
    }

    // Update an activity
    public function update(Activity $activity): void {
        if ($activity instanceof Activity) {
            $dbc = SqliteConnection::getInstance()->getConnection();

            // Vérifiez si l'ID de l'activité existe dans la base de données
            $checkQuery = "SELECT COUNT(*) FROM Activity WHERE activityId = :activityId";
            $checkStmt = $dbc->prepare($checkQuery);
            $checkStmt->bindValue(':activityId', $activity->getActivityId(), PDO::PARAM_INT);
            $checkStmt->execute();
            $rowCount = $checkStmt->fetchColumn();

            if ($rowCount > 0) {
                // L'ID de l'activité existe, on peut effectuer la mise à jour
                $query = "UPDATE Activity SET userId = :userId, date = :date, description = :description, time = :time, distance = :distance, averageSpeed = :averageSpeed, maxSpeed = :maxSpeed, totalAltitude = :totalAltitude, averageHeartRate = :averageHeartRate, maxHeartRate = :maxHeartRate, minHeartRate = :minHeartRate WHERE activityId = :activityId";
                $stmt = $dbc->prepare($query);

                // Liez les paramètres de mise à jour ici
                $stmt->bindValue(':activityId', $activity->getActivityId(), PDO::PARAM_INT);
                $stmt->bindValue(':userId', $activity->getUserId(), PDO::PARAM_INT);
                $stmt->bindValue(':date', $activity->getDate(), PDO::PARAM_STR);
                $stmt->bindValue(':description', $activity->getDescription(), PDO::PARAM_STR);
                $stmt->bindValue(':time', $activity->getTime(), PDO::PARAM_STR);
                $stmt->bindValue(':distance', $activity->getDistance(), PDO::PARAM_STR);
                $stmt->bindValue(':averageSpeed', $activity->getAverageSpeed(), PDO::PARAM_STR);
                $stmt->bindValue(':maxSpeed', $activity->getMaxSpeed(), PDO::PARAM_STR);
                $stmt->bindValue(':totalAltitude', $activity->getTotalAltitude(), PDO::PARAM_STR);
                $stmt->bindValue(':averageHeartRate', $activity->getAverageHeartRate(), PDO::PARAM_INT);
                $stmt->bindValue(':maxHeartRate', $activity->getMaxHeartRate(), PDO::PARAM_INT);
                $stmt->bindValue(':minHeartRate', $activity->getMinHeartRate(), PDO::PARAM_INT);;

                // Exécutez la requête préparée
                $stmt->execute();
            } else {
                // L'ID de l'activité n'existe pas dans la base de données
                // Vous pouvez gérer cette situation selon vos besoins, par exemple, en lançant une exception.
                throw new Exception("L'ID de l'activité n'existe pas dans la base de données.");
            }
        }
    }

    // Reset the auto increment
    public function resetAutoIncrement(): void {
        $dbc = SqliteConnection::getInstance()->getConnection();
        // Préparez la requête SQL
        $query = "DELETE FROM sqlite_sequence WHERE name = 'Activity'";
        $stmt = $dbc->prepare($query);
        // Exécutez la requête préparée
        $stmt->execute();
    }
}
?>
