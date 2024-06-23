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
            <h1 class="cabecera" id="Inicio"> ESTAS SON LAS SUGERENCIAS </h1>
            <?php
            // Incluir el archivo de conexión a la base de datos
            include 'conexion.php';

            // Consulta SQL para obtener todas las sugerencias de la tabla SUGERENCIAS
            $sql = "SELECT NombreUsuario, Fecha, Sugerencia FROM SUGERENCIAS";
            $result = $conexion->query($sql);

            // Verificar si la consulta fue exitosa
            if ($result === false) {
                die("Error al ejecutar la consulta: " . $conexion->error);
            }

            // Verificar si se encontraron sugerencias
            if ($result->num_rows > 0) {
                // Iterar sobre los resultados y mostrar cada sugerencia en el formato requerido
                while($row = $result->fetch_assoc()) {
                    $nombreUsuario = $row["NombreUsuario"];
                    $fecha = $row["Fecha"];
                    $sugerencia = $row["Sugerencia"];
                    echo '<div class="cons">';
                    echo "<p>$nombreUsuario sugirió el $fecha lo siguiente: \"$sugerencia\"</p>";
                    echo '</div>';
                }
                // Agregar el botón para eliminar sugerencias
                echo '<form action="eliminarsugerencias.php" method="post">';
                echo '<input type="submit" value="ELIMINAR SUGERENCIAS" class="btncamb">';
                echo '</form>';
            } else {
                echo "<p>No hay sugerencias disponibles.</p>";
            }

            // Cerrar la conexión
            $conexion->close();
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