<?php
namespace App\Models;

use App\Core\Database;

class Comment extends BaseModel {
    
    public function __construct(Database $db) {
        // Vinculamos este modelo a la tabla física 'comments'
        parent::__construct($db, 'comments');
    }

    // Método propio: Obtener los comentarios de un post ordenados por fecha de forma cronológica
    public function getByPostId(int $postId): ?array {
        $sql = "SELECT c.*, u.username as autor 
                FROM {$this->table} c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.post_id = :post_id 
                ORDER BY c.created_at ASC"; // Orden cronológico claro exigido en la rúbrica
                
        return $this->db->fetchAll($sql, ['post_id' => $postId]);
    }
}