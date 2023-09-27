<?php

require_once('model/SqliteConnection.php');
require_once('model/User.php');
require_once('model/UserDAO.php');

require_once('model/Activity.php');
require_once('model/ActivityDAO.php');

require_once('model/Data.php');
require_once('model/DataDAO.php');

require_once('CalculDistance/CalculDistance.php');
require_once('CalculDistance/CalculDistanceImpl.php');

try {
    $dbc = SqliteConnection::getInstance()->getConnection();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
/**
 * Classe abstraite qui est étendue par les contrôleurs pour traiter
 * les requêtes HTTP POST, GET, ...
 */

abstract class Controller{

    /**
     * Méthode appelée pour traiter une requête HTTP de type GET.
     */
    public function get($request){
        $this->render('error',['message' => 'Method HTTP GET not allowed!']);
    }

    /**
     * Méthode appelée pour traiter une requête HTTP de type POST.
     */
    public function post($request){
        $this->render('error',['message' => 'Method HTTP POST not allowed!']);
    }

    /**
     * Méthode appelée pour traiter une requête HTTP de type PUT.
     */
    public function put($request){
        $this->render('error',['message' => 'Method HTTP PUT not allowed!']);
    }

    /**
     * Méthode appelée pour traiter une requête HTTP de type PUT.
     */
    public function delete($request){
        $this->render('error',['message' => 'Method HTTP DELETE not allowed!']);
    }

    /**
     * Méthode appelée pour afficher une vue
     * @param String $view Le nom du fichier contenant la vue qui doit
     * être retournée au client.
     * @param Array $data Un tableau associatif contenant les données
     * qui doivent être passées à la vue.
     */
    public function render($view, $data, $print=true){
        $filePath ="views/".$view.".php";
        
        $output = NULL;
        if(file_exists($filePath)){
            // Extract the variables to a local namespace
            extract($data);

            // Start output buffering
            ob_start();

            // Include the template file
            include $filePath;

            // End buffering and return its contents
            $output = ob_get_clean();
        }
        if ($print) {
            print $output;
        }
        return $output;
    }
}
?>
