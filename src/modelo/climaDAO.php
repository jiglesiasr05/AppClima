<?php
include_once __DIR__ . '/Database.php';

class ClimaDAO {
    public $conexion_bd;

    public function __construct() {
        $this->conexion_bd = Database::conectar();
    }

    /**
     * Inserta una consulta en la base de datos.
     * * Parámetros: 
     * $ciudad, nombre de la ciudad.
     * $temperatura, valor de la temperatura.
     * $descripcion, estado del cielo.
     * $lat, latitud.
     * $lon, longitud.
     * Retorna true si la inserción fue exitosa y false en caso contrario.
     */
    public function insertarConsulta($ciudad, $temperatura, $descripcion, $lat, $lon) {
        $sentencia = $this->conexion_bd->prepare("INSERT INTO historial (ciudad, temperatura, descripcion, latitud, longitud, fecha) VALUES (:ciudad, :temperatura, :descripcion, :latitud, :longitud, NOW())");      
        
        $sentencia->bindParam(':ciudad', $ciudad);
        $sentencia->bindParam(':temperatura', $temperatura);
        $sentencia->bindParam(':descripcion', $descripcion);
        $sentencia->bindParam(':latitud', $lat);
        $sentencia->bindParam(':longitud', $lon);

        try {
            return $sentencia->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function obtenerTodasConsultas() {
        $sentencia = $this->conexion_bd->prepare("SELECT * FROM historial ORDER BY fecha DESC");
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);

        $sentencia->execute();

        return $sentencia->fetchAll();
    }

    public function eliminarConsulta($id) {
        $sentencia = $this->conexion_bd->prepare("DELETE FROM historial WHERE id = :id");
        $sentencia->bindParam(':id', $id);

        try {
            return $sentencia->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
?>