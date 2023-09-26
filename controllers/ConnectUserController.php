<?php
require(__ROOT__.'/controllers/Controller.php');

try {
    $dbc = SqliteConnection::getInstance()->getConnection();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

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
                // La connexion est réussie, sauvegardez userId dans une variable de session
                session_start();
                $_SESSION['userId'] = $user->getUserId();

                // Redirigez l'utilisateur vers la page d'accueil
                $this->render('user_connect_valid', []);
            } else {
                // Identifiants de connexion invalides, affichez un message d'erreur à l'utilisateur
                $errorMessage = "Identifiants de connexion invalides. Veuillez réessayer.";
                include('views/user_connect_form.php'); // Vous pouvez passer $errorMessage à la vue pour l'affichage
            }
        }
    }
}
?>
