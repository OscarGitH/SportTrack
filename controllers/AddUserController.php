<?php
require(__ROOT__.'/controllers/Controller.php');

class AddUserController extends Controller {
    // cette fonction permet d'afficher le formulaire d'inscription
    public function get($request) {
        $this->render('user_add_form', []);
    }

    // cette fonction permet de traiter les données du formulaire d'inscription
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
            // redirigez l'utilisateur vers une page d'erreur de connexion
            header("Location: /user_add_invalid");
        } elseif ($existingUser) {
            // Un utilisateur avec le même email existe déjà redirigez l'utilisateur vers une page d'erreur de connexion
            header("Location: /user_add_invalid");
        } elseif (strlen($password) < 6) {
            // Le mot de passe est trop court redirigez l'utilisateur vers une page d'erreur de connexion
            header("Location: /user_add_invalid");
        } else {
            // Aucun utilisateur avec le même email trouvé, mot de passe valide, et e-mail au bon format, insérez le nouvel utilisateur dans la base de données
            $user = new User();
            $user->init(null, $lastName, $firstName, $birthDate, $gender, $height, $weight, $email, $password);
            $userDAO->insert($user);
            // Sauvegardez userId, le mail, le nom et le prénom de l'utilisateur dans la session
            session_start();
            $_SESSION['userId'] = $user->getUserId();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['firstName'] = $user->getFirstName();
            $_SESSION['lastName'] = $user->getLastName();

            // Redirigez l'utilisateur
            $this->render('user_add_valid', []);
        }
    }
}
?>
