<?php

class Data {
    private ?int $dataId; // Change from idDonnées to dataId
    private int $activityId; // Change from lActivité to activityId
    private string $date;
    private string $description;
    private string $time; // Change from temps to time
    private int $heartRate; // Change from frequenceCardiaque to heartRate
    private float $latitude;
    private float $longitude;
    private float $altitude;

    public function __construct() {}

    public function init(
        $dataId,
        $activityId,
        $date,
        $description,
        $time,
        $heartRate,
        $latitude,
        $longitude,
        $altitude
    ) {
        // Vérifiez si $activityId est null avant de l'assigner
        if ($activityId !== null) {
            $this->activityId = $activityId;
        }

        // Assignez les autres propriétés normalement
        $this->dataId = $dataId;
        $this->date = $date;
        $this->description = $description;
        $this->time = $time;
        $this->heartRate = $heartRate;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
    }

    public function getDataId(): ?int { // Change from getIdDonnées to getDataId
        return $this->dataId; // Change from idDonnées to dataId
    }

    public function getActivityId(): int { // Change from getLActivité to getActivityId
        return $this->activityId;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getTime(): string { // Change from getTemps to getTime
        return $this->time;
    }

    public function getHeartRate(): int { // Change from getFrequenceCardiaque to getHeartRate
        return $this->heartRate;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function getAltitude(): float {
        return $this->altitude;
    }

    public function __toString(): string {
        return "Données : dataId = " . $this->dataId . ", activityId = " . $this->activityId . ", date = " . $this->date . ", description = " . $this->description . ", time = " . $this->time . ", heartRate = " . $this->heartRate . ", latitude = " . $this->latitude . ", longitude = " . $this->longitude . ", altitude = " . $this->altitude;
    }
}
?>