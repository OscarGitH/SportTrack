<?php
require(__ROOT__.'/controllers/Controller.php');

try {
    $dbc = SqliteConnection::getInstance()->getConnection();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

class MyAccountController extends Controller {
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

    public function post($request): void {
        // Vérifiez si l'utilisateur est connecté
        session_start();
        if (isset($_SESSION['userId'])) {
            // L'utilisateur est connecté, récupérez les informations de l'utilisateur à partir de la base de données
            $userDAO = UserDAO::getInstance();
            $user = $userDAO->find($_SESSION['userId']);

            // Accédez aux valeurs du formulaire à partir de $request
            $lastName = $request['nom'];
            $firstName = $request['prenom'];
            $birthDate = $request['date-de-naissance'];
            $gender = $request['sexe'];
            $height = $request['taille'];
            $weight = $request['poids'];
            $email = $request['email'];
            $password = $request['password'];

            // Vérifiez si l'adresse e-mail est au format correct
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "L'adresse e-mail n'est pas au format correct";
                echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
            } elseif (strlen($password) < 6) {
                // Le mot de passe est trop court
                echo "Le mot de passe doit contenir au moins 6 caractères.";
                echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
            } else {
                // Aucun utilisateur avec le même email trouvé, mot de passe valide, et e-mail au bon format, mettez à jour les informations de l'utilisateur dans la base de données
                $user->init(null, $lastName, $firstName, $birthDate, $gender, $height, $weight, $email, $password);
                $userDAO->update($user);
                echo "Les informations de l'utilisateur ont été mises à jour avec succès.";
                echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
            }
        } else {
            // L'utilisateur n'est pas connecté, redirigez l'utilisateur vers la page de connexion
            $this->render('user_connect_form', []);
        }
    }
}