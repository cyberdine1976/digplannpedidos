<?php
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
} else {

  if ($_SESSION['ventas'] == 1) {

    //incluimos el archivo factura
    require('Factura.php');

    //establecemos los datos de la empresa
    $logo = "logo.png";
    $ext_logo = "png";
    $empresa = "CYBERDINE";
    $documento = "1074528547";
    $direccion = "Managua";
    $telefono = "958524158";
    $email = "mafv1976@gmail.com";

    //obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/Venta.php";
    $venta = new Venta();
    $rsptav = $venta->ventacabecera($_GET["id"]);

    //recorremos todos los valores que obtengamos
    $regv = $rsptav->fetch_object();

    //configuracion de la factura
    $pdf = new PDF_Invoice('p', 'mm', 'A4');
    $pdf->AddPage();

    //enviamos datos de la empresa al metodo addSociete de la clase factura
    $pdf->addSociete(
      utf8_decode($empresa),
      $documento . "\n" .
        utf8_decode("Direccion: ") . utf8_decode($direccion) . "\n" .
        utf8_decode("Telefono: ") . $telefono . "\n" .
        "Email: " . $email,
      $logo,
      $ext_logo
    );

    $pdf->fact_dev("$regv->tipo_comprobante ", "$regv->planta");
    $pdf->temporaire("");
    $pdf->addDate(date("d-m-Y", strtotime($regv->fecha)));

    //enviamos los datos del cliente al metodo addClientAddresse de la clase factura
    $pdf->addClientAdresse(
      "Nombre: " . utf8_decode($regv->cliente),
      "Domicilio: " . utf8_decode($regv->direccion),
      ucwords(strtolower($regv->tipo_documento)) . ": " . $regv->num_documento,
      "Email: " . $regv->email,
      utf8_decode("Teléfono: ") . $regv->telefono
    );

    //Observacion del cliente
    /* $or1  = $pdf->GetPageWidth() - 200;
    $or2  = $or1 + 115;
    $oy1  = 15;
    $oy2  = $oy1 ;
    $omid = $oy1 + ($oy2 / 2);
    $pdf->RoundedRect($or1, $oy1+55, ($or2 - $or1), $oy2, 3.5, 'D');
    $pdf->Line( $or1, $omid+55, $or2, $omid+55);
    $pdf->SetXY($or1, $oy1+55 );
    $pdf->SetFont( "Arial", "B", 10);
    $pdf->Cell(($or2 - $or1), $oy2-5, "OBSERVACIONES", 0, 0, "C");
    $pdf->SetXY($or1, $oy1+60);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->MultiCell(($or2 - $or1), $oy2-5, '', 0, 0, '', false); */


    $pdf->addObservations($regv->observaciones);
    // Celdas para el detalle de entrega
    $r1 = $pdf->GetPageWidth() - 80;
    $y1 = 42;
    $pdf->SetXY($r1, $y1);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(0, 0, 'DETALLE DE ENTREGA', 0, 0, '', false);
    $pdf->SetXY($r1, $y1 + 5);
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 0, 'Fecha: ' . date("d-m-Y", strtotime($regv->fecha_entrega)), 0, 0, '', false);
    $pdf->SetXY($r1, $y1 + 10);
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 0, 'Hora: ' . $regv->hora_entrega, 0, 0, '', false);
    $pdf->SetXY($r1, $y1 + 15);
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 0, 'Planta: ' . $regv->hora_entrega, 0, 0, '', false);
    $pdf->SetXY($r1, $y1 + 20);
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 0, 'Tipo de vehiculo: ' . $regv->hora_entrega, 0, 0, '', false);

    //establecemos las columnas que va tener lña seccion donde mostramos los detalles de la venta
    $cols = array(
      "CODIGO" => 23,
      "DESCRIPCION" => 78,
      "CANTIDAD" => 22,
      "P.U." => 25,
      "DSCTO" => 20,
      "SUBTOTAL" => 22
    );
    $pdf->addCols($cols);
    $cols = array(
      "CODIGO" => "L",
      "DESCRIPCION" => "L",
      "CANTIDAD" => "C",
      "P.U." => "R",
      "DSCTO" => "R",
      "SUBTOTAL" => "C"
    );
    $pdf->addLineFormat($cols);
    $pdf->addLineFormat($cols);

    //actualizamos el valor de la coordenada "y" quie sera la ubicacion desde donde empecemos a mostrar los datos 
    $y = 85;

    //obtenemos todos los detalles del a venta actual
    $rsptad = $venta->ventadetalles($_GET["id"]);

    while ($regd = $rsptad->fetch_object()) {
      $line = array(
        "CODIGO" => "$regd->codigo",
        "DESCRIPCION" => utf8_decode("$regd->articulo"),
        "CANTIDAD" => "$regd->cantidad",
        "P.U." => "$regd->precio_venta",
        "DSCTO" => "$regd->descuento",
        "SUBTOTAL" => "$regd->subtotal"
      );
      $size = $pdf->addLine($y, $line);
      $y += $size + 2;
    }

    /*aqui falta codigo de letras*/
    require_once "Letras.php";
    $V = new EnLetras();

    $total = $regv->total_venta;
    $V = new EnLetras();
    $V->substituir_un_mil_por_mil = true;

    $con_letra = strtoupper($V->ValorEnLetras($total, "EUROS"));
    $pdf->addCadreTVAs("---" . $con_letra);


    //mostramos el impuesto
    $pdf->addTVAs($regv->total_venta, "  ");
    $pdf->addCadreEurosFrancs("IVA");
    $pdf->Output('Reporte de Venta', 'I');
  } else {
    echo "No tiene permiso para visualizar el reporte";
  }
}

ob_end_flush();
