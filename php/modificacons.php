<?php
session_start(); // Iniciar la sesión para acceder a las variables de sesión

// Incluir el archivo de conexión
include 'conexion.php';

// Consulta para obtener todos los párrafos de la tabla parrafos
$sql = "SELECT * FROM parrafos LIMIT 1";
$resultado = $conexion->query($sql);

// Inicializar variables para los párrafos
$parrafoContaminacionRecursos = '';
$parrafoAmbientalDiversidad = '';
$parrafoSocialesPersonal = '';
$parrafoDerechosHumanos = '';
$parrafoCorrupcionSoborno = '';

// Verificar si se obtuvo un resultado
if ($resultado->num_rows > 0) {
    // Obtener el resultado
    $fila = $resultado->fetch_assoc();
    $parrafoContaminacionRecursos = $fila['ParrafoContaminacionRecursos'];
    $parrafoAmbientalDiversidad = $fila['ParrafoAmbientalDiversidad'];
    $parrafoSocialesPersonal = $fila['ParrafoSocialesPersonal'];
    $parrafoDerechosHumanos = $fila['ParrafoDerechosHumanos'];
    $parrafoCorrupcionSoborno = $fila['ParrafoCorrupcionSoborno'];
} else {
    $parrafoContaminacionRecursos = "No se encontró el párrafo de contaminación.";
    $parrafoAmbientalDiversidad = "No se encontró el párrafo de ambiental y diversidad.";
    $parrafoSocialesPersonal = "No se encontró el párrafo de sociales y personal.";
    $parrafoDerechosHumanos = "No se encontró el párrafo de derechos humanos";
    $parrafoCorrupcionSoborno = "No se encontró el párrafo de corrupción y soborno.";
}

// Cerrar la conexión
$conexion->close();
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
            <h1 class="cabecera" id="Inicio"> MODIFICA LAS CONSULTAS </h1>
            <div class="cons">
                <div id="url-ContaminacionRecursos">
                    <h2 class="ranking">URL CONTAMINACION Y RECURSOS</h2>
                    <p class="cons">CONSULTA GENERADA</p>
                    <p class="parr"><?php echo htmlspecialchars($parrafoContaminacionRecursos); ?></p>
                    <p class="cons">MODIFICA LA CONSULTA</p>
                    <form action="modificar_archivo.php" method="post">
                        <input type="text" name="nueva_consulta_ContaminacionRecursos" id="consulta-ContaminacionRecursos" class="lablcamb" placeholder="Escribe aquí tu consulta..."><br>
                        <button type="submit" name="guardar_ContaminacionRecursos" class="btncamb">GUARDAR CAMBIOS</button>
                    </form>
                </div>
                
                <div id="url-Ambiental-Diversidad">
                    <h2 class="ranking">URL AMBIENTAL Y DIVERSIDAD</h2>
                    <p class="cons">CONSULTA GENERADA</p>
                    <p class="parr"><?php echo htmlspecialchars($parrafoAmbientalDiversidad); ?></p>
                    <p class="cons">MODIFICA LA CONSULTA</p>
                    <form action="modificar_archivo.php" method="post">
                        <input type="text" name="nueva_consulta_Ambiental_Diversidad" id="consulta-Ambiental-Diversidad" class="lablcamb" placeholder="Escribe aquí tu consulta..."><br>
                        <button type="submit" name="guardar_Ambiental_Diversidad" class="btncamb">GUARDAR CAMBIOS</button>
                    </form>
                </div>
                
                <div id="url-SocialesPersonal">
                    <h2 class="ranking">URL SOCIALES Y PERSONALES</h2>
                    <p class="cons">CONSULTA GENERADA</p>
                    <p class="parr"><?php echo htmlspecialchars($parrafoSocialesPersonal); ?></p>
                    <p class="cons">MODIFICA LA CONSULTA</p>
                    <form action="modificar_archivo.php" method="post">
                        <input type="text" name="nueva_consulta_SocialesPersonal" id="consulta-SocialesPersonal" class="lablcamb" placeholder="Escribe aquí tu consulta..."><br>
                        <button type="submit" name="guardar_SocialesPersonal" class="btncamb">GUARDAR CAMBIOS</button>
                    </form>
                </div>
                
                <div id="url-derechos-humanos">
                    <h2 class="ranking">URL DERECHOS HUMANOS</h2>
                    <p class="cons">CONSULTA GENERADA</p>
                    <p class="parr"><?php echo htmlspecialchars($parrafoDerechosHumanos); ?></p>
                    <p class="cons">MODIFICA LA CONSULTA</p>
                    <form action="modificar_archivo.php" method="post">
                        <input type="text" name="nueva_consulta_derechos_humanos" id="consulta-derechos-humanos" class="lablcamb" placeholder="Escribe aquí tu consulta..."><br>
                        <button type="submit" name="guardar_derechos_humanos" class="btncamb">GUARDAR CAMBIOS</button>
                    </form>
                </div>
                
                <div id="url-corrupcion-soborno">
                    <h2 class="ranking">URL CORRUPCION Y SOBORNO</h2>
                    <p class="cons">CONSULTA GENERADA</p>
                    <p class="parr"><?php echo htmlspecialchars($parrafoCorrupcionSoborno); ?></p>
                    <p class="cons">MODIFICA LA CONSULTA</p>
                    <form action="modificar_archivo.php" method="post">
                        <input type="text" name="nueva_consulta_corrupcion_soborno" id="consulta-corrupcion-soborno" class="lablcamb" placeholder="Escribe aquí tu consulta..."><br>
                        <button type="submit" name="guardar_corrupcion_soborno" class="btncamb">GUARDAR CAMBIOS</button>
                    </form>
                </div>
            </div>
            <button id="ejecutarJarButton">GENERAR CAMBIOS</button>
            <div id="mensaje1"></div>         
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
    <script src="scriptNuevas.js"></script>
</body>
</html>
