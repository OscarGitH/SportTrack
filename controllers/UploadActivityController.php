<?php
require(__ROOT__.'/controllers/Controller.php');

class UploadActivityController extends Controller {
    public function get($request) {
        $this->render('upload_activity_form', []);
    }
/*
    // Méthode pour gérer la requête HTTP POST pour le téléchargement de fichiers
    public function post($request) {
        
        // Vérifiez si un fichier a été téléchargé avec succès
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['fichier']['tmp_name'];
            
            // Lisez le contenu du fichier JSON téléchargé
            $jsonContent = file_get_contents($file);

            // Analysez le contenu JSON
            $data = json_decode($jsonContent, true);

            // Assurez-vous que les données ont été correctement extraites
            if ($data) {
                // Vous pouvez maintenant utiliser les données pour peupler la base de données

                // Exemple : Créez une nouvelle activité
                $activity = new Activity();
                $activity->init(
                    null, // Laissez l'ID de l'activité comme null pour qu'il soit généré automatiquement
                    $userId, // Remplacez $userId par l'ID de l'utilisateur
                    $data['activity']['date'],
                    $data['activity']['description'],
                    $data['activity']['data'][0]['time'], // Vous pouvez ajuster cela en fonction de vos besoins
                    $distance, // Remplacez $distance par la distance calculée à partir des données
                    $averageSpeed, // Remplacez $averageSpeed par la vitesse moyenne calculée
                    $maxSpeed, // Remplacez $maxSpeed par la vitesse maximale calculée
                    $totalAltitude, // Remplacez $totalAltitude par l'altitude totale calculée
                    $averageHeartRate, // Remplacez $averageHeartRate par la fréquence cardiaque moyenne calculée
                    $maxHeartRate, // Remplacez $maxHeartRate par la fréquence cardiaque maximale calculée
                    $minHeartRate // Remplacez $minHeartRate par la fréquence cardiaque minimale calculée
                );

                // Insérez la nouvelle activité dans la base de données
                ActivityDAO::getInstance()->insert($activity);

                // Vous pouvez également traiter les données individuelles et les insérer dans la table Data si nécessaire
                foreach ($data['activity']['data'] as $dataPoint) {
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
                // Le fichier JSON est mal formaté, renvoyez un message d'erreur à l'utilisateur
                $this->render('error', ['message' => 'Le fichier JSON est mal formaté']);
            }
        } else {
            // Une erreur s'est produite lors du téléchargement du fichier, renvoyez un message d'erreur à l'utilisateur
            $this->render('error', ['message' => 'Une erreur s\'est produite lors du téléchargement du fichier']);
        }
    }    
    */
}
?>