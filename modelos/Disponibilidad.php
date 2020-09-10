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
        $idVenta,
        $idProvincia,
        $idPlanta,
        $idCategoriaVehiculo,
        $fechaDisponible,
        $horaDisponible,
        $estado
    ) {
        $sql = "UPDATE disponibilidad SET idprovincia='$idProvincia', idventa='$idVenta',idplanta='$idPlanta',idcategoria_vehiculo='$idCategoriaVehiculo',fecha_disponible='$fechaDisponible',hora_disponible='$horaDisponible',estado='$estado' 
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


    //Consultas para agregar detalle de disponibilidad en el modulo de ventas
    public function listarDisponibilidadEstadoDisponible()
    {
        $sql = "SELECT 
                    d.iddisponibilidad,
                    d.idprovincia,
                    p.nombre_provincia,
                    d.idplanta,
                    c.ciudad,
                    d.idcategoria_vehiculo,
                    cv.descripcion,
                    d.fecha_disponible,
                    d.hora_disponible,
                    d.estado
                FROM disponibilidad d 
                    INNER JOIN provincia p ON p.idprovincia = d.idprovincia
                    INNER JOIN planta c ON c.idprovincia = p.idprovincia
                    INNER JOIN categoria_vehiculo cv ON cv.idcategoria_vehiculo = d.idcategoria_vehiculo
                WHERE d.estado = 'Disponible'
                GROUP BY d.iddisponibilidad";
        return ejecutarConsulta($sql);
    }
}
