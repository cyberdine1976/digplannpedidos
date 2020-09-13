<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Venta
{


	//implementamos nuestro constructor
	public function __construct()
	{
	}

	//metodo insertar registro
	public function insertar($idcliente, $idusuario, $tipo_comprobante, $planta, $tipo_vehiculo, $fecha_hora, $fecha_entrega, $hora_entrega, $observaciones, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento, $iddisponibilidad)
	{
		$sql = "INSERT INTO venta (idcliente,idusuario,tipo_comprobante,planta,tipo_vehiculo,fecha_hora,fecha_entrega,hora_entrega,observaciones,total_venta,estado) VALUES ('$idcliente','$idusuario','$tipo_comprobante','$planta','$tipo_vehiculo','$fecha_hora','$fecha_entrega','$hora_entrega','$observaciones','$total_venta','Aceptado')";
		//return ejecutarConsulta($sql);
		$idventanew = ejecutarConsulta_retornarID($sql);
		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($idarticulo)) {

			$sql_detalle = "INSERT INTO detalle_venta (idventa,idarticulo,cantidad,precio_venta,descuento) VALUES('$idventanew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";

			ejecutarConsulta($sql_detalle) or $sw = false;

			$num_elementos = $num_elementos + 1;
		}

		$num_disponibilidad = 0;
		$ud = true;
		while ($num_disponibilidad < count($iddisponibilidad)) {

			$sql_disponibilidad = "UPDATE disponibilidad d SET d.idventa='$idventanew', d.estado='Pedido generado' WHERE d.iddisponibilidad='$iddisponibilidad[$num_disponibilidad]'";

			ejecutarConsulta($sql_disponibilidad) or $ud = false;

			$num_disponibilidad = $num_disponibilidad + 1;
		}

		if ($sw && $ud) {
			$registro = true;
		} else {
			$registro = false;
		}
		return $registro;
	}

	public function anular($idventa)
	{
		$sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
		return ejecutarConsulta($sql);
	}


	//implementar un metodopara mostrar los datos de unregistro a modificar
	public function mostrar($idventa)
	{
		$sql = "SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.fecha_entrega,v.hora_entrega,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.planta,v.tipo_vehiculo,v.observaciones,v.total_venta,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE idventa='$idventa'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idventa)
	{
		$sql = "SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	//listar registros
	public function listar()
	{
		$sql = "SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.fecha_entrega,v.hora_entrega,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.planta,v.tipo_vehiculo,v.fecha_entrega,v.observaciones,v.total_venta,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC";
		return ejecutarConsulta($sql);
	}


	public function ventacabecera($idventa)
	{
		$sql = "SELECT v.idventa, v.idcliente, p.nombre AS cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, v.planta, v.tipo_vehiculo, DATE(v.fecha_hora) AS fecha, DATE(v.fecha_entrega) AS fecha_entrega, v.hora_entrega,v.observaciones,v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalles($idventa)
	{
		$sql = "SELECT a.nombre AS articulo, a.codigo, d.cantidad, d.precio_venta, d.descuento, (d.cantidad*d.precio_venta-d.descuento) AS subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	public function disponibilidaddetalle($idventa)
	{
		$sql = "SELECT 
					p.ciudad,
					ct.descripcion,
					d.fecha_disponible,
					d.hora_disponible
				FROM disponibilidad d 
					INNER JOIN planta p ON p.idplanta = d.idplanta
					INNER JOIN categoria_vehiculo ct ON ct.idcategoria_vehiculo = d.idcategoria_vehiculo
				WHERE d.idventa ='$idventa'";
		return ejecutarConsulta($sql);
	}
}
