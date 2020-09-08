<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ciudad
{


	//implementamos nuestro constructor
	public function __construct()
	{
	}

	public function listarCiudadxProvincia($idProvincia)
	{
		$sql = "SELECT * FROM planta WHERE idprovincia = '$idProvincia'";
		return ejecutarConsulta($sql);
	}

	public function listarCiudad()
	{
		$sql = "SELECT * FROM planta";
		return ejecutarConsulta($sql);
	}
}
