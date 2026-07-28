<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;
    private $table = 'users';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ------------------------------------------------------------------
    // Lecture
    // ------------------------------------------------------------------

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Liste de tous les utilisateurs, avec filtre optionnel par classe
    public function getAllUsers($classe = null)
    {
        $sql = "SELECT id, username, nom, prenom, email, photo, classe, role, is_active, last_login, created_at
                FROM {$this->table}";
        $params = [];

        if (in_array($classe, ['simple', 'gold', 'plus'], true)) {
            $sql .= " WHERE classe = :classe";
            $params[':classe'] = $classe;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalUsers()
    {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE is_active = 1");
        return (int) $stmt->fetch()['total'];
    }

    // Nombre d'utilisateurs par classe (simple / gold / plus)
    public function getClassCounts()
    {
        $stmt = $this->conn->query(
            "SELECT classe, COUNT(*) AS total FROM {$this->table} WHERE is_active = 1 GROUP BY classe"
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // toujours retourner les 3 classes, même à 0
        $result = ['simple' => 0, 'gold' => 0, 'plus' => 0];
        foreach ($rows as $row) {
            $result[$row['classe']] = (int) $row['total'];
        }
        return $result;
    }

    // Courbe d'intégration des membres (par jour / mois / année)
    public function getRegistrationStats($period = 'month')
    {
        $format = match ($period) {
            'day' => '%Y-%m-%d',
            'year' => '%Y',
            default => '%Y-%m',
        };

        $stmt = $this->conn->prepare(
            "SELECT DATE_FORMAT(created_at, '$format') AS periode, COUNT(*) AS total
             FROM {$this->table}
             GROUP BY periode
             ORDER BY periode ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ------------------------------------------------------------------
    // Écriture
    // ------------------------------------------------------------------

    // Admin : créer un compte (après demande reçue par e-mail)
    public function create(array $data)
    {
        if ($this->findByEmail($data['email']) || $this->findByUsername($data['username'])) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (username, nom, prenom, email, password, classe, role)
                VALUES (:username, :nom, :prenom, :email, :password, :classe, :role)";

        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        return $stmt->execute([
            ':username' => $data['username'],
            ':nom' => $data['nom'] ?? '',
            ':prenom' => $data['prenom'] ?? '',
            ':email' => $data['email'],
            ':password' => $hashedPassword,
            ':classe' => $data['classe'] ?? 'simple',
            ':role' => $data['role'] ?? 'user',
        ]);
    }

    // Admin : modifier un compte (email, username, classe, rôle, statut)
    public function updateUser($id, array $data)
    {
        $sql = "UPDATE {$this->table} SET
                    username = :username,
                    email = :email,
                    classe = :classe,
                    role = :role,
                    is_active = :is_active,
                    nom = :nom,
                    prenom = :prenom
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':classe' => $data['classe'],
            ':role' => $data['role'],
            ':is_active' => $data['is_active'] ?? 1,
            ':nom' => $data['nom'] ?? '',
            ':prenom' => $data['prenom'] ?? '',
            ':id' => $id,
        ]);
    }

    // Admin : changer le mot de passe d'un utilisateur
    public function changePassword($id, $newPassword)
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET password = :password WHERE id = :id");
        return $stmt->execute([':password' => $hashed, ':id' => $id]);
    }

    // Utilisateur : mettre à jour sa propre photo de profil (seul champ auto-modifiable)
    // Suppression douce : désactive le compte sans toucher à son historique
    public function softDelete($id)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_active = 0 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function updateAvatar($id, $relativePath)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET photo = :photo WHERE id = :id");
        return $stmt->execute([':photo' => $relativePath, ':id' => $id]);
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return ['success' => false, 'message' => 'Email non trouvé'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Compte désactivé'];
        }

        if (password_verify($password, $user['password'])) {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = :id");
            $stmt->execute([':id' => $user['id']]);
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Mot de passe incorrect'];
    }
}
