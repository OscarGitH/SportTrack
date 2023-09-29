<?php
require(__ROOT__.'/controllers/Controller.php');

class MyAccountController extends Controller {
    // cette fonction permet de récupérer les informations de l'utilisateur connecté
    public function get($request) {
        // Vérifie si l'utilisateur est connecté
        session_start();
        if (isset($_SESSION['userId'])) {
            // L'utilisateur est connecté, récupérez les informations de l'utilisateur à partir de la base de données
            $userDAO = UserDAO::getInstance();
            $user = $userDAO->find($_SESSION['userId']);
            $_SESSION['password'] = $user->getPassword();
            $this->render('my_account', ['user' => $user]);
        } else {
            // L'utilisateur n'est pas connecté, redirige l'utilisateur vers la page de connexion
            $this->render('user_connect_form', []);
        }
    }

    public function post($request) {
        session_start();
    
        // Vérifiez si l'utilisateur est connecté
        if (isset($_SESSION['userId'])) {
            // Récupérez les informations du formulaire POST
            $lastName = $_POST['lastName'];
            $firstName = $_POST['firstName'];
            $birthDate = $_POST['birthDate'];
            $gender = $_POST['gender'];
            $height = $_POST['height'];
            $weight = $_POST['weight'];
            $email = $_POST['email'];
            $password = $_SESSION['password'];
    
            // Vérification du format de l'adresse e-mail
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // L'adresse e-mail n'est pas au bon format
                header("Location: /modif_invalid");
            }
    
            // Vérification si un utilisateur (autre que l'utilisateur actuel) existe déjà avec la même adresse e-mail
            $userDAO = UserDAO::getInstance();
            $user = $userDAO->findByEmail($email);
            if ($user != null && $user->getUserId() != $_SESSION['userId']) {
                // Un utilisateur avec la même adresse e-mail existe déjà
                header("Location: /modif_invalid");
            }
    
            // Créez un objet User avec les nouvelles informations
            $user = new User();
            $user->setUserId($_SESSION['userId']);
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setBirthDate($birthDate);
            $user->setGender($gender);
            $user->setHeight($height);
            $user->setWeight($weight);
            $user->setEmail($email);
            $user->setPassword($_SESSION['password']);
    
            // Met à jour l'utilisateur dans la base de données
            $userDAO->update($user);
    
            // Redirige l'utilisateur vers la page de compte mise à jour
            $this->render('modif_valid', []);
        } else {
            // L'utilisateur n'est pas connecté, redirige vers la page de connexion
            $this->render('user_connect_form', []);
        }
    }
    
    
}