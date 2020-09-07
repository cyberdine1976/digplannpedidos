<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Provincia{


	//implementamos nuestro constructor
public function __construct(){

}

public function listarProvincia(){
	$sql="SELECT * FROM provincia";
	return ejecutarConsulta($sql);
}
}

 ?>
