<?php
namespace App\Models;

use App\Core\Database;

abstract class BaseModel {
    protected Database $db;
    protected string $table;

    // El constructor recibe la instancia común de Database y el nombre de la tabla
    public function __construct(Database $db, string $table) {
        $this->db = $db;
        $this->table = $table;
    }

    // Tarea 1: Obtener todos los registros de la tabla
    public function getAll(): ?array {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->db->fetchAll($sql);
    }

    // Tarea 2: Obtener un registro por su ID único
    public function getById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    // Tarea 3: Eliminar un registro por su ID
    public function delete(int $id): void {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    // Tarea 4: Insertar un registro dinámicamente usando pares 'campo' => 'valor'
    public function insert(array $data): void {
        // Extraemos los nombres de las columnas (ej: ['email', 'username'])
        $columnas = array_keys($data);
        
        // Creamos los marcadores de parámetros de PDO (ej: [':email', ':username'])
        $marcadores = array_map(fn($col) => ":$col", $columnas);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columnas) . ") 
                VALUES (" . implode(', ', $marcadores) . ")";
        
        $this->db->query($sql, $data);
    }

    // Tarea 5: Modificar un registro existente dinámicamente por su ID
    public function update(int $id, array $data): void {
        $fragmentos = [];
        foreach (array_keys($data) as $columna) {
            $fragmentos[] = "{$columna} = :{$columna}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fragmentos) . " WHERE id = :id_registro_update";
        
        // Añadimos el ID al array de parámetros para ejecutar la sentencia preparada con éxito
        $data['id_registro_update'] = $id;

        $this->db->query($sql, $data);
    }
}