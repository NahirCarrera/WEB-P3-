<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Reportes</h2>

<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "proyecto_14768";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener las asignaturas
$sql = "SELECT * FROM asignaturas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Asignaturas</h3>";
    echo "<table><tr><th>ID</th><th>Código</th><th>Nombre</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_asignatura"]. "</td><td>" . $row["codigo"]. "</td><td>" . $row["nombre"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron asignaturas.";
}

// Consulta para obtener las aulas disponibles
$sql = "SELECT * FROM aulas_disponibles";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Aulas Disponibles</h3>";
    echo "<table><tr><th>ID</th><th>Aula</th><th>Capacidad</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_aula_disponible"]. "</td><td>" . $row["AULAS_ID_aula"]. "</td><td>" . $row["capacidad"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron aulas disponibles.";
}

$conn->close();
?>

<!-- Botón para generar PDF -->

<button type="button" onclick="window.print()">IMPRIMIR</button>

</form>

</body>
</html>
