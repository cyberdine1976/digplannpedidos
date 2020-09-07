<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Vehiculo
{


    //implementamos nuestro constructor
    public function __construct()
    {
    }

    public function listarVehiculo($idPlanta)
    {
        $sql = "SELECT * FROM disponibilidad d INNER JOIN categoria_vehiculo cv ON cv.idcategoria_vehiculo = d.idcategoria_vehiculo WHERE idplanta = '$idPlanta'";
        return ejecutarConsulta($sql);
    }

    public function listarCategoriaVehiculo()
    {
        $sql = "SELECT * FROM categoria_vehiculo";
        return ejecutarConsulta($sql);
    }

    public function obtenerFechaHoraDisponible($idCategoriaVehiculo) {
        $sql = "SELECT * FROM disponibilidad d INNER JOIN categoria_vehiculo cv ON cv.idcategoria_vehiculo = d.idcategoria_vehiculo WHERE idplanta = '$idCategoriaVehiculo'";
        return ejecutarConsulta($sql);
    }
}
