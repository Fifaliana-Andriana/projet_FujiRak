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
            // Mettre à jour last_login
            $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Mot de passe incorrect'];
    }

    // Admin : créer un utilisateur
    public function create($email, $password, $classe = 'simple', $role = 'user') {
        // Vérifier si l'email existe déjà
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'Cet email existe déjà'];
        }

        $query = "INSERT INTO " . $this->table . " (email, password, classe, role) 
                  VALUES (:email, :password, :classe, :role)";
        
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
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
        $query = "SELECT id, email, classe, role, is_active, photo, last_login, date_creation 
                  FROM " . $this->table . " ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>