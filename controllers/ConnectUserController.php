<?php
require(__ROOT__.'/controllers/Controller.php');

class ConnectUserController extends Controller {
    // cette fonction permet d'afficher le formulaire de connexion
    public function get($request) {
        $this->render('user_connect_form', []);
    }

    // cette fonction permet de traiter les données du formulaire de connexion
    public function post($request): void {
        if (isset($request['email']) && isset($request['password'])) {
            $email = $request['email'];
            $password = $request['password'];

            // Crée une instance de UserDAO
            $userDAO = UserDAO::getInstance();

            // Utilisez la méthode connectUserByEmail pour vérifier les informations de connexion
            $user = $userDAO->connectUserByEmail($email, $password);

            if ($user !== null) {
                // Sauvegarde userId, le mail, le nom et le prénom de l'utilisateur dans la session
                session_start();
                $_SESSION['userId'] = $user->getUserId();
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['firstName'] = $user->getFirstName();
                $_SESSION['lastName'] = $user->getLastName();

                // Redirige l'utilisateur
                $this->render('user_connect_valid', []);
            } else {
                // Identifiants de connexion invalides
                header("Location: /connect_invalid");
            }
        }
    }
}
?>
