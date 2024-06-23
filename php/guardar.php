<?php
session_start();
include 'conexion.php'; 

// Verificar si la sesión está iniciada y obtener el nombre de usuario
if(isset($_SESSION['usuario'])) {
    $nombre_usuario = $_SESSION['usuario'];
} else {
    // Si no hay sesión iniciada, muestro un mensaje emergente pidiendo al usuario que inicie sesión
    echo "<script>alert('Por favor, inicia sesión para guardar la empresa.')</script>";
    echo "<script>window.location.href = 'altausuario.php';</script>"; // Redirigir a la página de inicio de sesión
    exit; // Detener la ejecución del script
}

// Obtener el nombre de la empresa de la URL
if(isset($_GET['nombre_empresa'])) {
    $nombre_empresa = $_GET['nombre_empresa'];
} else {
    echo "Error: Nombre de la empresa no especificado.";
    exit; // Detener la ejecución del script si no se proporciona el nombre de la empresa
}

// Consulta SQL para insertar en TABLASUSER
$sql_insert = "INSERT INTO TABLASUSER (NombreUsuario, NombreEmpresa) VALUES ('$nombre_usuario', '$nombre_empresa')";

// Ejecutar la consulta
if ($conexion->query($sql_insert) === TRUE) {
    // Si la inserción fue exitosa, redirigir al usuario a perfil.php
    header("Location: perfil.php");
    exit; // Detener la ejecución del script
} else {
    echo "Error al guardar el registro: " . $conexion->error;
}

// Cerrar la conexión
$conexion->close();
?>
