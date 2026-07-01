<?php

class Conexion
{
    private $host = "localhost";
    private $db = "parcial_itech";
    private $user = "root";
    private $pass = "";
    private $pdo;

   
    public function conectar()
    {
        if (!$this->pdo) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    /**
     * Ejecuta cualquier sentencia (INSERT, UPDATE, DELETE, SELECT)
     * con parámetros seguros (prepared statements).
     */
    public function ejecutar($sql, $params = [])
    {
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Devuelve todas las filas de una consulta SELECT.
     */
    public function obtenerTodos($sql, $params = [])
    {
        return $this->ejecutar($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve una sola fila de una consulta SELECT.
     */
    public function obtenerUno($sql, $params = [])
    {
        $resultado = $this->ejecutar($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /**
     * Devuelve el ID autoincremental del último INSERT.
     */
    public function ultimoId()
    {
        return $this->conectar()->lastInsertId();
    }
}