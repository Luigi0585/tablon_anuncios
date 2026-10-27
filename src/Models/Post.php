<?php
namespace App\Models;

use App\Core\Database;

class Post extends BaseModel {
    
    public function __construct(Database $db) {
        // Vinculamos este modelo a la tabla física 'posts'
        parent::__construct($db, 'posts');
    }

    // Tarea exigida: Buscador simple que filtre las publicaciones por título o categoría
    public function search(string $termino): ?array {
        $sql = "SELECT p.*, u.username as autor 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.title LIKE :termino OR p.category LIKE :termino 
                ORDER BY p.created_at DESC";
                
        return $this->db->fetchAll($sql, ['termino' => "%{$termino}%"]);
    }

    // Trae todos los posts uniendo el nombre del autor mediante un LEFT JOIN
    public function getAllWithAutor(): ?array {
        $sql = "SELECT p.*, u.username as autor 
                FROM {$this->table} p 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql);
    }
}