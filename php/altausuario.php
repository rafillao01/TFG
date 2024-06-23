<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario de inicio de sesión
    $nombreUsuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT * FROM USUARIOS WHERE NombreUsuario = ?";
    $statement = $conexion->prepare($sql);
    $statement->bind_param("s", $nombreUsuario);
    $statement->execute();
    $resultado = $statement->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contrasena, $usuario['Contrasena'])) {
            // Las credenciales son válidas
            $_SESSION['logged_in'] = true; 
            $_SESSION['usuario'] = $nombreUsuario;

            // Consulta SQL para obtener el tipo de usuario
            $sql_tipo = "SELECT Tipo FROM USUARIOS WHERE NombreUsuario = ?";
            $statement_tipo = $conexion->prepare($sql_tipo);
            $statement_tipo->bind_param("s", $nombreUsuario);
            $statement_tipo->execute();
            $resultado_tipo = $statement_tipo->get_result();

            if ($resultado_tipo->num_rows > 0) {
                $tipo_usuario = $resultado_tipo->fetch_assoc();
                $_SESSION['tipo_usuario'] = $tipo_usuario['Tipo'];
            }

            header("Location: index.php");
            exit();
        } else {
            echo '<script>alert("Nombre de usuario o contraseña incorrectos");</script>';
        }
    } else {
        echo '<script>alert("Nombre de usuario o contraseña incorrectos");</script>';
    }

    // Cerrar la conexión
    $statement->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProyectoSostenibilidad - Iniciar sesión</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta name="Autor" content="Rafael Cordoba Martinez">
    <meta name="Descripcion" content="Pagina Web para TFG">
    <script>
        function validateLoginForm() {
            var usuario = document.getElementById("usuario").value;
            var contrasena = document.getElementById("contrasena").value;

            if (usuario === "" || contrasena === "") {
                alert("Por favor, completa todos los campos.");
                return false;
            }

            return true;
        }
    </script>
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
            <h1 class="cabecera"> Inicia sesión aquí </h1>
            <form action="altausuario.php" method="post" id="loginForm" onsubmit="return validateLoginForm()">
                <label for="usuario" id="liusuario">NOMBRE DE USUARIO:</label>
                <input type="text" id="iusuario" name="usuario" required>
                
                <label for="contrasena" id="licontrasena">CONTRASEÑA:</label>
                <input type="password" id="icontrasena" name="contrasena" required>
                
                <input type="submit" value="INICIAR SESIÓN" id="BotIni">
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
