<?php
session_start();
require_once "../modelos/Disponibilidad.php";

$disponibilidad = new Disponibilidad();

$iddisponibilidad = isset($_POST["iddisponibilidad"]) ? limpiarCadena($_POST["iddisponibilidad"]) : "";
$idprovincia = isset($_POST["idprovincia"]) ? limpiarCadena($_POST["idprovincia"]) : "";
$idplanta = isset($_POST["idplanta"]) ? limpiarCadena($_POST["idplanta"]) : "";
$idcategoria_vehiculo = isset($_POST["idcategoria_vehiculo"]) ? limpiarCadena($_POST["idcategoria_vehiculo"]) : "";
$fecha_disponible = isset($_POST["fecha_disponible"]) ? limpiarCadena($_POST["fecha_disponible"]) : "";
$hora_disponible = isset($_POST["hora_disponible"]) ? limpiarCadena($_POST["hora_disponible"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";

switch ($_GET["op"]) {

    case 'listarRegistrosDisponibilidad':
        $rspta = $disponibilidad->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrarRegistroDisponibilidad(' . $reg->ID . ')"><i class="fa fa-pencil"></i></button>' . ' ' . '<button class="btn btn-danger btn-xs" onclick="eliminarRegistroDisponibilidad(' . $reg->ID . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->Pedido,
                "2" => $reg->Provincia,
                "3" => $reg->Ciudad,
                "4" => $reg->Tipo_vehiculo,
                "5" => $reg->Fecha_Disponible,
                "6" => $reg->Hora_Disponible,
                "7" => $reg->Estado
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

    case 'eliminar':
        $rspta = $disponibilidad->eliminar($iddisponibilidad);
        echo $rspta ? "Datos eliminados correctamente" : "No se pudo eliminar los datos";
        break;

    case 'mostrar':
        $rspta = $disponibilidad->mostrar($iddisponibilidad);
        echo json_encode($rspta);
        break;

    case 'cargarProvincias':

        require_once "../modelos/Provincia.php";
        $provincia = new Provincia();

        $rspta = $provincia->listarProvincia();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idprovincia . '>' . $reg->nombre_provincia . '</option>';
        }
        break;

    case 'cargarCiudades':

        $idProvincia = $_GET['id'];

        require_once "../modelos/Ciudad.php";
        $ciudad = new Ciudad();

        $rspta = $ciudad->listarCiudad($idProvincia);

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idplanta . '>' . $reg->ciudad . '</option>';
        }
        break;

    case 'cargarTipoDeVehiculos':

        require_once "../modelos/Vehiculo.php";
        $vehiculo = new Vehiculo();

        $rspta = $vehiculo->listarCategoriaVehiculo();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idcategoria_vehiculo . '>' . $reg->descripcion . '</option>';
        }
        break;
}
