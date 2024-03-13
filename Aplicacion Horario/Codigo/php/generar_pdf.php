<?php
require_once 'tcpdf/tcpdf.php';

// Conexión a la base de datos
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "proyecto_14768";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear nuevo documento PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu nombre');
$pdf->SetTitle('Reporte PDF');
$pdf->SetSubject('Reporte PDF');
$pdf->SetKeywords('TCPDF, PDF, reporte');

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, true);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Establecer auto página breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Establecer estilo de fuente
$pdf->SetFont('dejavusans', '', 10);

// Agregar una página
$pdf->AddPage();

// Consulta para obtener las asignaturas
$sql = "SELECT * FROM asignaturas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $html = "<h3>Asignaturas</h3><table><tr><th>ID</th><th>Código</th><th>Nombre</th></tr>";
    while($row = $result->fetch_assoc()) {
        $html .= "<tr><td>" . $row["ID_asignatura"]. "</td><td>" . $row["codigo"]. "</td><td>" . $row["nombre"]. "</td></tr>";
    }
    $html .= "</table>";
} else {
    $html = "No se encontraron asignaturas.";
}

// Consulta para obtener las aulas disponibles
$sql = "SELECT * FROM aulas_disponibles";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $html .= "<h3>Aulas Disponibles</h3><table><tr><th>ID</th><th>Aula</th><th>Capacidad</th></tr>";
    while($row = $result->fetch_assoc()) {
        $html .= "<tr><td>" . $row["ID_aula_disponible"]. "</td><td>" . $row["AULAS_ID_aula"]. "</td><td>" . $row["capacidad"]. "</td></tr>";
    }
    $html .= "</table>";
} else {
    $html .= "No se encontraron aulas disponibles.";
}

// Escribir HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar conexión
$conn->close();

// Salida PDF
$pdf->Output('reporte.pdf', 'I');
?>
