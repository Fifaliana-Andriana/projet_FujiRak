<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $classe;
    public $role;
    public $verification_code;
    public $code_expiration;
    public $is_verified;
    public $is_active;
    public $last_login;
    public $date_creation;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // public function findByEmail($email)
    // {
    //     $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':email', $email);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    // public function findByUsername($username)
    // {
    //     $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':username', $username);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    // APRES - utilise email
    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function createLoginRequest($email)
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email non trouvé'
            ];
        }

        if (!$user['is_active']) {
            return [
                'success' => false,
                'message' => 'Compte désactivé. Contactez l\'administrateur.'
            ];
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $expiration = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $query = "INSERT INTO login_requests (user_id, email, code, date_expiration) VALUES (:user_id, :email, :code, :date_expiration)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':date_expiration', $expiration);

        if ($stmt->execute()) {
            $updateQuery = "UPDATE " . $this->table . " SET verification_code = :code, code_expiration = :expiration WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':code', $code);
            $updateStmt->bindParam(':expiration', $expiration);
            $updateStmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $updateStmt->execute();

            return [
                'success' => true,
                'message' => 'Code envoyé par email',
                'user_id' => $user['id'],
                'email' => $email,
                'code' => $code
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur lors de la génération du code'
        ];
    }

    public function verifyCode($email, $code)
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ];
        }

        if (strtotime($user['code_expiration']) < time()) {
            return [
                'success' => false,
                'message' => 'Code expiré. Veuillez demander un nouveau code.'
            ];
        }

        if ($user['verification_code'] !== $code) {
            return [
                'success' => false,
                'message' => 'Code incorrect'
            ];
        }

        $query = "UPDATE " . $this->table . " SET is_verified = 1, verification_code = NULL, last_login = NOW() WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
        $stmt->execute();

        $updateRequest = "UPDATE login_requests SET is_used = 1 WHERE user_id = :user_id AND code = :code";

        $reqStmt = $this->conn->prepare($updateRequest);
        $reqStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
        $reqStmt->bindParam(':code', $code);
        $reqStmt->execute();

        return [
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => $user
        ];
    }

    // Create a registration request (visitor provides email)
    public function createRegistrationRequest($email)
    {
        // If email already exists as a user, don't create a request
        $existing = $this->findByEmail($email);
        if ($existing) {
            return [
                'success' => false,
                'message' => 'Un compte existe déjà pour cet email.'
            ];
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

        $query = "INSERT INTO registration_requests (email, ip_address) VALUES (:email, :ip)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':ip', $ip);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Demande enregistrée'
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur lors de l\'enregistrement de la demande.'
        ];
    }

    // Return pending registration requests
    public function getPendingRegistrationRequests()
    {
        $query = "SELECT id, email, ip_address, date_creation FROM registration_requests WHERE is_processed = 0 ORDER BY date_creation ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Approve a registration request: create user and mark request processed
    public function approveRegistrationRequest($requestId, $username, $password, $adminId = null)
    {
        // Fetch request
        $query = "SELECT * FROM registration_requests WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
        $stmt->execute();
        $req = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$req) {
            return ['success' => false, 'message' => 'Demande introuvable'];
        }
        if ($req['is_processed']) {
            return ['success' => false, 'message' => 'Demande déjà traitée'];
        }

        // Ensure username unique
        $existingUsername = $this->findByUsername($username);
        if ($existingUsername) {
            return ['success' => false, 'message' => 'Nom d\'utilisateur déjà utilisé'];
        }

        // Prepare basic name from email if possible
        $local = strstr($req['email'], '@', true);
        $nom = $local ?: 'Utilisateur';
        $prenom = '';

        $data = [
            'username' => $username,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $req['email'],
            'password' => $password,
            'classe' => 'simple',
            'role' => 'user'
        ];

        // Create user
        $created = $this->create($data);
        if (!$created) {
            return ['success' => false, 'message' => 'Impossible de créer l\'utilisateur'];
        }

        // Get created user id
        $userId = $this->conn->lastInsertId();

        // Mark request processed
        $update = "UPDATE registration_requests SET is_processed = 1, processed_by = :admin, processed_at = NOW(), user_id = :user WHERE id = :id";
        $uStmt = $this->conn->prepare($update);
        $uStmt->bindParam(':admin', $adminId);
        $uStmt->bindParam(':user', $userId);
        $uStmt->bindParam(':id', $requestId, PDO::PARAM_INT);
        $uStmt->execute();

        return ['success' => true, 'message' => 'Compte créé', 'user_id' => $userId, 'user_email' => $req['email']];
    }

    public function getStatsByClass()
    {
        $query = "SELECT classe, COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1 GROUP BY classe";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalUsers()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAllUsers()
    {
        $query = "SELECT id, username, nom, prenom, email, classe, role, is_verified, is_active, last_login, date_creation FROM " . $this->table . " ORDER BY date_creation DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " (username, nom, prenom, email, password, classe, role, is_verified, is_active) VALUES (:username, :nom, :prenom, :email, :password, :classe, :role, 1, 1)";

        $stmt = $this->conn->prepare($query);

        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':classe', $data['classe']);
        $stmt->bindParam(':role', $data['role']);

        return $stmt->execute();
    }


    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom, email = :email, classe = :classe, role = :role, is_active = :is_active WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':classe', $data['classe']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function deactivate($id)
    {
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }



    public function updateProfile($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);


        return $stmt->execute();
    }


    public function changePassword($id, $newPassword)
    {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

?>