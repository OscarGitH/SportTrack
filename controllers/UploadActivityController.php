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

                    // Appelez la méthode pour calculer et mettre à jour les valeurs de l'activité
                    $this->calculateAndUpdateActivityValues($activity, $data);

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

    // Méthode pour calculer et mettre à jour les valeurs de l'activité
    private function calculateAndUpdateActivityValues(Activity $activity, $data) {
        $calculDistance = new CalculDistanceImpl();

        // Récupérer les données associées à cette activité
        $dataEntries = DataDAO::getInstance()->findByActivityId($activity->getActivityId());

        // Récupérer le parcours associé à cette activité
        $parcours = $data['data'];

        if ($dataEntries) {
            // Initialiser les variables pour les calculs
            $startTime = null;
            $endTime = null;
            $totalDistance =  $calculDistance->calculDistanceTrajet($parcours);
            $averageSpeed = 0.0; //v = D/T
            $maxSpeed = 0.0; // on ne l'utilise pas
            $totalAltitude = 0.0; // on ne l'utilise pas
            $averageHeartRate = 0;
            $maxHeartRate = 0;
            $minHeartRate = 0;
            $i = 0;

    
            // Parcourir les données pour effectuer les calculs
            foreach ($dataEntries as $dataEntry) {        
                // Mettre à jour les temps de début et de fin en fonction des données
                if ($startTime === null || $dataEntry->getTime() < $startTime) {
                    $startTime = $dataEntry->getTime();
                }
                if ($endTime === null || $dataEntry->getTime() > $endTime) {
                    $endTime = $dataEntry->getTime();
                }
                $averageHeartRate += ($dataEntry->getHeartRate() + $averageHeartRate);
                $i+=1;
                $maxHeartRate = max($maxHeartRate, $dataEntry->getHeartRate());
                $minHeartRate = min($minHeartRate, $dataEntry->getHeartRate());
            }    

            // Calculer la durée totale de l'activité en secondes
            if ($startTime !== null && $endTime !== null) {
                $startTimestamp = strtotime($startTime);
                $endTimestamp = strtotime($endTime);
                $activityDuration = $endTimestamp - $startTimestamp;
            } else {
                $activityDuration = 0;
            }

            $averageHeartRate = $averageHeartRate/$i;
            $averageSpeed = $totalDistance/$activityDuration;

    
            // Mettre à jour les propriétés de l'activité avec les valeurs calculées
            $activity->setTime($this->formatTime($activityDuration));
            $activity->setDistance($totalDistance);
            $activity->setAverageSpeed($averageSpeed);
            $activity->setMaxSpeed($maxSpeed);
            $activity->setTotalAltitude($totalAltitude);
            $activity->setAverageHeartRate($averageHeartRate);
            $activity->setMaxHeartRate($maxHeartRate);
            $activity->setMinHeartRate($minHeartRate);

            //affichage tout activité
            $activities = ActivityDAO::getInstance()->findAll();
            // Mettre à jour l'activité dans la base de données
            ActivityDAO::getInstance()->update($activity);

            // Rediriger l'utilisateur vers la page de récapitulation
            $this->render('activities', []);
        } else {
            echo "erreur21";
            // Il n'y a pas de données associées à cette activité, renvoyez un message d'erreur à l'utilisateur
            $this->render('error', ['message' => 'Il n\'y a pas de données associées à cette activité']);
        }
    }
    
    // Fonction pour formater la durée en HH:MM:SS
    function formatTime($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
}
?>