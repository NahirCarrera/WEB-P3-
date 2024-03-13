<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ya está logueado
if (isset($_SESSION['username'])) {
    // Si el usuario ya está logueado, redirigirlo a la página de bienvenida
    header("Location: index1.php");
    exit();
}

// Incluir el archivo que contiene la conexión a la base de datos
include_once('mySqli.php');

// Verificar si se han enviado datos de formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar la base de datos para obtener el hash de la contraseña almacenada
    $db = new Conect_MySqli();
    $sql = "SELECT * FROM usuarios WHERE nombre = '$username'";
    $result = $db->execute($sql);

    // Verificar si se encontraron resultados
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['contraseña'];
        
        // Verificar si la contraseña proporcionada coincide con la almacenada
        if (password_verify($password, $stored_password)) {
            // Credenciales válidas, el usuario está autenticado

            // Iniciar sesión y almacenar información del usuario
            $_SESSION['username'] = $username;

            // Redirigir al usuario a una página de inicio de sesión exitosa
            header("Location: index1.php");
            exit();
        } else {
            // Credenciales inválidas, mostrar un mensaje de error
            echo "Nombre de usuario o contraseña incorrectos.";
        }
    } else {
        // Credenciales inválidas, mostrar un mensaje de error
        echo "Nombre de usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión a la base de datos
    $db->close_db();
}
?>




