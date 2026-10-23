<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database {
    private $pdo;

    public function __construct() {
        // Cargamos la configuración externa requerida en la instrucción
        $config = require __DIR__ . '/../../config.php';
        $dbConfig = $config['db'];

        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";

        try {
            // Establecemos conexión activando el modo excepción exigido
            $this->pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Error crítico en la conexión de datos: " . $e->getMessage());
        }
    }

    // Método genérico 1: Procesa consultas y devuelve el PDOStatement
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Método genérico 2: Devuelve un array completo con todos los resultados
    public function fetchAll(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetchAll();
        return $result ?: null;
    }

    // Método genérico 3: Devuelve una única fila (o null si no hay coincidencia)
    public function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}