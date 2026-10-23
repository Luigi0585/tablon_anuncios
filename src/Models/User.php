<?php
namespace App\Models;

use App\Core\Database;

class User extends BaseModel {
    
    // Pasamos la conexión común y el nombre de la tabla al padre
    public function __construct(Database $db) {
        parent::__construct($db, 'users');
    }

    // Tarea exigida: Buscar usuario por email y verificar su contraseña
    public function findByEmail(string $email, string $password): ?int {
        $sql = "SELECT id, password FROM {$this->table} WHERE email = :email";
        $user = $this->db->fetchOne($sql, ['email' => $email]);

        // Si existe el usuario y el hash de la contraseña coincide
        if ($user && password_verify($password, $user['password'])) {
            return (int) $user['id']; // Devuelve el ID del usuario
        }

        return null; // Credenciales incorrectas
    }
}