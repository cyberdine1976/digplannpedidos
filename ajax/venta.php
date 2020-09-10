<?php
require_once "../modelos/Venta.php";
if (strlen(session_id()) < 1)
	session_start();

$venta = new Venta();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION["idusuario"];
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$planta = isset($_POST["planta"]) ? limpiarCadena($_POST["planta"]) : "";
$tipo_vehiculo = isset($_POST["tipo_vehiculo"]) ? limpiarCadena($_POST["tipo_vehiculo"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$fecha_entrega = isset($_POST["fecha_entrega"]) ? limpiarCadena($_POST["fecha_entrega"]) : "";
$hora_entrega = isset($_POST["hora_entrega"]) ? limpiarCadena($_POST["hora_entrega"]) : "";
$observaciones = isset($_POST["observaciones"]) ? limpiarCadena($_POST["observaciones"]) : "";
//$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";





switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (empty($idventa)) {
			$rspta = $venta->insertar($idcliente, $idusuario, $tipo_comprobante, $planta, $tipo_vehiculo, $fecha_hora, $fecha_entrega, $hora_entrega, $observaciones, $total_venta, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_venta"], $_POST["descuento"]);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		} else {
		}
		break;


	case 'anular':
		$rspta = $venta->anular($idventa);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;

	case 'mostrar':
		$rspta = $venta->mostrar($idventa);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idventa
		$id = $_GET['id'];

		$rspta = $venta->listarDetalle($id);
		$total = 0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>Subtotal</th>
       </thead>';
		while ($reg = $rspta->fetch_object()) {
			echo '<tr class="filas">
			<td></td>
			<td>' . $reg->nombre . '</td>
			<td>' . $reg->cantidad . '</td>
			<td>' . $reg->precio_venta . '</td>
			<td>' . $reg->descuento . '</td>
			<td>' . $reg->subtotal . '</td></tr>';
			$total = $total + ($reg->precio_venta * $reg->cantidad - $reg->descuento);
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total"> €.  ' . $total . '</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>';
		break;

	case 'listar':
		$rspta = $venta->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			if ($reg->tipo_comprobante == 'Ticket') {
				$url = '../reportes/exTicket.php?id=';
			} else {
				$url = '../reportes/exFactura.php?id=';
			}

			$data[] = array(
				"0" => (($reg->estado == 'Aceptado') ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="anular(' . $reg->idventa . ')"><i class="fa fa-close"></i></button>' : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>') .
					'<a target="_blank" href="' . $url . $reg->idventa . '"> <button class="btn btn-info btn-xs"><i class="fa fa-file"></i></button></a>',
				"1" => $reg->fecha,
				"2" => $reg->fecha_entrega,
				"3" => $reg->hora_entrega,
				"4" => $reg->cliente,
				"5" => $reg->usuario,
				"6" => $reg->tipo_comprobante,
				"7" => $reg->planta,
				"8" => $reg->tipo_vehiculo,
				"9" => $reg->observaciones,
				"10" => $reg->total_venta,
				"11" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>'
			);
		}
		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);
		break;

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarc();

		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
		}
		break;

	case 'selectProvincia':
		require_once "../modelos/Provincia.php";
		$provincia = new Provincia();

		$rspta = $provincia->listarProvincia();

		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idprovincia . '>' . $reg->nombre_provincia . '</option>';
		}
		break;

	case 'selectCiudad':

		$idProvincia = $_GET['id'];

		require_once "../modelos/Ciudad.php";
		$ciudad = new Ciudad();

		$rspta = $ciudad->listarCiudad($idProvincia);

		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idplanta . '>' . $reg->ciudad . '</option>';
		}
		break;

	case 'selectTipoVehiculo':

		$idPlanta = $_GET['id'];

		require_once "../modelos/Vehiculo.php";
		$vehiculo = new Vehiculo();

		$rspta = $vehiculo->listarVehiculo($idPlanta);

		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idcategoria_vehiculo . '>' . $reg->descripcion . '</option>';
		}
		break;

	case 'getFechaHoraDisponibilidad':

		$idCategoriaVehiculo = $_GET['id'];

		require_once "../modelos/Vehiculo.php";
		$vehiculo = new Vehiculo();

		$rspta = $vehiculo->obtenerFechaHoraDisponible($idCategoriaVehiculo);

		$reg = $rspta->fetch_object();

		$data[] = array(
			"0" => $reg->fecha_disponible,
			"1" => $reg->hora_disponible,
		);

		echo json_encode($data);

		break;

	case 'listarArticulos':
		require_once "../modelos/Articulo.php";
		$articulo = new Articulo();

		$rspta = $articulo->listarActivosVenta();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => '<button class="btn btn-warning" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . $reg->nombre . '\',' . $reg->precio_venta . ')"><span class="fa fa-plus"></span></button>',
				"1" => $reg->nombre,
				"2" => $reg->categoria,
				"3" => $reg->codigo,
				"4" => $reg->stock,
				"5" => $reg->precio_venta,
				"6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px'>"

			);
		}
		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;

	case 'listarDisponibilidadEstadoDisponible':
		require_once "../modelos/Disponibilidad.php";
		$disponibilidad = new Disponibilidad();

		$rspta = $disponibilidad->listarDisponibilidadEstadoDisponible();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				"0" => '<button class="btn btn-warning" onclick="agregarDetalleDisponibilidad("' . $reg->nombre_provincia . '")"><span class="fa fa-plus"></span></button>',
				"1" => $reg->nombre_provincia,
				"2" => $reg->ciudad,
				"3" => $reg->descripcion,
				"4" => $reg->fecha_disponible,
				"5" => $reg->hora_disponible,
				"6" => $reg->estado

			);
		}
		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;
}
