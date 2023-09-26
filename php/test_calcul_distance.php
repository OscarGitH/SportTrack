<?php
// Lecture du fichier JSON
$json = file_get_contents("parcours.json");

// Décodage du fichier JSON
$activite = json_decode($json, true);

// Calcul de la distance
require_once "CalculDistanceImpl.php";
$calculDistance = new CalculDistanceImpl();
$distance = $calculDistance->calculDistanceTrajet($activite["data"]);

// Affichage de la distance
echo "Distance parcourue : " . $distance . " km";
?>