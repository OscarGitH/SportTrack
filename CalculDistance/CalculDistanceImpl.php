<?php
require_once 'CalculDistance.php'; // Inclure l'interface CalculDistance

class CalculDistanceImpl implements CalculDistance {
    private const RADIUS = 6378.137; // Rayon de la Terre en kilomètres

    public function calculDistance2PointsGPS(float $lat1, float $long1, float $lat2, float $long2): float {
        // Conversion des coordonnées en radians
        $lat1 = deg2rad($lat1);
        $long1 = deg2rad($long1);
        $lat2 = deg2rad($lat2);
        $long2 = deg2rad($long2);

        // Calcul de la distance
        $deltaLat = $lat2 - $lat1;
        $deltaLong = $long2 - $long1;
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLong / 2) * sin($deltaLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = self::RADIUS * $c * 1000; // Convertir en mètres

        return $distance;
    }

    public function calculDistanceTrajet(array $parcours): float {
        $distance = 0;
        $numPoints = count($parcours);

        for ($i = 1; $i < $numPoints; $i++) {
            $lat1 = $parcours[$i - 1]["latitude"];
            $long1 = $parcours[$i - 1]["longitude"];
            $lat2 = $parcours[$i]["latitude"];
            $long2 = $parcours[$i]["longitude"];
            $distance += $this->calculDistance2PointsGPS($lat1, $long1, $lat2, $long2);
        }

        return $distance;
    }
}
?>
