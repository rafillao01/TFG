<?php
session_start();
include 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombreUsuario = $_SESSION['usuario'];
    $sugerencia = $_POST['sugerencia'];
    $fecha = date("Y-m-d H:i:s"); // Obtener la fecha actual en formato YYYY-MM-DD

    // Preparar la consulta SQL para insertar la sugerencia
    $sql = "INSERT INTO SUGERENCIAS (NombreUsuario, Sugerencia, Fecha) VALUES (?, ?, ?)";

    // Preparar la declaración
    $statement = $conexion->prepare($sql);
    $statement->bind_param("sss", $nombreUsuario, $sugerencia, $fecha);

    // Ejecutar la consulta
    if ($statement->execute()) {
        // Redirigir al usuario a index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Error al enviar la sugerencia: " . $statement->error;
    }

    // Cerrar la statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProyectoSostenibilidad - Mandar Sugerencia</title>
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
            <h1 class="cabecera"> MANDA TU SUGERENCIA </h1>
            <form action="sugerencias.php" method="post" id="sugerenciaForm">
                <label for="sugerencia" id="lsugerencia">SUGERENCIA:</label><br>
                <textarea id="sugerencia" name="sugerencia" rows="4" cols="50" required></textarea><br><br>
                <input type="submit" value="MANDALA AQUÍ" id="BotSug">
            </form>
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