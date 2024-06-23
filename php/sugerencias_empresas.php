<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Recibir la cadena de búsqueda desde la solicitud AJAX
$query = $_GET['query'];

// Consulta SQL para buscar empresas que coincidan con la cadena de búsqueda por NombreEmpresa
$sql = "SELECT NombreEmpresa, URLEmpresa FROM EMPRESASESPANA WHERE NombreEmpresa LIKE '$query%' LIMIT 5";

// Ejecutar la consulta
$result = $conexion->query($sql);

// Array para almacenar los resultados
$empresas = array();

// Obtener resultados y añadirlos al array
while ($row = $result->fetch_assoc()) {
    $empresas[] = array(
        'NombreEmpresa' => $row['NombreEmpresa'],
        'URLEmpresa' => $row['URLEmpresa']
    );
}

// Devolver resultados en formato JSON
echo json_encode($empresas);

// Cerrar conexión
$conexion->close();
?>