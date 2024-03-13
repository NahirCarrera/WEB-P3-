<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['username'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: ../index.html");
    exit();
}
?>
<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['username'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: ../index.html");
    exit();
}
?>
<html lang="en">                                                                                                                                                                                                   
<head>                                                                                                                                                                                                             
    <meta charset="UTF-8">                                                                                                                                                                                         
    <title>Horario</title>                                                                                                                                                                               
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <style type="text/css">                                                                                                                                                                                        
        .page-header h2{                                                                                                                                                                                           
            margin-top: 0;                                                                                                                                                                                         
        }                                                                                                                                                                                                          
        table tr td:last-child a{                                                                                                                                                                                  
            margin-right: 5px;                                                                                                                                                                                     
        }       
    
</style>                                                                                                                                                                                                       
</head>   


                                                                                                                                                                                                     
<?php require_once('navbar.php'); ?>

<iframe src="index.html" width="100%" height = "100%"></iframe>
</html>  