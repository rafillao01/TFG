<?php
session_start();
include 'conexion.php'; // Incluir el script de conexión

// Inicializar la variable para almacenar los nombres de las empresas filtradas
$empresas_filtradas = [];

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores seleccionados por el usuario
    $localidad = $_POST['localidad'];
    $sector = $_POST['sector'];
    $empleados_range = $_POST['empleados_range'];

    // Verificar si se seleccionó "Ninguno" en el selector de localidad
    if ($localidad !== "Ninguno") {
        $condicion_localidad = "Localidad = '$localidad'";
    } else {
        $condicion_localidad = "1"; // Condición verdadera, no se aplica filtro de localidad
    }

    // Verificar si se seleccionó "Ninguno" en el selector de sector
    if ($sector !== "Ninguno") {
        $condicion_sector = "Sector = '$sector'";
    } else {
        $condicion_sector = "1"; // Condición verdadera, no se aplica filtro de sector
    }

    // Verificar el rango seleccionado de empleados
    switch ($empleados_range) {
        case "Menos de 50":
            $condicion_empleados = "Empleados < 50";
            break;
        case "Entre 50-100":
            $condicion_empleados = "Empleados BETWEEN 50 AND 100";
            break;
        case "Entre 100-150":
            $condicion_empleados = "Empleados BETWEEN 100 AND 150";
            break;
        case "Entre 150-200":
            $condicion_empleados = "Empleados BETWEEN 150 AND 200";
            break;
        case "Entre 200-250":
            $condicion_empleados = "Empleados BETWEEN 200 AND 250";
            break;
        default:
            $condicion_empleados = "1"; // Condición verdadera, no se aplica filtro de empleados
    }

    // Consulta SQL para obtener las empresas filtradas
    $sql_empresas_filtradas = "SELECT * FROM URLEMPRESAS WHERE $condicion_localidad AND $condicion_sector AND $condicion_empleados";
    $result_empresas_filtradas = $conexion->query($sql_empresas_filtradas);

    // Verificar si se encontraron empresas que coinciden con los criterios de filtrado
    if ($result_empresas_filtradas === false) {
        die("Error al ejecutar la consulta: " . $conexion->error);
    }

    if ($result_empresas_filtradas->num_rows > 0) {
        while ($row_empresa = $result_empresas_filtradas->fetch_assoc()) {
            // Almacenar los datos de las empresas en el array
            $empresas_filtradas[] = $row_empresa;
        }
    }
}
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
        <h1 class="cabecera" id="Inicio"> BUSCA CON LOS SIGUIENTES FILTROS </h1>
        <form method="post" id="formfiltr" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="localidad" id="localidad">LOCALIDAD</label>
            <select name="localidad" id="slocalidad">
                <option value="Ninguno">Ninguno</option>
                <?php
                // Consulta SQL para obtener las opciones de localidad
                $sql_localidades = "SELECT DISTINCT Localidad FROM URLEMPRESAS";
                $result_localidades = $conexion->query($sql_localidades);
                if ($result_localidades->num_rows > 0) {
                    while($row = $result_localidades->fetch_assoc()) {
                        echo '<option value="' . $row["Localidad"] . '">' . $row["Localidad"] . '</option>';
                    }
                }
                ?>
            </select>
            <label for="sector" id="sector">SECTOR</label>
            <select name="sector" id="ssector">
                <option value="Ninguno">Ninguno</option>
                <?php
                // Consulta SQL para obtener las opciones de sector
                $sql_sectores = "SELECT DISTINCT Sector FROM URLEMPRESAS";
                $result_sectores = $conexion->query($sql_sectores);
                if ($result_sectores->num_rows > 0) {
                    while($row = $result_sectores->fetch_assoc()) {
                        echo '<option value="' . $row["Sector"] . '">' . $row["Sector"] . '</option>';
                    }
                }
                ?>
            </select>
            <label for="empleados_range" id="empleados_range">NUMERO DE EMPLEADOS</label>
            <select name="empleados_range" id="sempleados_range">
                <option value="Ninguno">Ninguno</option>
                <option value="Menos de 50">Menos de 50</option>
                <option value="Entre 50-100">Entre 50-100</option>
                <option value="Entre 100-150">Entre 100-150</option>
                <option value="Entre 150-200">Entre 150-200</option>
                <option value="Entre 200-250">Entre 200-250</option>
            </select>
            <input type="submit" value="FILTRAR" id="btnfiltr">
        </form>

        <?php
        // Inicializar el array $empresas_por_url
        $empresas_por_url = [];

        // Agrupar las empresas filtradas por URL
        foreach ($empresas_filtradas as $empresa) {
            $empresas_por_url["URL CONTAMINACIÓN Y RECURSOS"][] = $empresa;
            $empresas_por_url["URL AMBIENTAL Y DIVERSIDAD"][] = $empresa;
            $empresas_por_url["URL SOCIALES Y PERSONAL"][] = $empresa;
            $empresas_por_url["URL DERECHOS HUMANOS"][] = $empresa;
            $empresas_por_url["URL CORRUPCIÓN Y SOBORNO"][] = $empresa;
        }

        // Funciones de comparación para ordenar las empresas por score de mayor a menor
        function compararPorScoreContaminacionRecursos($a, $b) {
            return $b['ScoreContaminacionRecursos'] - $a['ScoreContaminacionRecursos'];
        }

        function compararPorScoreAmbientalDiversidad($a, $b) {
            return $b['ScoreAmbientalDiversidad'] - $a['ScoreAmbientalDiversidad'];
        }

        function compararPorScoreSocialesPersonal($a, $b) {
            return $b['ScoreSocialesPersonal'] - $a['ScoreSocialesPersonal'];
        }

        function compararPorScoreDerechosHumanos($a, $b) {
            return $b['ScoreDerechosHumanos'] - $a['ScoreDerechosHumanos'];
        }

        function compararPorScoreCorrupcionSoborno($a, $b) {
            return $b['ScoreCorrupcionSoborno'] - $a['ScoreCorrupcionSoborno'];
        }

        // Mostrar las empresas agrupadas por URL con sus respectivos scores
        foreach ($empresas_por_url as $url => $empresas) {
            // Ordenar las empresas por score de mayor a menor, dependiendo del tipo de URL
            switch ($url) {
                case "URL CONTAMINACIÓN Y RECURSOS":
                    usort($empresas, 'compararPorScoreContaminacionRecursos');
                    break;
                case "URL AMBIENTAL Y DIVERSIDAD":
                    usort($empresas, 'compararPorScoreAmbientalDiversidad');
                    break;
                case "URL SOCIALES Y PERSONAL":
                    usort($empresas, 'compararPorScoreSocialesPersonal');
                    break;
                case "URL DERECHOS HUMANOS":
                    usort($empresas, 'compararPorScoreDerechosHumanos');
                    break;
                case "URL CORRUPCIÓN Y SOBORNO":
                    usort($empresas, 'compararPorScoreCorrupcionSoborno');
                    break;
            }

            // Imprimir el título con formato de párrafo y clase de CSS
            echo '<p class="ranking">' . $url . '</p>';

            // Imprimir la tabla de empresas
            echo '<table class="empresa-table">';
            foreach ($empresas as $empresa) {
                echo '<tr>';
                echo '<td>' . $empresa['NombreEmpresa'] . '</td>';
                // Imprimir el score correspondiente a cada tipo de URL
                switch ($url) {
                    case "URL CONTAMINACIÓN Y RECURSOS":
                        echo '<td class="score-cell"><img src="' . getScoreImageUrl($empresa['ScoreContaminacionRecursos']) . '" alt="Score"></td>';
                        break;
                    case "URL AMBIENTAL Y DIVERSIDAD":
                        echo '<td class="score-cell"><img src="' . getScoreImageUrl($empresa['ScoreAmbientalDiversidad']) . '" alt="Score"></td>';
                        break;
                    case "URL SOCIALES Y PERSONAL":
                        echo '<td class="score-cell"><img src="' . getScoreImageUrl($empresa['ScoreSocialesPersonal']) . '" alt="Score"></td>';
                        break;
                    case "URL DERECHOS HUMANOS":
                        echo '<td class="score-cell"><img src="' . getScoreImageUrl($empresa['ScoreDerechosHumanos']) . '" alt="Score"></td>';
                        break;
                    case "URL CORRUPCIÓN Y SOBORNO":
                        echo '<td class="score-cell"><img src="' . getScoreImageUrl($empresa['ScoreCorrupcionSoborno']) . '" alt="Score"></td>';
                        break;
                }
                echo '</tr>';
            }
            echo '</table>';
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
function getScoreImageUrl($score) {
    if ($score >= 0 && $score <= 0.15) {
        return 'imags/circulomorado.png';
    } else if ($score > 0.15 && $score <= 0.45) {
        return 'imags/circulorojo.png';
    } else if ($score > 0.45 && $score <= 0.65) {
        return 'imags/circuloambar.png';
    } else if ($score > 0.65 && $score <= 0.85) {
        return 'imags/circuloazul.png';
    } else if ($score > 0.85 && $score <= 1) {
        return 'imags/circuloverde.png';
    }
}
?>
