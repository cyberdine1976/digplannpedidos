<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ciudad{


	//implementamos nuestro constructor
public function __construct(){

}

public function listarCiudad($idPlanta){
	$sql="SELECT * FROM planta WHERE idprovincia = '$idPlanta'";
	return ejecutarConsulta($sql);
}
}

 ?>
