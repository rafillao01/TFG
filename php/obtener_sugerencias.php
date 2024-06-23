<?php
// Incluir el archivo de conexión
include 'conexion.php';

$query = $_GET['query'];

// Consulta SQL para obtener las sugerencias de empresas
$sql = "SELECT 
            NombreEmpresa, 
            URLContaminacionRecursos, 
            URLAmbientalDiversidad, 
            URLSocialesPersonal, 
            URLDerechosHumanos, 
            URLCorrupcionSoborno, 
            ScoreContaminacionRecursos, 
            ScoreAmbientalDiversidad, 
            ScoreSocialesPersonal, 
            ScoreDerechosHumanos, 
            ScoreCorrupcionSoborno,
            ScoreLocalContaminacionRecursos, 
            ScoreLocalAmbientalDiversidad, 
            ScoreLocalSocialesPersonal, 
            ScoreLocalDerechosHumanos, 
            ScoreLocalCorrupcionSoborno
        FROM 
            URLEMPRESAS 
        WHERE 
            NombreEmpresa LIKE '$query%'";
            
$resultado = $conexion->query($sql);

$sugerencias = array();
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $sugerencias[] = $fila;
    }
}

// Obtener el valor de QueryContaminacionRecursos de la tabla CONSULTAS
$queryContaminacionRecursos = "";
$queryAmbientalDiversidad = "";
$querySocialesPersonal = "";
$queryDerechosHumanos = "";
$queryCorrupcionSoborno = "";
$sql_consultas = "SELECT QueryContaminacionRecursos, QueryAmbientalDiversidad, QuerySocialesPersonal, QueryDerechosHumanos, QueryCorrupcionSoborno FROM CONSULTAS LIMIT 1";
$result_consultas = $conexion->query($sql_consultas);

if ($result_consultas->num_rows > 0) {
    // Si se encontraron resultados, obtener la fila
    $row = $result_consultas->fetch_assoc();
    $queryContaminacionRecursos = $row["QueryContaminacionRecursos"];
    $queryAmbientalDiversidad = $row["QueryAmbientalDiversidad"];
    $querySocialesPersonal = $row["QuerySocialesPersonal"];
    $queryDerechosHumanos = $row["QueryDerechosHumanos"];
    $queryCorrupcionSoborno = $row["QueryCorrupcionSoborno"];
}

// Devolver las sugerencias, QueryContaminacionRecursos, QueryAmbientalDiversidad, QuerySocialesPersonal, QueryDerechosHumanos y QueryCorrupcionSoborno como JSON
echo json_encode(array(
    "sugerencias" => $sugerencias,
    "queryContaminacionRecursos" => $queryContaminacionRecursos,
    "queryAmbientalDiversidad" => $queryAmbientalDiversidad,
    "querySocialesPersonal" => $querySocialesPersonal,
    "queryDerechosHumanos" => $queryDerechosHumanos,
    "queryCorrupcionSoborno" => $queryCorrupcionSoborno
));
?>
