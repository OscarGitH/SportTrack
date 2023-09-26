<?php
class Activity {
    private ?int $activityId;
    private int $userId;
    private string $date;
    private string $description;
    private string $time;
    private float $distance;
    private float $averageSpeed;
    private float $maxSpeed;
    private float $totalAltitude;
    private int $averageHeartRate;
    private int $maxHeartRate;
    private int $minHeartRate;

    public function __construct() {}

    public function init(
        $activityId, $userId, $date, $description, $time, $distance, 
        $averageSpeed, $maxSpeed, $totalAltitude, $averageHeartRate,
        $maxHeartRate, $minHeartRate
    ) {
        $this->activityId = $activityId;
        $this->userId = $userId;
        $this->date = $date;
        $this->description = $description;
        $this->time = $time;
        $this->distance = $distance;
        $this->averageSpeed = $averageSpeed;
        $this->maxSpeed = $maxSpeed;
        $this->totalAltitude = $totalAltitude;
        $this->averageHeartRate = $averageHeartRate;
        $this->maxHeartRate = $maxHeartRate;
        $this->minHeartRate = $minHeartRate;
    }

    public function getActivityId(): ?int {
        return $this->activityId;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getTime(): string {
        return $this->time;
    }

    public function getDistance(): float {
        return $this->distance;
    }

    public function getAverageSpeed(): float {
        return $this->averageSpeed;
    }

    public function getMaxSpeed(): float {
        return $this->maxSpeed;
    }

    public function getTotalAltitude(): float {
        return $this->totalAltitude;
    }

    public function getAverageHeartRate(): int {
        return $this->averageHeartRate;
    }

    public function getMaxHeartRate(): int {
        return $this->maxHeartRate;
    }

    public function getMinHeartRate(): int {
        return $this->minHeartRate;
    }

    public function __toString(): string {
        return "Activity: activityId = " . $this->activityId . ", userId = " . $this->userId . ", date = " . $this->date . ", description = " . $this->description . ", time = " . $this->time . ", distance = " . $this->distance . ", averageSpeed = " . $this->averageSpeed . ", maxSpeed = " . $this->maxSpeed . ", totalAltitude = " . $this->totalAltitude . ", averageHeartRate = " . $this->averageHeartRate . ", maxHeartRate = " . $this->maxHeartRate . ", minHeartRate = " . $this->minHeartRate;
    }
}
?>