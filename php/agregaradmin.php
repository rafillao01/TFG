<?php
session_start(); // Iniciar la sesión para acceder a las variables de sesión

// Verificar si se ha enviado el formulario para promover a un usuario a administrador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el usuario tiene permisos de administrador
    if (isset($_SESSION['usuario']) && $_SESSION['tipo_usuario'] === 'Administrador') {
        // Verificar si se seleccionó un usuario
        if (isset($_POST["usuario"]) && !empty($_POST["usuario"])) {
            // Obtener el nombre de usuario seleccionado
            $nombreUsuario = $_POST["usuario"];

            // Incluir el archivo de conexión a la base de datos
            include 'conexion.php';

            // Consulta SQL para actualizar el tipo del usuario a "Administrador"
            $sql = "UPDATE usuarios SET Tipo = 'Administrador' WHERE NombreUsuario = '$nombreUsuario'";

            // Ejecutar la consulta
            if ($conexion->query($sql) === TRUE) {
                // Redirigir al usuario a perfil.php
                header("Location: perfil.php");
                exit(); // Asegúrate de detener la ejecución del script después de la redirección
            } else {
                echo "Error al eliminar las sugerencias: " . $conexion->error;
            }

            // Cerrar la conexión
            $conexion->close();
        } else {
            echo "Por favor, seleccione un usuario.";
        }
    } else {
        echo "No tienes permisos para realizar esta acción.";
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
            <h1 class="cabecera" id="Inicio"> SELECCIONA AL NUEVO ADMIN </h1>
            <?php
            // Verificar si el usuario tiene permisos de administrador
            if (isset($_SESSION['usuario']) && $_SESSION['tipo_usuario'] === 'Administrador') {
                // Incluir el archivo de conexión a la base de datos
                include 'conexion.php';

                // Consulta SQL para obtener todos los usuarios de tipo "Usuario"
                $sql = "SELECT NombreUsuario FROM USUARIOS WHERE Tipo = 'Usuario'";
                $result = $conexion->query($sql);

                // Verificar si se encontraron usuarios
                if ($result->num_rows > 0) {
                    echo '<form action="agregaradmin.php" method="post">';
                    echo '<select id="padmin" name="usuario">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["NombreUsuario"] . '">' . $row["NombreUsuario"] . '</option>';
                    }
                    echo '</select>';
                    echo '<br>';
                    echo '<input type="submit" value="Cambiar a Administrador" class="btnadmin">';
                    echo '</form>';
                } else {
                    echo "No hay usuarios disponibles para promover a administrador.";
                }

                // Cerrar la conexión
                $conexion->close();
            } else {
                echo "No tienes permisos para acceder a esta página.";
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
