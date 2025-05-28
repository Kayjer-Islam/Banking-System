<?php
require_once 'db.php';

class ProfileModel {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getUserProfileByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, username, age, email, profile_picture FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfile($userId, $username, $age, $password = null, $profile_picture = null) {
        $query = "UPDATE users SET username=?, age=?";
        $params = [$username, $age];
        $types = "si";

        if ($password) {
            $query .= ", password=?";
            $params[] = password_hash($password, PASSWORD_BCRYPT);
            $types .= "s";
        }

        if ($profile_picture) {
            $query .= ", profile_picture=?";
            $params[] = $profile_picture;
            $types .= "s";
        }

        $query .= " WHERE id=?";
        $params[] = $userId;
        $types .= "i";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }
}
?>
