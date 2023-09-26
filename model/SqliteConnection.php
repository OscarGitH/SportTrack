<?php
class SqliteConnection {
    private static $instance = null;
    
    public static function getConnection() {
        $db = new PDO('sqlite:sporttrack_db.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new SqliteConnection();
        }
        return self::$instance;
    }
}
?>
