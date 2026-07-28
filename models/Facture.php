<?php
// models/Facture.php
require_once __DIR__ . '/../config/database.php';

class Facture
{
    private $conn;
    private $table = 'factures';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($userId, $uploadedBy, $originalName, $storedName, $fileType, $fileSize, $description)
    {
        $sql = "INSERT INTO {$this->table}
                (user_id, uploaded_by, original_name, stored_name, file_type, file_size, description)
                VALUES (:user_id, :uploaded_by, :original_name, :stored_name, :file_type, :file_size, :description)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':uploaded_by' => $uploadedBy,
            ':original_name' => $originalName,
            ':stored_name' => $storedName,
            ':file_type' => $fileType,
            ':file_size' => $fileSize,
            ':description' => $description,
        ]);
    }

    // Tous les documents reçus par un utilisateur donné
    public function getByUser($userId)
    {
        $stmt = $this->conn->prepare(
            "SELECT f.*, u.username AS uploaded_by_name
             FROM {$this->table} f
             INNER JOIN users u ON u.id = f.uploaded_by
             WHERE f.user_id = :user_id
             ORDER BY f.created_at DESC"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vue admin : tous les documents envoyés, tous utilisateurs confondus
    public function getAll()
    {
        $stmt = $this->conn->query(
            "SELECT f.*, u.username AS destinataire, a.username AS uploaded_by_name
             FROM factures f
             INNER JOIN users u ON u.id = f.user_id
             INNER JOIN users a ON a.id = f.uploaded_by
             ORDER BY f.created_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
