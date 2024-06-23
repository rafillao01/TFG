<?php
session_start();

// Verificar si el usuario está autenticado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['usuario'])) {
    // Incluir el script de conexión
    include 'conexion.php';

    // Nombre de usuario de la sesión
    $nombreUsuario = $_SESSION['usuario'];

    // Consulta SQL para obtener las empresas asociadas al usuario
    $sql_empresas_usuario = "SELECT NombreEmpresa FROM TABLASUSER WHERE NombreUsuario = '$nombreUsuario'";
    $result_empresas_usuario = $conexion->query($sql_empresas_usuario);

    // Verificar si la consulta fue exitosa
    if ($result_empresas_usuario === false) {
        die("Error al ejecutar la consulta de TABLASUSER: " . mysqli_error($conexion));
    }

    // Verificar si se encontraron empresas asociadas al usuario
    if ($result_empresas_usuario->num_rows > 0) {
        // Array para almacenar las empresas asociadas al usuario
        $empresas_usuario = [];

        while ($row = $result_empresas_usuario->fetch_assoc()) {
            // Almacenar el nombre de la empresa
            $empresas_usuario[] = $row["NombreEmpresa"];
        }

        // Consulta SQL para obtener las empresas asociadas al usuario y sus scores
        $sql_empresas_scores = "SELECT NombreEmpresa, ScoreContaminacionRecursos, ScoreAmbientalDiversidad, ScoreSocialesPersonal, ScoreDerechosHumanos, ScoreCorrupcionSoborno FROM URLEMPRESAS WHERE NombreEmpresa IN ('" . implode("','", $empresas_usuario) . "')";
        $result_empresas_scores = $conexion->query($sql_empresas_scores);

        // Verificar si la consulta fue exitosa
        if ($result_empresas_scores === false) {
            die("Error al ejecutar la consulta de URLEMPRESAS: " . mysqli_error($conexion));
        }

        // Verificar si se encontraron resultados
        if ($result_empresas_scores->num_rows > 0) {
            // Array para almacenar las sumas de scores
            $scores_sumados = [];

            while ($row = $result_empresas_scores->fetch_assoc()) {
                // Calcular la suma de los scores y dividirlos entre 5 para obtener el promedio
                $suma_scores = ($row["ScoreContaminacionRecursos"] + $row["ScoreAmbientalDiversidad"] + $row["ScoreSocialesPersonal"] + $row["ScoreDerechosHumanos"] + $row["ScoreCorrupcionSoborno"]) / 5;

                // Almacenar el nombre de la empresa y la suma de scores en el array
                $scores_sumados[$row["NombreEmpresa"]] = $suma_scores;
            }

            // Ordenar el array por la suma de scores en orden descendente
            arsort($scores_sumados);

            echo '<div class="estadisticas-container">';
            // Imprimir el ranking de empresas en una tabla
            echo '<h2 class="ranking">RANKING DE TUS EMPRESAS</h2>';
            echo '<table class="ranking-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="ranking-header">PUESTO</th>';
            echo '<th class="ranking-header">EMPRESA</th>';
            echo '</tr>';
            echo '</thead>';
            $puesto = 1;
            foreach ($scores_sumados as $empresa => $score) {
                echo '<tr class="ranking-row">';
                echo '<td>' . $puesto . 'º</td><td>' . $empresa . '</td>';
                echo '</tr>';
                $puesto++;
            }
            echo '</table>';
            echo '</div>';

            // Consulta SQL para obtener el top 3 de localidades basado en las empresas del usuario
            $sql_top_localidades = "SELECT Localidad, AVG(ScoreContaminacionRecursos + ScoreAmbientalDiversidad + ScoreSocialesPersonal + ScoreDerechosHumanos + ScoreCorrupcionSoborno) AS avg_score 
                                    FROM URLEMPRESAS 
                                    WHERE NombreEmpresa IN ('" . implode("','", $empresas_usuario) . "')
                                    GROUP BY Localidad 
                                    ORDER BY avg_score DESC 
                                    LIMIT 3";
            $result_top_localidades = $conexion->query($sql_top_localidades);

            if ($result_top_localidades === false) {
                die("Error al ejecutar la consulta de las top localidades: " . mysqli_error($conexion));
            }

            echo '<div class="top-localidades-container">';
            echo '<h2 class="ranking">TOP 3 LOCALIDADES</h2>';
            echo '<table class="ranking-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="ranking-header">Puesto</th>';
            echo '<th class="ranking-header">Localidad</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $puesto = 1;
            while ($row = $result_top_localidades->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $puesto . '</td>';
                echo '<td>' . $row['Localidad'] . '</td>';
                echo '</tr>';
                $puesto++;
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';

            // Consulta SQL para obtener el top 3 de sectores basado en las empresas del usuario
            $sql_top_sectores = "SELECT Sector, AVG(ScoreContaminacionRecursos + ScoreAmbientalDiversidad + ScoreSocialesPersonal + ScoreDerechosHumanos + ScoreCorrupcionSoborno) AS avg_score 
                                 FROM URLEMPRESAS 
                                 WHERE NombreEmpresa IN ('" . implode("','", $empresas_usuario) . "')
                                 GROUP BY Sector 
                                 ORDER BY avg_score DESC 
                                 LIMIT 3";
            $result_top_sectores = $conexion->query($sql_top_sectores);

            if ($result_top_sectores === false) {
                die("Error al ejecutar la consulta de los top sectores: " . mysqli_error($conexion));
            }

            echo '<div class="top-sectores-container">';
            echo '<h2 class="ranking">TOP 3 SECTORES</h2>';
            echo '<table class="ranking-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="ranking-header">Puesto</th>';
            echo '<th class="ranking-header">Sector</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $puesto = 1;
            while ($row = $result_top_sectores->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $puesto . '</td>';
                echo '<td>' . $row['Sector'] . '</td>';
                echo '</tr>';
                $puesto++;
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo "<p>No se encontraron empresas asociadas al usuario.</p>";
        }
    } else {
        echo "<p>No tienes ninguna empresa guardada.</p>";
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo '<h1 class="cabecera" id="Inicio">Perfil no disponible</h1>';
}
?>
