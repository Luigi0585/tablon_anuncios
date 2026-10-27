<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database {
    private $pdo;

    public function __construct() {
        // Cargamos la configuración externa requerida en la instrucción
        $config = require __DIR__ . '/../../config.php'; //Lee el archivo config.php de la raíz del proyecto empleando una ruta absoluta con __DIR__. Con esos datos construye el DSN (Data Source Name), que es la cadena de texto con el formato exacto que exige PDO para saber a qué servidor conectarse.
        $dbConfig = $config['db'];

        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";

        try {
            // Establecemos conexión activando el modo excepción exigido
            $this->pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Fuerza a que cualquier fallo de SQL (como una tabla mal escrita o un error de sintaxis) lance una excepción capturable.
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //Configura a PDO para que todas las consultas devuelvan los datos en forma de array asociativo (donde las claves son los nombres de las columnas de la tabla, ej: $usuario['email']), eliminando la información duplicada por índices numéricos.
            ]);
        } catch (PDOException $e) {
            die("Error crítico en la conexión de datos: " . $e->getMessage());
        }
    }

    // Método genérico 1: Procesa consultas y devuelve el PDOStatement
    // Es el método central de la clase. Toma una cadena SQL con marcadores 
    // (ej: WHERE id = :id) y un array con los valores.
    // Al usar prepare() y execute(), está usando Sentencias Preparadas, lo que separa de 
    // forma estricta la estructura de la consulta SQL de los datos introducidos por el 
    // usuario, neutralizando al 100% los ataques de Inyección SQL.
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Método genérico 2: Devuelve un array completo con todos los resultados
    // Ejecuta la consulta mediante query() y extrae todas las filas resultantes (útil para 
    // listar anuncios en el tablón).
    public function fetchAll(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetchAll();
        return $result ?: null;
    }

    // Método genérico 3: Devuelve una única fila (o null si no hay coincidencia)
    // Hace exactamente lo mismo que fetchAll, pero usando fetch() para extraer únicamente la
    // primera fila que coincida (ideal para buscar un usuario por su ID o email).
    public function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}