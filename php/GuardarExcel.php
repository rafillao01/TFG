<?php
// Obtener el término de búsqueda del parámetro GET
$searchTerm = $_GET['url'];

// Ruta al archivo newspider.py
$newspiderPath = '/var/TFG/newspider.py';

// Ruta al archivo .jar
$jarFilePath = '/var/TFG/ProyectoSostenibilidad/dist/ProyectoSostenibilidad.jar'; 

// Directorio de resultados
$resultsDir = '/var/TFG/ProyectoSostenibilidad/resultados';

// Verificar si el directorio de resultados existe
if (!file_exists($resultsDir)) {
    // Si no existe, intenta crearlo con permisos de escritura para el usuario del servidor web
    if (!mkdir($resultsDir, 0777, true)) {
        // Si falla la creación, muestra un mensaje de error y termina el script
        die('Error: No se pudo crear el directorio de resultados.');
    }
}

// Construye el comando para ejecutar el script de Python con la URL proporcionada como argumento
$command = "scrapy runspider $newspiderPath -a url=https://$searchTerm";

// Ejecuta el comando y captura la salida
$output = shell_exec($command);

// Responde con un mensaje de éxito o la salida del comando
if ($output === null) {
    echo "Error al ejecutar el comando.";
} else {
    echo "Comando ejecutado correctamente: <br>$output";
}

?>
