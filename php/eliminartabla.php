<?php
session_start(); // Iniciar la sesión para acceder a las variables de sesión

if(isset($_POST['nombre_empresa']) && isset($_SESSION['usuario'])) {
    // Obtener el nombre de usuario de la sesión
    $nombreUsuario = $_SESSION['usuario'];

    // Obtener el nombre de la empresa a eliminar
    $nombreEmpresa = $_POST['nombre_empresa'];

    // Incluir el script de conexión
    include 'conexion.php';

    // Consulta SQL para eliminar la tupla de la tabla TABLASUSER
    $sql = "DELETE FROM TABLASUSER WHERE NombreUsuario = '$nombreUsuario' AND NombreEmpresa = '$nombreEmpresa'";
    if ($conexion->query($sql) === TRUE) {
        // Éxito al eliminar la tupla
        echo "La tabla se ha eliminado correctamente";
    } else {
        // Error al ejecutar la consulta SQL
        echo "Error al eliminar la tabla: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Redirigir si no se proporciona el nombre de la empresa
    header("Location: perfil.php");
    exit();
}
?>