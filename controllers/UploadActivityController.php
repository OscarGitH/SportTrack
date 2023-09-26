<?php
require(__ROOT__.'/controllers/Controller.php');

class UploadActivityController extends Controller {
    public function get($request) {
        $this->render('upload_activity_form', []);
    }

    // Méthode pour gérer la requête HTTP POST pour le téléchargement de fichiers
    public function post($request) {
        // Vérifiez si un fichier a été téléchargé avec succès
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file']['tmp_name']; // Utilisez 'tmp_name' pour obtenir le nom temporaire du fichier

            // Lisez le contenu du fichier JSON téléchargé
            $jsonContent = file_get_contents($file);

            // Analysez le contenu JSON
            $data = json_decode($jsonContent, true);
            
            session_start();

            if (isset($_SESSION['userId']) && isset($data['data']) && is_array($data['data'])) {
                // Vous pouvez maintenant utiliser ces données dans votre script

                // Assurez-vous que les données ont été correctement extraites
                if ($data) {
                    // Le reste de votre code pour traiter les données et les insérer dans la base de données
                    // Exemple : Créez une nouvelle activité
                    $activity = new Activity();
                    $activity->init(
                        null, // Laissez l'ID de l'activité comme null pour qu'il soit généré automatiquement
                        $_SESSION['userId'],
                        $data['activity']['date'],
                        $data['activity']['description'],
                        "00:00:00", // Vous pouvez ajuster cela en fonction de vos besoins
                        0.0, // Remplacez $distance par la distance calculée à partir des données
                        0.0, // Remplacez $averageSpeed par la vitesse moyenne calculée
                        0.0, // Remplacez $maxSpeed par la vitesse maximale calculée
                        0.0, // Remplacez $totalAltitude par l'altitude totale calculée
                        0, // Remplacez $averageHeartRate par la fréquence cardiaque moyenne calculée
                        0, // Remplacez $maxHeartRate par la fréquence cardiaque maximale calculée
                        0 // Remplacez $minHeartRate par la fréquence cardiaque minimale calculée
                    );

                    // Insérez la nouvelle activité dans la base de données
                    ActivityDAO::getInstance()->insert($activity);
                    
                    // Vous pouvez également traiter les données individuelles et les insérer dans la table Data si nécessaire
                    foreach ($data['data'] as $dataPoint) {
                        $dataEntry = new Data();
                        $dataEntry->init(
                            null, // Laissez l'ID de la donnée comme null pour qu'il soit généré automatiquement
                            $activity->getActivityId(),
                            $dataPoint['time'],
                            $data['activity']['description'],
                            $dataPoint['time'], // Vous pouvez ajuster cela en fonction de vos besoins
                            $dataPoint['cardio_frequency'],
                            $dataPoint['latitude'],
                            $dataPoint['longitude'],
                            $dataPoint['altitude']
                        );

                        // Insérez la nouvelle donnée dans la base de données
                        DataDAO::getInstance()->insert($dataEntry);
                    }
                    // Redirigez l'utilisateur vers une page de confirmation ou de récapitulation
                    $this->render('confirmation', ['message' => 'Le fichier a été téléchargé avec succès']);
                } else {
                    echo "erreur2";
                    // Le fichier JSON est mal formaté, renvoyez un message d'erreur à l'utilisateur
                    $this->render('error', ['message' => 'Le fichier JSON est mal formaté']);
                }
            } else {
                echo "erreur1";
                // L'utilisateur n'est pas connecté, renvoyez un message d'erreur à l'utilisateur
                $this->render('error', ['message' => 'Vous devez être connecté pour télécharger un fichier']);
            }
        } else {
            echo "erreur0";
            // Le fichier n'a pas été téléchargé avec succès, renvoyez un message d'erreur à l'utilisateur
            $this->render('error', ['message' => 'Le fichier n\'a pas été téléchargé avec succès']);
        }
    }
}
?>