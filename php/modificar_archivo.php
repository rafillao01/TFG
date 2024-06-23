<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ruta al archivo de texto que deseas modificar
    $ruta_archivo = '/var/TFG/Terminos/terminos.txt';

    // Leer todo el contenido del archivo
    $contenido = file_get_contents($ruta_archivo);

    // Convertir el contenido en un array de líneas
    $lineas = explode("\n", $contenido);

    // Modificar las líneas según los formularios enviados
    if (isset($_POST['guardar_ContaminacionRecursos']) && !empty($_POST['nueva_consulta_ContaminacionRecursos'])) {
        $lineas[0] = $_POST['nueva_consulta_ContaminacionRecursos'];
    }

    if (isset($_POST['guardar_Ambiental_Diversidad']) && !empty($_POST['nueva_consulta_Ambiental_Diversidad'])) {
        $lineas[1] = $_POST['nueva_consulta_Ambiental_Diversidad'];
    }

    if (isset($_POST['guardar_SocialesPersonal']) && !empty($_POST['nueva_consulta_SocialesPersonal'])) {
        $lineas[2] = $_POST['nueva_consulta_SocialesPersonal'];
    }

    if (isset($_POST['guardar_derechos_humanos']) && !empty($_POST['nueva_consulta_derechos_humanos'])) {
        $lineas[3] = $_POST['nueva_consulta_derechos_humanos'];
    }

    if (isset($_POST['guardar_corrupcion_soborno']) && !empty($_POST['nueva_consulta_corrupcion_soborno'])) {
        $lineas[4] = $_POST['nueva_consulta_corrupcion_soborno'];
    }

    // Unir las líneas nuevamente en una sola cadena
    $nuevo_contenido = implode("\n", $lineas);

    // Escribir el nuevo contenido al archivo
    file_put_contents($ruta_archivo, $nuevo_contenido);

    // Mostrar un mensaje emergente con JavaScript
    echo '<script>alert("¡Cambios guardados correctamente!"); window.location.href = "modificacons.php";</script>';
} else {
    // Si el método de solicitud no es POST, redireccionar a la página principal
    echo '<p class="ranking">Error: La solicitud no se realizó correctamente.</p>';
}
?>
