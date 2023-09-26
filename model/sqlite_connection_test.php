<?php
// SQLite test script
// Author: Noé Pierre & Oscar Pavoine

require_once('SqliteConnection.php');
require_once('User.php');
require_once('UserDAO.php');

require_once('Activity.php');
require_once('ActivityDAO.php');

require_once('Data.php');
require_once('DataDAO.php');

try {
    $dbc = SqliteConnection::getInstance()->getConnection();
    echo "Connexion réussie.<br><br>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

//vide la table utilisateur
UserDAO::getInstance()->deleteAll();
echo "Table utilisateur vidée avec succès.<br>";

//vide la table activite
ActivityDAO::getInstance()->deleteAll();
echo "Table activite vidée avec succès.<br>";

//vide la table données
DataDAO::getInstance()->deleteAll();
echo "Table données vidée avec succès.<br>";


echo "----------------------------------------------TEST USER----------------------------------------------<br><br>";
testUser();

echo "----------------------------------------------TEST ACTIVITE----------------------------------------------<br><br>";
testActivity();

echo "----------------------------------------------TEST DONNEES----------------------------------------------<br><br>";
testData();

//-------------------TEST USER----------------------------------------------

function testUser() {
    //remettre à 0 l'autoincrement de la table utilisateur
    UserDAO::getInstance()->resetAutoIncrement();
    echo "<br>Autoincrement de la table utilisateur remis à 0 avec succès.<br><br>";

    // insertion de l'utilisateur noe
    $noe = new User();
    $noe->init(null, "Noé", "Pierre", "04/11/2004", "M", 1.80, 80, "noe@gmail.com", "motdepasse");

    // insertion de l'utilisateur emma
    $emma = new User();
    $emma->init(4, "Emma", "Martin", "04/11/2004", "F", 1.70, 70, "test@gmail.com", "motdepasse");

    // insertion de l'utilisateur oscar sans id (l'id sera généré automatiquement)
    $oscar = new User();
    $oscar->init(2,"Oscar", "Pavoine", "01/05/2004", "M", 1.75, 60, "oscar@gmail.com", "motdepasse");

    $lea = new User();
    $lea->init(3, "Léa", "Duval", "04/01/2000", "F", 1.60, 60, "lea@gmail.com", "motdepasse");

    //insertion des utilisateurs noe oscar et lea
    UserDAO::getInstance()->insert($noe);
    echo "Utilisateur noe inséré avec succès.<br>";

    UserDAo::getInstance()->insert($oscar);
    echo "Utilisateur oscar inséré avec succès.<br>";

    UserDAO::getInstance()->insert($lea);
    echo "Utilisateur lea inséré avec succès.<br>";

    UserDAO::getInstance()->insert($emma);
    echo "Utilisateur emma inséré avec succès.<br>";

    //affichage de l'utilisateur lea
    $id3 = UserDAO::getInstance()->find(3);
    echo "<br>Utilisateur avec l'ID 3 :<br>";
    echo $id3 . "<br><br>";

    //suppression de l'utilisateur lea
    UserDAO::getInstance()->delete($lea);
    echo "Utilisateur lea supprimé avec succès.<br>";

    //modification de l'utilisateur oscar (changement de taille de 1.75 à 1.90)
    // Créez un objet Utilisateur avec les nouvelles informations pour Oscar
    $oscarModifie = new User();
    $oscarModifie->init(2, "Oscar", "Pavoine", "01/11/2004", "M", 1.90, 80, "oscar@gmail.com", "motdepasse");
    // Utilisez la méthode update pour mettre à jour Oscar
    UserDAO::getInstance()->update($oscarModifie);
    echo "Utilisateur oscar mis à jour avec succès.<br>";

    //modification de l'utilisateur noe (changement de poids de 80 à 70 et du mail de noe@gmail à oscar@gmail.com) ne devrait pas fonctionner car le mail doit être unique
    //Créez un objet Utilisateur avec les nouvelles informations pour Noé
    $noeModifie = new User();
    $noeModifie->init(1, "Noé", "Pierre", "04/11/2004", "M", 1.95, 70, "oscar@gmail", "motdepasse");

    //Utilisez la méthode update pour mettre à jour Noé (ne devrait pas fonctionner car le mail doit être unique)
    echo "<br>Tentative de mise à jour de l'utilisateur noe avec un mail déjà existant (erreur attendue).<br>";
    try {
        UserDAO::getInstance()->update($noeModifie);
        echo "Utilisateur Noé mis à jour avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage() . "<br><br>";
    }

    //modification de l'utilisateur noe (changement de poids de 80 à 70 et du mail de noe@gmail à noee@gmail.com)
    // Créez un objet Utilisateur avec les nouvelles informations pour Noé
    $noeModifie2 = new User();
    $noeModifie2->init(1, "Noé", "Pierre", "04/11/2004", "M", 1.80, 70, "noee@gmail.com", "motdepasse");
    //Utilisez la méthode update pour mettre à jour Noé
    UserDAO::getInstance()->update($noeModifie2);
    echo "Utilisateur noe mis à jour avec succès.<br>";

    //tentative de modification de l'utilisateur avec l'id 4 (qui n'existe pas)
    //Créez un objet Utilisateur avec les nouvelles informations pour Noé
    echo "<br>Tentative de mise à jour de l'utilisateur avec l'id 4 (qui n'existe pas) (erreur attendue).<br>";
    $sarahmodif = new User();
    $sarahmodif->init(3, "Sarah", "Test", "04/11/2004", "F", 1.80, 70, "sarah@gmail.com", "motdepasse");
    try {
        UserDAO::getInstance()->update($sarahmodif);
        echo "Modification de l'utilisateur avec l'id 4 avec succès.<br>";
    }catch (Exception $e) { // Utilisez Exception ici
        echo "Erreur : " . $e->getMessage() . "<br><br>";
    }
    // OK, cela provoque une erreur car l'ID 4 n'existe pas dans la base de données

    //affichage de l'utilisateur avec l'email 'noee@gmail.com'
    $utilisateur = UserDAO::getInstance()->find(1);
    echo "Utilisateur avec l'email 'noee@gmail.com' :<br>";
    echo $utilisateur . "<br><br>";


    //ajout de l'utilisateur sarah avec un mail invalide (erreur attendue)
    $sarah = new User();
    $sarah->init(4, "Sarah", "Test", "04/11/2004", "F", 1.80, 70, "sarah.gmail" , "motdepasse");
    echo "<br>Tentative d'insertion de l'utilisateur sarah avec un mail invalide (erreur attendue).<br>";
    try {
        UserDAO::getInstance()->insert($sarah);
        echo "Utilisateur sarah inséré avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage() . "<br><br>";
    }

    //ajout de l'utilisateur sarah
    $sarah = new User();
    $sarah->init(null, "Sarah", "Test", "04/11/2004", "F", 1.80, 70, "sarah@gmail.com", "motdepasse");
    UserDAO::getInstance()->insert($sarah);
    echo "Utilisateur sarah inséré avec succès.<br>";

    //affichage des utilisateurs
    $users = UserDAO::getInstance()->findAll();
    if (count($users) > 0) {
        echo "<br>Utilisateurs dans la base de données :<br>";
        foreach ($users as $user) {
            echo "ID: " . $user->getUserId() . "<br>";
            echo "Nom: " . $user->getLastName() . "<br>";
            echo "Prénom: " . $user->getFirstName() . "<br>";
            echo "Date de Naissance: " . $user->getBirthDate() . "<br>";
            echo "Genre: " . $user->getGender() . "<br>";
            echo "Taille: " . $user->getHeight() . "<br>";
            echo "Poids: " . $user->getWeight() . "<br>";
            echo "Email: " . $user->getEmail() . "<br>";
            echo "Mot de passe: " . $user->getPassword() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucun utilisateur trouvé dans la base de données.<br>";
    }
}

//-------------------TEST ACTIVITY----------------------------------------------

function testActivity() {
    // remettre à 0 l'autoincrement de la table activite
    ActivityDAO::getInstance()->resetAutoIncrement();
    echo "<br>Autoincrement de la table activite remis à 0 avec succès.<br><br>";

    // Créez une instance de la classe Activite avec les données de l'activité
    $activite = new Activity();
    $activite->init(
        null, // L'ID de l'activité sera généré automatiquement
        1, // ID de l'utilisateur associé
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:07", // Temps
        10.2, // Distance (en kilomètres)
        9.5, // Vitesse moyenne (en km/h)
        15.8, // Vitesse maximale (en km/h)
        150, // Altitude totale (en mètres)
        120, // Moyenne fréquence cardiaque
        180, // Fréquence cardiaque maximale
        90 // Fréquence cardiaque minimale
    );

    // Insérez l'activité dans la base de données
    ActivityDAO::getInstance()->insert($activite);
    echo "Activité insérée avec succès.<br>";

    // Affichez l'activité avec l'ID 1
    $activite = ActivityDAO::getInstance()->find(1);
    echo "<br>Activité avec l'ID 1 :<br>";
    echo $activite . "<br><br>";

    // Récupérez les activités de l'utilisateur avec l'email 'noee@gmail.com'
    $activities = ActivityDAO::getInstance()->findByEmail("noee@gmail.com");
    if (count($activities) > 0) {
        echo "<br>Activités de l'utilisateur avec l'email 'noee@gmail.com' :<br>";
        foreach ($activities as $activity) {
            echo "ID: " . $activity->getActivityId() . "<br>";
            echo "ID de l'utilisateur: " . $activity->getUserID() . "<br>";
            echo "Date: " . $activity->getDate() . "<br>";
            echo "Description: " . $activity->getDescription() . "<br>";
            echo "Temps: " . $activity->getTime() . "<br>";
            echo "Distance: " . $activity->getDistance() . "<br>";
            echo "Vitesse Moyenne: " . $activity->getAverageSpeed() . "<br>";
            echo "Vitesse Maximale: " . $activity->getMaxSpeed() . "<br>";
            echo "Altitude Totale: " . $activity->getTotalAltitude() . "<br>";
            echo "Moyenne Fréquence Cardiaque: " . $activity->getAverageHeartRate() . "<br>";
            echo "Fréquence Cardiaque Maximale: " . $activity->getMaxHeartRate() . "<br>";
            echo "Fréquence Cardiaque Minimale: " . $activity->getMinHeartRate() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune activité trouvée pour l'utilisateur avec l'email 'noee@gmail.com'.<br>";
    }

    // Supprimez une activité (par exemple, avec ID 1)
    $activiteASupprimer = ActivityDAO::getInstance()->find(1);
    if ($activiteASupprimer) {
        ActivityDAO::getInstance()->delete($activiteASupprimer);
        echo "Activité supprimée avec succès.<br>";
    } else {
        echo "Activité non trouvée, aucune suppression effectuée.<br>";
    }


    // Créez une nouvelle instance de la classe Activite avec id 1
    $activite = new Activity();
    $activite->init(
        2, // L'ID de l'activité 
        1, // ID de l'utilisateur associé
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:57", // Temps
        10.2, // Distance (en kilomètres)
        9.5, // Vitesse moyenne (en km/h)
        15.8, // Vitesse maximale (en km/h)
        150, // Altitude totale (en mètres)
        120, // Moyenne fréquence cardiaque
        180, // Fréquence cardiaque maximale
        90 // Fréquence cardiaque minimale
    );

    // Insérez l'activité dans la base de données
    ActivityDAO::getInstance()->insert($activite);
    echo "Activité 1 insérée avec succès.<br>";


    // Créer une nouvelle instance de la classe Activite avec id 2
    $activite = new Activity();
    $activite->init(
        null, // L'ID de l'activité sera généré automatiquement
        1, // ID de l'utilisateur associé
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:57", // Temps
        10.2, // Distance (en kilomètres)
        9.5, // Vitesse moyenne (en km/h)
        15.8, // Vitesse maximale (en km/h)
        150, // Altitude totale (en mètres)
        120, // Moyenne fréquence cardiaque
        180, // Fréquence cardiaque maximale
        90 // Fréquence cardiaque minimale
    );

    // Insérez l'activité dans la base de données
    ActivityDAO::getInstance()->insert($activite);
    echo "Activité insérée avec succès.<br>";

    // Affichez l'activité avec l'ID 2
    $activite = ActivityDAO::getInstance()->find(2);
    echo "<br>Activité avec l'ID 2 :<br>";
    echo $activite . "<br><br>";

    // tentative de modification de l'activite avec l'id 2
    // Créez une instance mise à jour de l'activité
    $activiteMiseAJour = new Activity();
    $activiteMiseAJour->init(
        2, // L'ID de l'activité sera généré automatiquement
        1, // ID de l'utilisateur associé
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "01:55:57", // Temps
        20.2, // Distance (en kilomètres)
        10.5, // Vitesse moyenne (en km/h)
        15.8, // Vitesse maximale (en km/h)
        150, // Altitude totale (en mètres)
        120, // Moyenne fréquence cardiaque
        180, // Fréquence cardiaque maximale
        90 // Fréquence cardiaque minimale
    );

    // Utilisez la méthode update pour mettre à jour l'activité
    echo "<br>Tentative de mise à jour de l'activité avec l'id 2.<br>";
    try {
        ActivityDAO::getInstance()->update($activiteMiseAJour);
        echo "Activité mise à jour avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage() . "<br><br>";
    }

    //affichage des activités de l'utilisateur avec le mail 'oscar@gmail.com' (qui n'a pas d'activité)

    $activites = ActivityDAO::getInstance()->findByEmail("oscar@gmail.com");
    if (count($activites) > 0) {
        echo "<br>Activités de l'utilisateur avec le mail 'noee@gmail' :<br>";
        foreach ($activites as $activite) {
            echo "ID: " . $activity->getactivityId() . "<br>";
            echo "ID de l'utilisateur: " . $activity->getUserID() . "<br>";
            echo "Date: " . $activity->getDate() . "<br>";
            echo "Description: " . $activity->getDescription() . "<br>";
            echo "Temps: " . $activity->getTime() . "<br>";
            echo "Distance: " . $activity->getDistance() . "<br>";
            echo "Vitesse Moyenne: " . $activity->getAverageSpeed() . "<br>";
            echo "Vitesse Maximale: " . $activity->getMaxSpeed() . "<br>";
            echo "Altitude Totale: " . $activity->getTotalAltitude() . "<br>";
            echo "Moyenne Fréquence Cardiaque: " . $activity->getAverageHeartRate() . "<br>";
            echo "Fréquence Cardiaque Maximale: " . $activity->getMaxHeartRate() . "<br>";
            echo "Fréquence Cardiaque Minimale: " . $activity->getMinHeartRate() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune activité trouvée pour l'utilisateur avec le mail 'noee@gmail'.<br>";
    }

    //affichage des activités

    $activites = ActivityDAO::getInstance()->findAll();
    if (count($activites) > 0) {
        echo "<br>Activités dans la base de données :<br>";
        foreach ($activites as $activite) {
            echo "ID: " . $activity->getactivityId() . "<br>";
            echo "ID de l'utilisateur: " . $activity->getUserID() . "<br>";
            echo "Date: " . $activity->getDate() . "<br>";
            echo "Description: " . $activity->getDescription() . "<br>";
            echo "Temps: " . $activity->getTime() . "<br>";
            echo "Distance: " . $activity->getDistance() . "<br>";
            echo "Vitesse Moyenne: " . $activity->getAverageSpeed() . "<br>";
            echo "Vitesse Maximale: " . $activity->getMaxSpeed() . "<br>";
            echo "Altitude Totale: " . $activity->getTotalAltitude() . "<br>";
            echo "Moyenne Fréquence Cardiaque: " . $activity->getAverageHeartRate() . "<br>";
            echo "Fréquence Cardiaque Maximale: " . $activity->getMaxHeartRate() . "<br>";
            echo "Fréquence Cardiaque Minimale: " . $activity->getMinHeartRate() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune activité trouvée dans la base de données.<br>";
    }

//-------------------TEST DATA----------------------------------------------

function testData() {
    //remettre à 0 l'autoincrement de la table utilisateur
    DataDAO::getInstance()->resetAutoIncrement();
    echo "<br>Autoincrement de la table utilisateur remis à 0 avec succès.<br><br>";

    // Créez une instance de la classe Données
    $donnee = new Data();
    $donnee->init(
        null, // L'ID de la donnée sera généré automatiquement
        1, // ID de l'activité associée
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:07", // Temps
        150, // Fréquence cardiaque
        10.2, // Latitude
        9.5, // Longitude
        15.8 // Altitude
    );

    // Insérez les données dans la base de données
    DataDAO::getInstance()->insert($donnee);
    echo "Données insérées avec succès.<br>";

    // Affichez les données avec l'ID 1
    $donnees = DataDAO::getInstance()->find(1);
    echo "<br>Données avec l'ID 1 :<br>";
    echo $donnees . "<br><br>";

    // Récupérer les données de l'activité avec l'ID 1
    $donnees = DataDAO::getInstance()->findByActivityId(1);
    if (count($donnees) > 0) {
        echo "<br>Données de l'activité avec l'ID 1 :<br>";
        foreach ($donnees as $donnee) {
            echo "ID: " . $donnee->getDataId() . "<br>";
            echo "ID de l'activité: " . $donnee->getActivityID() . "<br>";
            echo "Date: " . $donnee->getDate() . "<br>";
            echo "Description: " . $donnee->getDescription() . "<br>";
            echo "Temps: " . $donnee->getTime() . "<br>";
            echo "Fréquence Cardiaque: " . $donnee->getHeartRate() . "<br>";
            echo "Latitude: " . $donnee->getLatitude() . "<br>";
            echo "Longitude: " . $donnee->getLongitude() . "<br>";
            echo "Altitude: " . $donnee->getAltitude() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune donnée trouvée pour l'activité avec l'ID 1.<br>";
    }

    // Récupérer les données de l'activité avec l'ID 2 (aucune donnée ne devrait être trouvée)
    $donnees = DataDAO::getInstance()->findByActivityId(2);
    if (count($donnees) > 0) {
        echo "<br>Données de l'activité avec l'ID 2 :<br>";
        foreach ($donnees as $donnee) {
            echo "ID: " . $donnee->getDataId() . "<br>";
            echo "ID de l'activité: " . $donnee->getActivityID() . "<br>";
            echo "Date: " . $donnee->getDate() . "<br>";
            echo "Description: " . $donnee->getDescription() . "<br>";
            echo "Temps: " . $donnee->getTime() . "<br>";
            echo "Fréquence Cardiaque: " . $donnee->getHeartRate() . "<br>";
            echo "Latitude: " . $donnee->getLatitude() . "<br>";
            echo "Longitude: " . $donnee->getLongitude() . "<br>";
            echo "Altitude: " . $donnee->getAltitude() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune donnée trouvée pour l'activité avec l'ID 2 (ce qui est prévu).<br><br>";
    }

    // Insertion de 2 données pour l'activité avec l'ID 2
    $donnees = new Data();
    $donnees->init(
        null, // L'ID de la donnée sera généré automatiquement
        2, // ID de l'activité associée
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:07", // Temps
        150, // Fréquence cardiaque
        10.2, // Latitude
        9.5, // Longitude
        15.8 // Altitude
    );

    $donnees2 = new Data();
    $donnees2->init(
        null, // L'ID de la donnée sera généré automatiquement
        2, // ID de l'activité associée
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:07", // Temps
        150, // Fréquence cardiaque
        10.2, // Latitude
        9.5, // Longitude
        15.8 // Altitude
    );

    // Insérez les données dans la base de données
    DataDAO::getInstance()->insert($donnees);
    DataDAO::getInstance()->insert($donnees2);
    echo "2 données de l'activité avec l'ID 2 insérées avec succès.<br>";

    // Afficher les données de l'activité avec l'ID 2
    $donnees = DataDAO::getInstance()->findByActivityId(2);
    if (count($donnees) > 0) {
        echo "<br>Données de l'activité avec l'ID 2 :<br>";
        foreach ($donnees as $donnee) {
            echo "ID: " . $donnee->getDataId() . "<br>";
            echo "ID de l'activité: " . $donnee->getActivityID() . "<br>";
            echo "Date: " . $donnee->getDate() . "<br>";
            echo "Description: " . $donnee->getDescription() . "<br>";
            echo "Temps: " . $donnee->getTime() . "<br>";
            echo "Fréquence Cardiaque: " . $donnee->getHeartRate() . "<br>";
            echo "Latitude: " . $donnee->getLatitude() . "<br>";
            echo "Longitude: " . $donnee->getLongitude() . "<br>";
            echo "Altitude: " . $donnee->getAltitude() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune donnée trouvée pour l'activité avec l'ID 2.<br>";
    }

    // Supprimez les données de l'activité avec l'ID 1
    $donnees = DataDAO::getInstance()->findByActivityId(1);
    if (count($donnees) > 0) {
        foreach ($donnees as $donnee) {
            DataDAO::getInstance()->delete($donnee);
        }
        echo "Données de l'activité avec l'ID 1 supprimées avec succès.<br><br>";
    } else {
        echo "Aucune donnée trouvée pour l'activité avec l'ID 1.<br>";
    }

    // Modification de la donnée avec l'ID 2
    // Créez une instance mise à jour de l'activité
    $donnees2MiseAJour = new Data();
    $donnees2MiseAJour->init(
        2, // L'ID de la donnée
        2, // ID de l'activité associée
        "2023-09-15", // Date de l'activité
        "Course à pied", // Description de l'activité
        "00:55:56", // Le temps est modifiée
        152, // Fréquence cardiaque est modifiée
        10.2, // Latitude
        9.5, // Longitude
        15.8 // Altitude
    );

    // Utilisez la méthode update pour mettre à jour les données
    DataDAO::getInstance()->update($donnees2MiseAJour);
    echo "Données avec l'ID 2 mise à jour avec succès.<br>";

    // Afficher toutes les données
    $donnees = DataDAO::getInstance()->findAll();
    if (count($donnees) > 0) {
        echo "<br>Données dans la base de données :<br>";
        foreach ($donnees as $donnee) {
            echo "ID: " . $donnee->getDataId() . "<br>";
            echo "ID de l'activité: " . $donnee->getActivityId() . "<br>";
            echo "Date: " . $donnee->getDate() . "<br>";
            echo "Description: " . $donnee->getDescription() . "<br>";
            echo "Temps: " . $donnee->getTime() . "<br>";
            echo "Fréquence Cardiaque: " . $donnee->getHeartRate() . "<br>";
            echo "Latitude: " . $donnee->getLatitude() . "<br>";
            echo "Longitude: " . $donnee->getLongitude() . "<br>";
            echo "Altitude: " . $donnee->getAltitude() . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune donnée trouvée dans la base de données.<br>";
    }
}

}

?>