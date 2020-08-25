<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ingreso{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$planta,$hora_entrega,$fecha_hora,$fecha_entrega,$observaciones,$total_pedido,$idarticulo,$cantidad,$precio_venta){
	$sql="INSERT INTO pedidos (idcliente,idusuario,tipo_comprobante,planta,hora_entrega,fecha_hora,fecha_entrega,observaciones,total_pedido,estado) VALUES ('$idcliente','$idusuario','$tipo_comprobante','$planta','$hora_entrega','$fecha_hora','$fecha_entrega','$observaciones','$total_pedido','Aceptado')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_pedidos (idventa,idarticulo,cantidad,precio_venta,descuento) VALUES('$idpedidonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos])";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function anular($idpedido){
	$sql="UPDATE pedidos SET estado='Anulado' WHERE idpedido='$idpedido'";
	return ejecutarConsulta($sql);
}


//metodo para mostrar registros
public function mostrar($idpedido){
	$sql="SELECT pe.idpedido,DATE(pe.fecha_hora) as fecha,DATE(pe.fecha_entrega) as fechaentrega,pe.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, pe.tipo_comprobante,pe.planta,pe.hora_entrega,pe.observaciones,pe.total_pedido,pe.estado FROM pedidos pe INNER JOIN persona p ON pe.idcliente=p.idpersona INNER JOIN usuario u ON pe.idusuario=u.idusuario  WHERE idpedido='$idpedido'";
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idpedido){
	$sql="SELECT dp.idpedido,dp.idarticulo,a.nombre,dp.cantidad,dp.precio_venta FROM detalle_pedidos dp INNER JOIN articulo a ON dp.idarticulo=a.idarticulo WHERE dp.idpedido='$idpedido'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT pe.idpedido,DATE(pe.fecha_hora) as fecha,pe.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, pe.tipo_comprobante,pe.planta,pe.hora_entrega,pe.total_pedido,pe.estado FROM pedidos pe INNER JOIN persona p ON pe.idcliente=p.idpersona INNER JOIN usuario u ON pe.idusuario=u.idusuario ORDER BY pe.idpedido DESC ";
	return ejecutarConsulta($sql);
}

}

 ?>
