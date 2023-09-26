<?php
require(__ROOT__.'/controllers/Controller.php');

class DisconnectUserController extends Controller {
    public function get($request) {
        // Démarrer la session
        session_start();

        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['userId'])) {
            // Supprimer la variable d'environnement d'identification
            unset($_SESSION['userId']);
            unset($_SESSION['firstName']);
            unset($_SESSION['lastName']);

            // Détruire la session utilisateur
            session_destroy();

            // Rediriger l'utilisateur vers une page de déconnexion réussie ou une autre page de votre choix
            header("Location: /");
            exit();
        } else {
            // L'utilisateur n'est pas connecté, vous pouvez rediriger vers une page d'erreur ou une autre page appropriée
            header("Location: /not_logged_in");
            exit();
        }
    }
}
?>
