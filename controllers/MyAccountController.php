<?php
require(__ROOT__.'/controllers/Controller.php');

class MyAccountController extends Controller {
    // cette fonction permet de récupérer les informations de l'utilisateur connecté
    public function get($request) {
        // Vérifiez si l'utilisateur est connecté
        session_start();
        if (isset($_SESSION['userId'])) {
            // L'utilisateur est connecté, récupérez les informations de l'utilisateur à partir de la base de données
            $userDAO = UserDAO::getInstance();
            $user = $userDAO->find($_SESSION['userId']);
            $this->render('my_account', ['user' => $user]);
        } else {
            // L'utilisateur n'est pas connecté, redirigez l'utilisateur vers la page de connexion
            $this->render('user_connect_form', []);
        }
    }
}