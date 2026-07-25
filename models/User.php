<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Trouver un utilisateur par email
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifier le mot de passe
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email non trouvé'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Compte désactivé'];
        }

        if (password_verify($password, $user['password'])) {
            $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Mot de passe incorrect'];
    }

    // Admin : créer un utilisateur
    public function create($email, $username, $nom, $prenom, $password, $classe = 'simple', $role = 'user') {
        // Vérifier si l'email existe déjà
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'Cet email existe déjà'];
        }

        $query = "INSERT INTO " . $this->table . " (nom, prenom, email, password, classe, role, is_verified, is_active)
                  VALUES (:nom, :prenom, :email, :password, :classe, :role, 1, 1)";
        
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':classe', $classe);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Utilisateur créé'];
        }

        return ['success' => false, 'message' => 'Erreur création'];
    }

    // Récupérer tous les utilisateurs
    public function getAll() {
        $query = "SELECT id, nom, prenom, email, classe, role, is_active, last_login, date_creation
                  FROM " . $this->table . " ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', (int) $userId, PDO::PARAM_INT);
        return $stmt->execute() && $stmt->rowCount() === 1;
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getStatsByClass() {
        $query = "SELECT classe, COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1 GROUP BY classe";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAvatar($userId, $avatarPath) {
        return true;
    }

    public function changePassword($userId, $newPassword) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>