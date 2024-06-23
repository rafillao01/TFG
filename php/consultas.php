<?php
session_start(); 
include 'conexion.php'; 

// Obtener el nombre de la empresa de la URL
$nombreEmpresa = $_GET['nombre_empresa'];

// Consulta para obtener los datos de la tabla CONSULTAS
$sqlConsulta = "SELECT ParrafoContaminacionRecursos, ParrafoAmbientalDiversidad, ParrafoSocialesPersonal, ParrafoDerechosHumanos, ParrafoCorrupcionSoborno FROM PARRAFOS";
$resultadoConsulta = $conexion->query($sqlConsulta);

// Consulta para obtener los datos de la tabla URLEMPRESAS específicos para la empresa seleccionada
$sqlEmpresas = "SELECT ExplicacionContaminacionRecursos, ExplicacionAmbientalDiversidad, ExplicacionSocialesPersonal, ExplicacionDerechosHumanos, ExplicacionCorrupcionSoborno FROM URLEMPRESAS WHERE NombreEmpresa = ?";
$stmtEmpresas = $conexion->prepare($sqlEmpresas);
$stmtEmpresas->bind_param("s", $nombreEmpresa);
$stmtEmpresas->execute();
$resultadoEmpresas = $stmtEmpresas->get_result();

// Función para obtener los términos y sus puntuaciones en forma de array
function getTermsAndScores($explicacion) {
    $terms = explode(',', $explicacion);
    $result = [];
    foreach ($terms as $term) {
        // Extraer el término y la puntuación
        if (preg_match('/termino:(.+) y score=([0-9.]+)/', $term, $matches)) {
            // Limpiar el término: convertir a minúsculas y eliminar caracteres no deseados
            $cleanedTerm = strtolower(preg_replace('/[^\p{L}\p{N} ]/u', '', $matches[1]));
            $score = floatval($matches[2]);
            // Almacenar el término limpio y su puntuación en el array de resultados
            $result[] = [
                'term' => trim($cleanedTerm),
                'score' => $score
            ];
        }
    }
    return $result;
}

// Función para ordenar los términos con mayor puntuación y mostrar solo los primeros 7
function renderTermsTable($terms) {
    usort($terms, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
    $highlighted = array_slice($terms, 0, 7);
    $output = "<table class='ranking-table'><tr><th>Término</th><th>Score</th></tr>";
    foreach ($highlighted as $term) {
        $scoreWithPercent = $term['score'] . "%"; // Agregar el símbolo "%" al score
        $output .= "<tr><td>{$term['term']}</td><td>{$scoreWithPercent}</td></tr>";
    }
    $output .= "</table>";
    $output .= "<p class='cons'>El score simboliza la importancia del término en el documento</p>"; // Añadir el párrafo debajo de la tabla
    return $output;
}

// Verificar si las consultas se ejecutaron correctamente
if ($resultadoConsulta && $resultadoEmpresas) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <title>ProyectoSostenibilidad</title>
            <link rel="stylesheet" type="text/css" href="styles.css">

            <meta name="Autor" content="Rafael Cordoba Martinez">
            <meta name="Descripcion" content="Pagina Web para TFG">
        </head>
        <body>
            <header>
                <nav>
                    <ul>
                        <li id="li1"><a href="index.php #Inicio" class="index-link"> HOME</a></li>
                        <li id="li2"><a href="NuevaEmpresa.php" class="index-link"> AÑADIR EMPRESA</a></li>
                        <li id="li3"><a href="Filtros.php" class="index-link"> BUSQUEDA POR FILTROS</a></li>
                        <?php
                        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                            echo '<li id="li6"><a href="perfil.php" class="index-link">ACCEDA A SU PERFIL</a></li>';
                            echo '<li id="li7"><a href="logout.php" class="index-link">CERRAR SESIÓN</a></li>';
                        } else {
                            echo '<li id="li4"><a href="altausuario.php" class="index-link"> INICIAR SESION</a></li>';
                            echo '<li id="li5"><a href="registro.php" class="index-link"> REGISTRARSE</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </header>
            <main>
                <div class="container">
                    <h1 class="cabecera" id="Inicio"> TERMINOS RELEVANTE POR DOCUMENTO </h1>
                    <?php
                    // Imprimir los datos en el formato requerido
                    while (($rowConsulta = $resultadoConsulta->fetch_assoc()) && ($rowEmpresas = $resultadoEmpresas->fetch_assoc())) {
                        $explicaciones = [
                            'ExplicacionContaminacionRecursos' => $rowEmpresas['ExplicacionContaminacionRecursos'],
                            'ExplicacionAmbientalDiversidad' => $rowEmpresas['ExplicacionAmbientalDiversidad'],
                            'ExplicacionSocialesPersonal' => $rowEmpresas['ExplicacionSocialesPersonal'],
                            'ExplicacionDerechosHumanos' => $rowEmpresas['ExplicacionDerechosHumanos'],
                            'ExplicacionCorrupcionSoborno' => $rowEmpresas['ExplicacionCorrupcionSoborno']
                        ];
                        ?>
                        <div class="cons">
                            <p class="ranking">URL CONTAMINACIÓN Y RECURSOS</p>
                            <p class="cons"><?php echo "CONSULTA REALIZADA"; ?></p>
                            <p class="parr"><?php echo $rowConsulta['ParrafoContaminacionRecursos']; ?></p>
                            <p class="cons"><?php echo "EXPLICACIÓN POR TERMINO ORDENADAS"; ?></p>
                            <?php echo renderTermsTable(getTermsAndScores($explicaciones['ExplicacionContaminacionRecursos'])); ?>

                            <p class="ranking">URL AMBIENTAL Y DIVERSIDAD</p>
                            <p class="cons"><?php echo "CONSULTA REALIZADA"; ?></p>
                            <p class="parr"><?php echo $rowConsulta['ParrafoAmbientalDiversidad']; ?></p>
                            <p class="cons"><?php echo "EXPLICACIÓN POR TERMINO ORDENADAS"; ?></p>
                            <?php echo renderTermsTable(getTermsAndScores($explicaciones['ExplicacionAmbientalDiversidad'])); ?>

                            <p class="ranking">URL SOCIALES Y PERSONAL</p>
                            <p class="cons"><?php echo "CONSULTA REALIZADA"; ?></p>
                            <p class="parr"><?php echo $rowConsulta['ParrafoSocialesPersonal']; ?></p>
                            <p class="cons"><?php echo "EXPLICACIÓN POR TERMINO ORDENADAS"; ?></p>
                            <?php echo renderTermsTable(getTermsAndScores($explicaciones['ExplicacionSocialesPersonal'])); ?>

                            <p class="ranking">URL DERECHOS HUMANOS</p>
                            <p class="cons"><?php echo "CONSULTA REALIZADA"; ?></p>
                            <p class="parr"><?php echo $rowConsulta['ParrafoDerechosHumanos']; ?></p>
                            <p class="cons"><?php echo "EXPLICACIÓN POR TERMINO ORDENADAS"; ?></p>
                            <?php echo renderTermsTable(getTermsAndScores($explicaciones['ExplicacionDerechosHumanos'])); ?>

                            <p class="ranking">URL CORRUPCIÓN Y SOBORNO</p>
                            <p class="cons"><?php echo "CONSULTA REALIZADA"; ?></p>
                            <p class="parr"><?php echo $rowConsulta['ParrafoCorrupcionSoborno']; ?></p>
                            <p class="cons"><?php echo "EXPLICACIÓN POR TERMINO ORDENADAS"; ?></p>
                            <?php echo renderTermsTable(getTermsAndScores($explicaciones['ExplicacionCorrupcionSoborno'])); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </main>
            <footer>
                <section id="pie">
                    <h3 id="loc">LOCALIZACIÓN</h3>
                    <p id="local">Calle Galeno Nº34, Armilla, Granada</p>
                    <h3 id="sobre">SOBRE MI</h3>
                    <p id="in">LinkedIn: Rafael Córdoba Martínez</p>
                    <h3 id="TFG">SOBRE EL TFG</h3>
                    <p id="STFG">Trabajo realizado para la UGR<br>TFG para el análisis de la sostenibilidad de las empresas</p>
                    <div id="final">
                        <p id="pp">Copyright © Your Website 2024</p>
                    </div>
                </section>
            </footer>
            <script src="script.js"></script>
        </body>
    </html>
    <?php
} else {
    echo "Error al obtener los datos de la base de datos.";
}
?>
