document.addEventListener("DOMContentLoaded", function() {
    var searchBox = document.getElementById('search1');
    var suggestionsContainer = document.getElementById('suggestions-container');

    // Agregar evento clic al botón
    var ejecutarJarButton = document.getElementById('ejecutarJarButton');
    ejecutarJarButton.addEventListener('click', function() {
        var mensaje = document.getElementById('mensaje1');
        if (mensaje) {
            mensaje.innerText = 'Ejecutando...';
        }

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('El archivo JAR se ha iniciado correctamente.');
                    // Cambiar el mensaje a "Empresa añadida correctamente" después de completar la ejecución del JAR
                    if (mensaje) {
                        mensaje.innerText = 'Empresa añadida correctamente';
                    }
                } else {
                    console.error('Error al intentar iniciar el archivo JAR.');
                    console.error('Código de retorno: ' + xhr.status);
                    console.error('Respuesta del servidor: ' + xhr.responseText);
                }
            }
        };

        // Configurar y enviar solicitud AJAX para ejecutar el archivo PHP que inicia el JAR
        xhr.open('GET', 'EjecutarJar.php');
        xhr.send();
    });

    searchBox.addEventListener('input', function() {
        var searchTerm = searchBox.value;

        // Realizar solicitud AJAX al script PHP para obtener sugerencias
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var empresas = JSON.parse(xhr.responseText);
                    // Mostrar sugerencias
                    showSuggestions(empresas, searchTerm);
                } else {
                    console.error('Hubo un error en la solicitud AJAX.');
                }
            }
        };

        // Configurar y enviar solicitud AJAX para obtener sugerencias
        xhr.open('GET', 'sugerencias_empresas.php?query=' + searchTerm);
        xhr.send();
    });

    suggestionsContainer.addEventListener('click', function(event) {
        var selectedEmpresa = event.target.textContent;
        var selectedUrl = event.target.dataset.url; // Obtener la URL asociada desde el atributo data-url

        // Añadir mensaje "Extrayendo archivos"
        var mensaje = document.getElementById('mensaje');
        if (mensaje) {
            mensaje.innerText = 'Extrayendo archivos...';
            // Cambiar el mensaje después de 180 segundos
            setTimeout(function() {
                mensaje.innerText = 'Archivos extraídos';
            }, 180000); // 180 segundos = 180000 milisegundos
        }

        // Realizar solicitud AJAX para guardar la empresa en el archivo Excel
        saveEmpresa(selectedEmpresa, selectedUrl);
    });

    function saveEmpresa(nombreEmpresa, url) {
        // Realizar solicitud AJAX para ejecutar mi script de Python con la empresa seleccionada
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Empresa correctamente tratada');
                    console.log(xhr.responseText); 
                } else {
                    console.error('Hubo un error');
                    console.log(xhr.responseText); 
                }
            }
        };

        // Configurar y enviar solicitud AJAX 
        xhr.open('GET', 'GuardarExcel.php?url=' + encodeURIComponent(url) + '&empresa=' + encodeURIComponent(nombreEmpresa));
        xhr.send();
    }

    function showSuggestions(empresas, searchTerm) {
        suggestionsContainer.innerHTML = '';
    
        // Filtrar las empresas que comienzan con el término de búsqueda
        var filteredEmpresas = empresas.filter(function(empresa) {
            return empresa.NombreEmpresa.toLowerCase().startsWith(searchTerm.toLowerCase());
        });
    
        console.log('Empresas filtradas:', filteredEmpresas); // Agregar este console.log para depurar
    
        // Mostrar solo hasta 5 sugerencias
        filteredEmpresas.slice(0, 5).forEach(function(empresa) {
            var suggestion = document.createElement('div');
            suggestion.classList.add('suggestion');
            suggestion.textContent = empresa.NombreEmpresa; // Mostrar el nombre de la empresa en la sugerencia
            suggestion.dataset.url = empresa.URLEmpresa; // Guardar la URL asociada en el atributo data-url
    
            suggestion.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#e0e0e0';
            });
    
            suggestion.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '#f4f4f4';
            });
    
            suggestionsContainer.appendChild(suggestion);
        });
    }
});
