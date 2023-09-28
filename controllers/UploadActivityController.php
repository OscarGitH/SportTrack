<?php
require(__ROOT__.'/controllers/Controller.php');

class UploadActivityController extends Controller {
    public function get($request) {
        $this->render('upload_activity_form', []);
    }

    // Méthode pour gérer la requête HTTP POST pour le téléchargement de fichiers
    public function post($request) {
        // Vérifie si un fichier a été téléchargé avec succès
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file']['tmp_name']; // Utilisez 'tmp_name' pour obtenir le nom temporaire du fichier

            // Lecture du contenu du fichier JSON téléchargé
            $jsonContent = file_get_contents($file);


            // Analyse le contenu JSON
            $data = json_decode($jsonContent, true);
            
            session_start();

            if (isset($_SESSION['userId']) && isset($data['data']) && is_array($data['data'])) {
                if ($data) {
                    $activity = new Activity();

                    
                    // On récupère la description de l'activité si elle est renseignée sinon on laisse la description de l'activité par défaut
                    if (isset($_POST['description']) && !empty($_POST['description'])) {
                        $description = $_POST['description'];
                    } else {
                        $description = $data['activity']['description'];
                    }

                    $activity->init(
                        null, // Laissez l'ID de l'activité comme null pour qu'il soit généré automatiquement
                        $_SESSION['userId'],
                        // date est la concatenation de la date et l'heure de début de l'activité
                        $data['activity']['date']." ".$data['data'][0]['time'],
                        $description,
                        "00:00:00", // Remplace $time par la durée calculée à partir des données
                        0.0, // Remplace $distance par la distance calculée à partir des données
                        0.0, // Remplace $averageSpeed par la vitesse moyenne calculée
                        0.0, // Remplace $maxSpeed par la vitesse maximale calculée
                        0.0, // Remplace $totalAltitude par l'altitude totale calculée
                        0, // Remplace $averageHeartRate par la fréquence cardiaque moyenne calculée
                        0, // Remplace $maxHeartRate par la fréquence cardiaque maximale calculée
                        0 // Remplace $minHeartRate par la fréquence cardiaque minimale calculée
                    );

                    // Insére la nouvelle activité dans la base de données
                    ActivityDAO::getInstance()->insert($activity);
                    
                    //On peut également traiter les données individuelles et les insérer dans la table Data si nécessaire
                    foreach ($data['data'] as $dataPoint) {
                        $dataEntry = new Data();
                        $dataEntry->init(
                            null, // Laisse l'ID de la donnée comme null pour qu'il soit généré automatiquement
                            $activity->getActivityId(),
                            $dataPoint['time'],
                            $data['activity']['description'],
                            $dataPoint['time'],
                            $dataPoint['cardio_frequency'],
                            $dataPoint['latitude'],
                            $dataPoint['longitude'],
                            $dataPoint['altitude']
                        );

                        // Insére la nouvelle donnée dans la base de données
                        DataDAO::getInstance()->insert($dataEntry);
                    }

                    // Appele la méthode pour calculer et mettre à jour les valeurs de l'activité
                    $this->calculateAndUpdateActivityValues($activity, $data);

                    // Redirige l'utilisateur vers une page de confirmation ou de récapitulation
                    $this->render('confirmation', ['message' => 'Le fichier a été téléchargé avec succès']);
                } else {
                    echo "erreur2";
                    // Le fichier JSON est mal formaté, renvoye un message d'erreur à l'utilisateur
                    $this->render('error', ['message' => 'Le fichier JSON est mal formaté']);
                }
            } else {
                echo "erreur1";
                // L'utilisateur n'est pas connecté, renvoie un message d'erreur à l'utilisateur
                $this->render('error', ['message' => 'Vous devez être connecté pour télécharger un fichier']);
            }

            // Redirection vers la page d'accueil
            header('Location: /activities');
            exit;
        } else {
            echo "erreur0";
            // Le fichier n'a pas été téléchargé avec succès, renvoie un message d'erreur à l'utilisateur
            $this->render('error', ['message' => 'Le fichier n\'a pas été téléchargé avec succès']);
        }
    }

    // Méthode pour calculer et mettre à jour les valeurs de l'activité
    private function calculateAndUpdateActivityValues(Activity $activity, $data) {
        $calculDistance = new CalculDistanceImpl();

        // Récupére les données associées à cette activité
        $dataEntries = DataDAO::getInstance()->findByActivityId($activity->getActivityId());

        // Récupére le parcours associé à cette activité
        $parcours = $data['data'];

        if ($dataEntries) {
            // Initialise les variables pour les calculs
            $startTime = null;
            $endTime = null;
            // on divise par 1000 pour avoir la distance en km et on arrondi à 2 chiffres après la virgule
            $totalDistance = round($calculDistance->calculDistanceTrajet($parcours)/1000, 2);
            $averageSpeed = 0.0;
            $totalAltitude = 0.0;
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
                $maxHeartRate = max($maxHeartRate, $dataEntry->getHeartRate());
                // min ne peut pas être 0
                if ($minHeartRate === 0) {
                    $minHeartRate = $dataEntry->getHeartRate();
                } else {
                    $minHeartRate = min($minHeartRate, $dataEntry->getHeartRate());
                }
            } 
            
            // Calculer la fréquence cardiaque moyenne
            foreach ($dataEntries as $dataEntry) {
                $averageHeartRate += $dataEntry->getHeartRate();
                $i++;
            }

            $averageHeartRate = $averageHeartRate/$i;

            // Calculer la durée totale de l'activité en secondes
            if ($startTime !== null && $endTime !== null) {
                $startTimestamp = strtotime($startTime);
                $endTimestamp = strtotime($endTime);
                $activityDuration = $endTimestamp - $startTimestamp;
            } else {
                $activityDuration = 0;
            }

            $averageSpeed = round($totalDistance/($activityDuration/3600), 2);

            // Calculer le dénivelé total
            $totalAltitude = 0.0;
            $previousAltitude = null;
            foreach ($dataEntries as $dataEntry) {
                if ($previousAltitude !== null) {
                    $totalAltitude += abs($dataEntry->getAltitude() - $previousAltitude);
                }
                $previousAltitude = $dataEntry->getAltitude();
            }
  
            // Mettre à jour les propriétés de l'activité avec les valeurs calculées
            $activity->setTime($this->formatTime($activityDuration));
            $activity->setDistance($totalDistance);
            $activity->setAverageSpeed($averageSpeed);
            $activity->setTotalAltitude($totalAltitude);
            $activity->setAverageHeartRate($averageHeartRate);
            $activity->setMaxHeartRate($maxHeartRate);
            $activity->setMinHeartRate($minHeartRate);

            //affichage tout activité
            $activities = ActivityDAO::getInstance()->findAll();
            // Mettre à jour l'activité dans la base de données
            ActivityDAO::getInstance()->update($activity);

            // Rediriger l'utilisateur vers la page de récapitulation
            $this->render('activities', ['activities' => $activities]);
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