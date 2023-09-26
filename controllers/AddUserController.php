<?php
require(__ROOT__.'/controllers/Controller.php');

try {
    $dbc = SqliteConnection::getInstance()->getConnection();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

class AddUserController extends Controller {
    public function get($request) {
        $this->render('user_add_form', []);
    }

    public function post($request): void {
        // Accédez aux valeurs du formulaire à partir de $request
        $lastName = $request['nom'];
        $firstName = $request['prenom'];
        $birthDate = $request['date-de-naissance'];
        $gender = $request['sexe'];
        $height = $request['taille'];
        $weight = $request['poids'];
        $email = $request['email'];
        $password = $request['password'];
    
        // Vérifiez si un utilisateur avec le même email existe déjà
        $userDAO = UserDAO::getInstance();
        $existingUser = $userDAO->findByEmail($email);
        
        // Vérifiez si l'adresse e-mail est au format correct
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "L'adresse e-mail n'est pas au format correct";
            echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
        } elseif ($existingUser) {
            // Un utilisateur avec le même email existe déjà
            echo "Un utilisateur avec cet email existe déjà.";
            echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
        } elseif (strlen($password) < 6) {
            // Le mot de passe est trop court
            echo "Le mot de passe doit contenir au moins 6 caractères.";
            echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
        } else {
            // Aucun utilisateur avec le même email trouvé, mot de passe valide, et e-mail au bon format, insérez le nouvel utilisateur dans la base de données
            $user = new User();
            $user->init(null, $lastName, $firstName, $birthDate, $gender, $height, $weight, $email, $password);
            $userDAO->insert($user);
            echo "L'utilisateur a été ajouté avec succès.";
            echo "<br><br><a href='/'>Retour à la page d'accueil</a>";
        }
    }
}
?>
