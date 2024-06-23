<?php
session_start(); 
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
        <?php if(isset($_SESSION['tipo_usuario'])): ?>
            <script>var userType = '<?php echo $_SESSION['tipo_usuario']; ?>';</script>
        <?php else: ?>
            <script>var userType = 'Ajeno';</script>
        <?php endif; ?>
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
                <h1 class="cabecera" id="Inicio"> Empresas Sostenibles </h1>
                <form>
                    <input type="text" id="search" name="empresa" placeholder="Buscar empresa...">
                    <div id="suggestions-container"></div>
                </form>
                <div id="company-details-container"></div> 
                <details id="detalles">
                    <summary class="cons">Ver detalles</summary>
                    <table class="detalles-table">
                        <tr>
                            <td><img src="imags/circulomorado.png" alt="Circulo Morado"></td>
                            <td>Se trata muy poco o nada</td>
                        </tr>
                        <tr>
                            <td><img src="imags/circulorojo.png" alt="Circulo Rojo"></td>
                            <td>Se trata poco</td>
                        </tr>
                        <tr>
                            <td><img src="imags/circuloambar.png" alt="Circulo Ambar"></td>
                            <td>Se trata algo</td>
                        </tr>
                        <tr>
                            <td><img src="imags/circuloazul.png" alt="Circulo Azul"></td>
                            <td>Se trata bastante</td>
                        </tr>
                        <tr>
                            <td><img src="imags/circuloverde.png" alt="Circulo Verde"></td>
                            <td>Se trata mucho</td>
                        </tr>
                    </table>
                </details>
            </div>
            <div class="container2"> 
                <img src="imags/empresa1.jpg" alt="Imagen 1" id="imag1">
                <h2 id="con2">LA IMPORTANCIA DE LA SOSTENIBILIDAD</h2>
                <p id="p2"> La sostenibilidad en las empresas es fundamental en el panorama empresarial actual, ya que implica la capacidad de satisfacer las necesidades del presente sin comprometer los recursos y oportunidades de las generaciones futuras. Incorporar prácticas sostenibles no solo contribuye al cuidado del medio ambiente, sino que también promueve la eficiencia operativa, reduce costos a largo plazo y fortalece la reputación de la empresa. Esta puede dividirse en distintas temáticas de manera que pueden ser relevantes cada una de ellas a nivel tanto local, es decir, dentro de la propia empresa como a nivel global, comparando a la entidad con el resto de empresas. Además, en un mundo cada vez más consciente de los problemas ambientales, la sostenibilidad se ha convertido en un factor clave para atraer a consumidores, inversores y talento humano comprometido con valores éticos y responsables.</p>
            </div>
            <div class="container3"> 
                <img src="imags/empresa2.jpg" alt="Imagen 1" id="imag2">
                <h2 id="con3">ASPECTOS DE LA SOSTENIBILIDAD</h2>
                <p id="p3"> La sostenibilidad empresarial abarca una amplia gama de aspectos que van más allá del simple cumplimiento de normativas ambientales. Implica una visión holística que considera no solo el impacto medioambiental, sino también aspectos sociales y económicos. Esto puede incluir la adopción de prácticas de gestión responsable de recursos naturales, como la eficiencia energética y la reducción de residuos, así como el fomento de relaciones éticas con proveedores y comunidades locales. Además, implica la promoción de la equidad y diversidad en el lugar de trabajo, el cumplimiento de estándares laborales justos y el compromiso con el desarrollo socioeconómico de las regiones en las que opera la empresa.</p>
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const verDetalles = document.getElementById('ver-detalles');
                const detallesTabla = document.getElementById('detalles-tabla');

                verDetalles.addEventListener('click', function() {
                    if (detallesTabla.classList.contains('hidden')) {
                        detallesTabla.classList.remove('hidden');
                    } else {
                        detallesTabla.classList.add('hidden');
                    }
                });
            });
        </script>
    </body>
</html>