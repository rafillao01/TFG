<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Consulta SQL para eliminar todas las filas de la tabla SUGERENCIAS
$sql = "DELETE FROM SUGERENCIAS";

// Ejecutar la consulta
if ($conexion->query($sql) === TRUE) {
    // Redirigir al usuario a perfil.php
    header("Location: perfil.php");
    exit(); // Asegúrate de detener la ejecución del script después de la redirección
} else {
    echo "Error al eliminar las sugerencias: " . $conexion->error;
}

// Cerrar la conexión
$conexion->close();
?>