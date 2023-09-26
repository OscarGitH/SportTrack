<?php
require(__ROOT__.'/controllers/Controller.php');

class ConnectUserController extends Controller {
    public function get($request) {
        $this->render('user_connect_form', []);
    }

    public function post($request): void {
        if (isset($request['email']) && isset($request['password'])) {
            $email = $request['email'];
            $password = $request['password'];

            // Créez une instance de UserDAO
            $userDAO = UserDAO::getInstance();

            // Utilisez la méthode connectUserByEmail pour vérifier les informations de connexion
            $user = $userDAO->connectUserByEmail($email, $password);

            if ($user !== null) {
                // Sauvegardez userId, le mail, le nom et le prénom de l'utilisateur dans la session
                session_start();
                $_SESSION['userId'] = $user->getUserId();
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['firstName'] = $user->getFirstName();
                $_SESSION['lastName'] = $user->getLastName();

                // Redirigez l'utilisateur
                $this->render('user_connect_valid', []);
            } else {
                // Identifiants de connexion invalides, affichez un message d'erreur à l'utilisateur
                $errorMessage = "Identifiants de connexion invalides.";
                $this->render('user_connect_form', ['errorMessage' => $errorMessage]);
            }
        }
    }
}
?>
