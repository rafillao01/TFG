<?php
session_start();
include 'conexion.php';

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {        
        // Obtener el nombre de usuario del usuario actual
        $nombreUsuario = $_SESSION['usuario'];

        // Eliminar las entradas relacionadas en la tabla "TABLASUSER"
        $sqlDelete = "DELETE FROM TABLASUSER WHERE NombreUsuario = ?";
        $statementDelete = $conexion->prepare($sqlDelete);
        $statementDelete->bind_param("s", $nombreUsuario);
        $statementDelete->execute();
        $statementDelete->close();

        // Eliminar al usuario de la tabla "USUARIOS"
        $sqlDeleteUser = "DELETE FROM USUARIOS WHERE NombreUsuario = ?";
        $statementDeleteUser = $conexion->prepare($sqlDeleteUser);
        $statementDeleteUser->bind_param("s", $nombreUsuario);
        $statementDeleteUser->execute();
        $statementDeleteUser->close();

        // Cerrar la sesión
        session_unset();
        session_destroy();

        // Redirigir al usuario al index
        header("Location: index.php");
        exit();
    } else {
        // Si no se confirma la eliminación, redirigir al perfil
        header("Location: perfil.php");
        exit();
    }
}
?>