<?php
class Database {
    private static $conexion = null;

    public static function conectar() {
        if (self::$conexion !== null) return self::$conexion;

        try {
            $servidor = getenv('DB_HOST') ?: 'mysql'; 
            $bd   = getenv('MYSQL_DATABASE') ?: 'historial';
            $usuario = getenv('MYSQL_USER') ?: 'lamp_user';
            $clave = getenv('MYSQL_PASSWORD') ?: 'lamp_password';

            $dsn = "mysql:host=$servidor;dbname=$bd;charset=utf8mb4";
            self::$conexion = new PDO($dsn, $usuario, $clave, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            return self::$conexion;
        } catch (PDOException $e) {
            return null; 
        }
    }
}