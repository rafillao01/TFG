<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $nombreUsuario = $_POST['nombreUsuario'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Establecer el valor predeterminado "Usuario" para el campo "Tipo"
    $tipo = "Usuario";

    // Insertar los datos en la tabla de usuarios
    $sql = "INSERT INTO USUARIOS (Nombre, Apellidos, NombreUsuario, Correo, Telefono, Contrasena, Tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement = $conexion->prepare($sql);
    $statement->bind_param("sssssss", $nombre, $apellidos, $nombreUsuario, $correo, $telefono, $contrasena, $tipo);

    if ($statement->execute()) {
        // Redirigir al usuario a index.php
        header("Location: index.php");
        exit();
    } else {
        echo '<script>alert("Error al registrar usuario");</script>';
    }

    $statement->close();
    $conexion->close();
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
    <script>
        function validateForm() {
            var nombre = document.getElementById("nombre").value;
            var apellidos = document.getElementById("apellidos").value;
            var usuario = document.getElementById("usuario").value;
            var correo = document.getElementById("correo").value;
            var contrasena = document.getElementById("contrasena").value;

            if (nombre === "" || apellidos === "" || usuario === "" || correo === "" || contrasena === "") {
                alert("Todos los campos son obligatorios");
                return false;
            }

            return true;
        }
    </script>
    <script>
        function confirmarRegistro() {
            if (confirm("De acuerdo con la normativa de protección de datos, usted está dando su consentimiento para que sus datos sean utilizados exclusivamente por nuestra empresa con fines internos. ¿Está de acuerdo con esta política de privacidad?")) {
                return true; // Si el usuario acepta, enviar el formulario
            } else {
                return false; // Si el usuario cancela, no enviar el formulario
            }
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
            <h1 class="cabecera" id="Inicio"> Registrate aquí </h1>
            <form action="registro.php" method="post" id="formulario" onsubmit="return validateForm() && confirmarRegistro()">
                <label for="nombre" id="lnombre">NOMBRE:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="apellidos" id="lapellidos">APELLIDOS:</label>
                <input type="text" id="apellidos" name="apellidos" required>
                
                <label for="nombreUsuario" id="lusuario">NOMBRE DE USUARIO:</label>
                <input type="text" id="usuario" name="nombreUsuario" required>
                
                <label for="correo" id="lcorreo">CORREO ELECTRÓNICO:</label>
                <input type="email" id="correo" name="correo" required>
                
                <label for="telefono" id="ltelefono">TELÉFONO:</label>
                <input type="text" id="telefono" name="telefono">
                
                <label for="contrasena" id="lcontrasena">CONTRASEÑA:</label>
                <input type="password" id="contrasena" name="contrasena" required>
                
                <input type="submit" value="REGISTRARSE" id="botRegis">
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