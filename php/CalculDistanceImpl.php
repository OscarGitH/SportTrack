<?php
require_once "CalculDistance.php";

class CalculDistanceImpl implements CalculDistance {

    public function calculDistance2PointsGPS(float $lat1, float $long1, float $lat2, float $long2) : float {
        $R = 6378.137; // km

        // Conversion en radian
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($long1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($long2);

        // Calcul de la distance
        $d = $R * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon2 - $lon1));

        return $d;
    }

    public function calculDistanceTrajet(array $parcours): float {
        $distance = 0;
        for ($i = 0; $i < count($parcours) - 1; $i++) { // Parcours du tableau jusqu'à l'avant-dernier élément

            // Calcul de la distance entre les deux points GPS
            $distance += $this->calculDistance2PointsGPS($parcours[$i]["latitude"], $parcours[$i]["longitude"],
            $parcours[$i + 1]["latitude"], $parcours[$i + 1]["longitude"]);
        }
        return $distance;
    }
}