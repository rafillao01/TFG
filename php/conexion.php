<?php
$servidor = "mysql";
$usuario = "rcordobam";
$contrasena = "rcordobam";  
$basedatos = "ProyectoSostenibilidad";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>