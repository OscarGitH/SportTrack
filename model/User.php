<?php
class User {
    private ?int $userId;
    private string $lastName;
    private string $firstName;
    private string $birthDate;
    private string $gender;
    private float $height;
    private float $weight;
    private string $email;
    private string $password;

    public function __construct() {
    }

    public function init($id, $lastName, $firstName, $birthDate, $gender, $height, $weight, $email, $password) {
        $this->userId = $id;    
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
        $this->gender = $gender;
        $this->height = $height;
        $this->weight = $weight;
        $this->email = $email;
        $this->password = $password;
    }   

    public function getUserId(): ?int {
        return $this->userId;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getBirthDate(): string {
        return $this->birthDate;
    }

    public function getGender(): string {
        return $this->gender;
    }

    public function getHeight(): float {
        return $this->height;
    }

    public function getWeight(): float {
        return $this->weight;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    public function __toString(): string {
        return "User : userId = " . $this->userId . ", lastName = " . $this->lastName . ", firstName = " . $this->firstName . ", birthDate = " . $this->birthDate . ", gender = " . $this->gender . ", height = " . $this->height . ", weight = " . $this->weight . ", email = " . $this->email . ", password = " . $this->password;
    }
}
?>