<?php 
require_once "../modelos/pedidos.php";
if (strlen(session_id())<1) 
	session_start();

$pedido=new Pedido();

$idpedido=isset($_POST["idpedido"])? limpiarCadena($_POST["idpedido"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$planta=isset($_POST["planta"])? limpiarCadena($_POST["planta"]):"";
$$hora_entrega=isset($_POST["hora_entrega"])? limpiarCadena($_POST["hora_entrega"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$fecha_entrega=isset($_POST["fecha_entrega"])? limpiarCadena($_POST["fecha_entrega"]):"";
$observaciones=isset($_POST["observaciones"])? limpiarCadena($_POST["observaciones"]):"";
$total_pedido=isset($_POST["total_pedido"])? limpiarCadena($_POST["total_pedido"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idpedido)) {
		$rspta=$pedido->insertar($idcliente,$idusuario,$tipo_comprobante,$planta,$hora_entrega,$fecha_hora,$fecha_entrega,$observaciones,$total_pedido,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"]);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
		break;
	

	case 'anular':
		$rspta=$pedido->anular($idpedido);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	
	case 'mostrar':
		$rspta=$pedido->mostrar($idpedido);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idingreso
		$id=$_GET['id'];

		$rspta=$pedido->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Subtotal</th>
       </thead>';
	   while ($reg=$rspta->fetch_object()) {
		echo '<tr class="filas">
		<td></td>
		<td>'.$reg->nombre.'</td>
		<td>'.$reg->cantidad.'</td>
		<td>'.$reg->precio_venta.'</td>
		<td>'.$reg->subtotal.'</td></tr>';
		$total=$total+($reg->precio_venta*$reg->cantidad);
	}
	echo '<tfoot>
	 <th>TOTAL</th>
	 <th></th>
	 <th></th>
	 <th></th>
	 <th></th>
	 <th><h4 id="total">€/. '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
   </tfoot>';
	break;

    case 'listar':
		$rspta=$pedido->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
                 if ($reg->tipo_comprobante=='Ticket') {
                 	$url='../reportes/exTicket.php?id=';
                 }else{
                    $url='../reportes/exFactura.php?id=';
                 }

			$data[]=array(
            "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idpedido.')"><i class="fa fa-eye"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="anular('.$reg->idpedido.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idpedido.')"><i class="fa fa-eye"></i></button>').
            '<a target="_blank" href="'.$url.$reg->idpedido.'"> <button class="btn btn-info btn-xs"><i class="fa fa-file"></i></button></a>',
			"1"=>$reg->fecha,
			"2"=>$reg->fechaentrega,
            "3"=>$reg->cliente,
            "4"=>$reg->usuario,
            "5"=>$reg->tipo_comprobante,
			"6"=>$reg->planta,
			"7"=>$reg->hora_entrega,
            "8"=>$reg->total_pedido,
			"9"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>'
			
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;

		case 'selectCliente':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarc();

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
			break;

			case 'listarArticulos':
			require_once "../modelos/Articulo.php";
			$articulo=new Articulo();

				$rspta=$articulo->listarActivosVenta();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\','.$reg->precio_venta.')"><span class="fa fa-plus"></span></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->categoria,
            "3"=>$reg->codigo,
            "4"=>$reg->stock,
            "5"=>$reg->precio_venta,
            "6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
          
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

				break;
}
 ?>