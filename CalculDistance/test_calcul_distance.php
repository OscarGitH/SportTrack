<?php
require_once 'CalculDistanceImpl.php'; // Inclure la classe CalculDistanceImpl

// Lire le fichier JSON externe
$jsonData = file_get_contents('parcours.json'); // Assurez-vous que le chemin du fichier est correct

// Convertir le JSON en tableau associatif
$data = json_decode($jsonData, true);

// Instancier la classe CalculDistanceImpl
$calculDistance = new CalculDistanceImpl();

// Calculer la distance totale du parcours
$parcours = $data['data'];
$distanceTotale = $calculDistance->calculDistanceTrajet($parcours);

// Afficher la distance en mètres
echo "Distance totale du parcours : " . $distanceTotale . " mètres\n";
?>
