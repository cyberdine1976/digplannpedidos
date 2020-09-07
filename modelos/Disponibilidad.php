<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Disponibilidad
{
    //implementamos nuestro constructor
    public function __construct()
    {
    }

    //metodo insertar regiustro
    public function insertar(
        $idProvincia,
        $idPlanta,
        $idCategoriaVehiculo,
        $fechaDisponible,
        $horaDisponible,
        $estado
    ) {
        $sql = "INSERT INTO disponibilidad (idprovincia,idplanta,idcategoria_vehiculo,fecha_disponible,hora_disponible,estado) VALUES ('$idProvincia','$idPlanta','$idCategoriaVehiculo','$fechaDisponible','$horaDisponible','$estado')";
        return ejecutarConsulta($sql);
    }

    public function editar(
        $idDisponibilidad,
        $idProvincia,
        $idPlanta,
        $idCategoriaVehiculo,
        $fechaDisponible,
        $horaDisponible,
        $estado
    ) {
        $sql = "UPDATE disponibilidad SET idprovincia='$idProvincia',idplanta='$idPlanta',idcategoria_vehiculo='$idCategoriaVehiculo',fecha_disponible='$fechaDisponible',hora_disponible='$horaDisponible',estado='$estado' 
	            WHERE iddisponibilidad='$idDisponibilidad'";
        return ejecutarConsulta($sql);
    }

    //listar registros
    public function listar()
    {
        $sql = "SELECT d.iddisponibilidad AS ID, d.idventa AS Pedido,pv.nombre_provincia as Provincia ,p.Ciudad,cv.descripcion as Tipo_vehiculo,d.fecha_disponible as Fecha_Disponible,d.hora_disponible as Hora_Disponible,d.Estado FROM disponibilidad d inner JOIN planta p on p.idplanta = d.idplanta INNER JOIN provincia pv on pv.idprovincia = p.idprovincia inner JOIN categoria_vehiculo cv on d.idcategoria_vehiculo = cv.idcategoria_vehiculo";
        return ejecutarConsulta($sql);
    }

    //funcion para eliminar datos
    public function eliminar($idDisponibilidad)
    {
        $sql = "DELETE FROM disponibilidad WHERE iddisponibilidad='$idDisponibilidad'";
        return ejecutarConsulta($sql);
    }

    //metodo para mostrar registros
    public function mostrar($idDisponibilidad)
    {
        $sql = "SELECT * FROM disponibilidad WHERE iddisponibilidad='$idDisponibilidad'";
        return ejecutarConsultaSimpleFila($sql);
    }
}
