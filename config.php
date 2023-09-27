<?php
const VIEWS_DIR = __ROOT__.'/views';
const CONTROLLERS_DIR = __ROOT__.'/controllers';

require_once('model/SqliteConnection.php');

try {
    $dbc = SqliteConnection::getInstance()->getConnection();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
