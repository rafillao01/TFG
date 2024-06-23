<?php
session_start(); // Iniciar la sesión para acceder a las variables de sesión
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
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['usuario'])) {
            $usuario = strtoupper($_SESSION['usuario']);
            echo '<h1 class="cabecera" id="Inicio">BIENVENIDO ' . $usuario . ' ESTAS SON SUS TABLAS:</h1>';

            // Incluir el script de conexión
            include 'conexion.php';

            // Nombre de usuario de la sesión
            $nombreUsuario = $_SESSION['usuario'];

            // Consulta SQL para obtener el nombre de la empresa
            $sql = "SELECT NombreEmpresa FROM TABLASUSER WHERE NombreUsuario = '$nombreUsuario'";
            $result = $conexion->query($sql);

            // Verificar si la consulta fue exitosa
            if ($result === false) {
                die("Error al ejecutar la consulta: " . mysqli_error($conexion));
            }

            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $nombreEmpresa = $row["NombreEmpresa"];

                    // Consulta SQL para obtener los datos de la tabla URLEMPRESAS asociados al nombre de la empresa
                    $sql_empresas = "SELECT * FROM URLEMPRESAS WHERE NombreEmpresa = '$nombreEmpresa'";
                    $result_empresas = $conexion->query($sql_empresas);

                    // Verificar si la consulta fue exitosa
                    if ($result_empresas === false) {
                        die("Error al ejecutar la consulta de URLEMPRESAS: " . mysqli_error($conexion));
                    }

                    // Verificar si se encontraron resultados
                    if ($result_empresas->num_rows > 0) {
                        // Mostrar la tabla de empresas
                        echo '<table class="suggestion-table">';
                        echo '<tr><td colspan="4" style="text-align: center;">' . strtoupper($row["NombreEmpresa"]) . '</td></tr>';
                        // Añadir la nueva fila aquí
                        echo '<tr><th class="custom-header-row">Local</th><th class="custom-header-row">Global</th><th class="custom-header-row">Temática</th><th class="custom-header-row">URLs</th></tr>';
                        while($row_empresa = $result_empresas->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreLocalContaminacionRecursos"]) . '" alt="Score Local ContaminacionRecursos"></td>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreContaminacionRecursos"]) . '" alt="Score ContaminacionRecursos"></td>';
                            echo '<td>URL Contaminación y Recursos</td>';
                            echo '<td><a href="' . $row_empresa["URLContaminacionRecursos"] . '" target="_blank">' . $row_empresa["URLContaminacionRecursos"] . '</a></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreLocalAmbientalDiversidad"]) . '" alt="Score Local AmbientalDiversidad"></td>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreAmbientalDiversidad"]) . '" alt="Score AmbientalDiversidad"></td>';
                            echo '<td>URL AmbientalDiversidad</td>';
                            echo '<td><a href="' . $row_empresa["URLAmbientalDiversidad"] . '" target="_blank">' . $row_empresa["URLAmbientalDiversidad"] . '</a></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreLocalSocialesPersonal"]) . '" alt="Score Local SocialesPersonal"></td>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreSocialesPersonal"]) . '" alt="Score SocialesPersonal"></td>';
                            echo '<td>URL SocialesPersonal</td>';
                            echo '<td><a href="' . $row_empresa["URLSocialesPersonal"] . '" target="_blank">' . $row_empresa["URLSocialesPersonal"] . '</a></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreLocalDerechosHumanos"]) . '" alt="Score Local Derechos Humanos"></td>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreDerechosHumanos"]) . '" alt="Score Derechos Humanos"></td>';
                            echo '<td>URL Derechos Humanos</td>';
                            echo '<td><a href="' . $row_empresa["URLDerechosHumanos"] . '" target="_blank">' . $row_empresa["URLDerechosHumanos"] . '</a></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreLocalCorrupcionSoborno"]) . '" alt="Score Local CorrupcionSoborno"></td>';
                            echo '<td class="score-cell"><img src="' . getScoreImageUrl($row_empresa["ScoreCorrupcionSoborno"]) . '" alt="Score CorrupcionSoborno"></td>';
                            echo '<td>URL CorrupcionSoborno</td>';
                            echo '<td><a href="' . $row_empresa["URLCorrupcionSoborno"] . '" target="_blank">' . $row_empresa["URLCorrupcionSoborno"] . '</a></td>';
                            echo '</tr>';
                        }
                        echo '</table>';

                        // Agregar el botón "Eliminar tabla"
                        echo '<button class="eliminar-tabla-btn" data-nombre-empresa="' . $nombreEmpresa . '">ELIMINAR TABLA</button>';
                    } else {
                        echo "<p>No se encontraron resultados para la empresa: $nombreEmpresa.</p>";
                    }
                }
            } else {
                echo '<p id="notienes">NO TIENES NINGUNA TABLA GUARDADA</p>';
            }

            // Cerrar la conexión
            $conexion->close();
        } else {
            echo '<h1 class="cabecera" id="Inicio">Perfil no disponible</h1>';
        }

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
        <div id="ranking-container">
            <button id="generar-estadisticas-btn">GENERAR ESTADISTICAS</button>
        </div>
            <div id="botons-container">
                <a href="modificausuario.php"><button class="datos-btn">MODIFICAR DATOS</button></a>
                <a href="bajausuario.php?confirm=yes"><button class="datos-btn">DAR DE BAJA</button></a>
                <?php
                    if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'Administrador') {
                        echo '<a href="versugerencias.php"><button class="datos-btn">VER SUGERENCIAS</button></a>';
                        echo '<a href="agregaradmin.php"><button class="datos-btn">AGREGAR ADMINS</button></a>';
                    }
                ?>
            </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darBajaBtn = document.getElementById("dar-baja-btn");
            if (darBajaBtn) {
                darBajaBtn.addEventListener("click", function() {
                    var confirmar = confirm("¿Estás seguro de que quieres eliminar tu perfil?");
                    if (confirmar) {
                        window.location.href = "bajausuario.php?confirm=yes";
                    } else {
                        window.location.href = "perfil.php";
                    }
                });
            }

            document.querySelectorAll('.eliminar-tabla-btn').forEach(btn => {
                btn.classList.add('delete-button'); 
                btn.addEventListener('click', () => {
                    const nombreEmpresa = btn.dataset.nombreEmpresa;
                    if (confirm(`¿Estás seguro de que deseas eliminar la tabla "${nombreEmpresa}"?`)) {
                        // Enviar una solicitud para eliminar la tabla mediante AJAX
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'eliminartabla.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Recargar la página después de eliminar la tabla
                                location.reload();
                            }
                        };
                        xhr.send('nombre_empresa=' + encodeURIComponent(nombreEmpresa));
                    }
                });
            });

            document.getElementById('generar-estadisticas-btn').addEventListener('click', function() {
                // Realizar una solicitud Ajax para obtener el ranking de empresas
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'generar_estadisticas.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('ranking-container').innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            });
        });
    </script>
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