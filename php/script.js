document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('search');
    const suggestionsContainer = document.getElementById('suggestions-container');
    const companyDetailsContainer = document.getElementById('company-details-container');

    searchInput.addEventListener('input', function() {
        const query = this.value;
        getSuggestions(query, suggestionsContainer, companyDetailsContainer);
    });

    function getSuggestions(query, suggestionsContainer, companyDetailsContainer) {
        const xhr = new XMLHttpRequest();
        const url = `obtener_sugerencias.php?query=${query}`;
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const suggestions = response.sugerencias;
                const queryContaminacionRecursos = response.queryContaminacionRecursos;
                const queryAmbientalDiversidad = response.queryAmbientalDiversidad;
                const querySocialesPersonal = response.querySocialesPersonal;
                const queryDerechosHumanos = response.queryDerechosHumanos;
                const queryCorrupcionSoborno = response.queryCorrupcionSoborno;

                suggestionsContainer.innerHTML = '';
                showSuggestions(suggestions, queryContaminacionRecursos, queryAmbientalDiversidad, querySocialesPersonal, queryDerechosHumanos, queryCorrupcionSoborno, suggestionsContainer, companyDetailsContainer);
            }
        };
        xhr.send();
    }

    function showSuggestions(suggestions, queryContaminacionRecursos, queryAmbientalDiversidad, querySocialesPersonal, queryDerechosHumanos, queryCorrupcionSoborno, suggestionsContainer, companyDetailsContainer) {
        for (let i = 0; i < Math.min(suggestions.length, 5); i++) {
            const suggestion = suggestions[i];
            const suggestionElement = createSuggestionElement(suggestion.NombreEmpresa);
            suggestionElement.addEventListener('click', function() {
                showCompanyDetails(suggestion, queryContaminacionRecursos, queryAmbientalDiversidad, querySocialesPersonal, queryDerechosHumanos, queryCorrupcionSoborno, companyDetailsContainer, suggestionsContainer);
            });
            suggestionsContainer.appendChild(suggestionElement);
        }
    }

    function createSuggestionElement(companyName) {
        const suggestionElement = document.createElement('div');
        suggestionElement.classList.add('suggestion');
        suggestionElement.textContent = companyName;
        return suggestionElement;
    }

    function showCompanyDetails(suggestion, queryContaminacionRecursos, queryAmbientalDiversidad, querySocialesPersonal, queryDerechosHumanos, queryCorrupcionSoborno, companyDetailsContainer, suggestionsContainer) {
        const table = document.createElement('table');
        table.classList.add('suggestion-table');

        appendUrlRow(suggestion.URLContaminacionRecursos, suggestion.ScoreContaminacionRecursos, suggestion.ScoreLocalContaminacionRecursos, queryContaminacionRecursos, 'URL Contaminación y Recursos', table);
        appendUrlRow(suggestion.URLAmbientalDiversidad, suggestion.ScoreAmbientalDiversidad, suggestion.ScoreLocalAmbientalDiversidad, queryAmbientalDiversidad, 'URL Ambiental y Diversidad', table);
        appendUrlRow(suggestion.URLSocialesPersonal, suggestion.ScoreSocialesPersonal, suggestion.ScoreLocalSocialesPersonal, querySocialesPersonal, 'URL Sociales y Personal', table);
        appendUrlRow(suggestion.URLDerechosHumanos, suggestion.ScoreDerechosHumanos, suggestion.ScoreLocalDerechosHumanos, queryDerechosHumanos, 'URL Derechos Humanos', table);
        appendUrlRow(suggestion.URLCorrupcionSoborno, suggestion.ScoreCorrupcionSoborno, suggestion.ScoreLocalCorrupcionSoborno, queryCorrupcionSoborno, 'URL Corrupcion y Soborno', table);

        const nameRow = document.createElement('tr');
        const nameCell = document.createElement('td');
        nameCell.setAttribute('colspan', '4');
        nameCell.style.textAlign = 'center';
        nameCell.textContent = suggestion.NombreEmpresa.toUpperCase();
        nameRow.appendChild(nameCell);
        table.insertBefore(nameRow, table.firstChild);

        // Insertar la fila adicional después del nombre de la empresa
        const headerRow = document.createElement('tr');
        headerRow.classList.add('custom-header-row');
        const localHeader = document.createElement('th');
        localHeader.textContent = 'Local';
        localHeader.classList.add('custom-header-row');
        const globalHeader = document.createElement('th');
        globalHeader.textContent = 'Global';
        globalHeader.classList.add('custom-header-row');
        const thematicHeader = document.createElement('th');
        thematicHeader.textContent = 'Temática';
        thematicHeader.classList.add('custom-header-row');
        const urlsHeader = document.createElement('th');
        urlsHeader.textContent = 'URLs';
        urlsHeader.classList.add('custom-header-row');
        headerRow.appendChild(localHeader);
        headerRow.appendChild(globalHeader);
        headerRow.appendChild(thematicHeader);
        headerRow.appendChild(urlsHeader);
        table.insertBefore(headerRow, table.childNodes[1]);

        // Agregar tabla al companyDetailsContainer
        companyDetailsContainer.innerHTML = '';
        companyDetailsContainer.appendChild(table);
        suggestionsContainer.innerHTML = '';

        // Crear contenedor de botones
        const buttonsContainer = document.createElement('div');
        buttonsContainer.classList.add('buttons-container');

        // Crear botón 'VER CONSULTAS'
        if (userType === "Ajeno" || userType === "Usuario" || userType === "Administrador") {
            const buttonVerConsultas = document.createElement('button');
            buttonVerConsultas.textContent = 'VER CONSULTAS';
            buttonVerConsultas.addEventListener('click', function() {
                // Obtener el nombre de la empresa
                const nombreEmpresa = suggestion.NombreEmpresa;

                // Redirigir a consultas.php con el nombre de la empresa como parámetro
                window.location.href = `consultas.php?nombre_empresa=${nombreEmpresa}`
            });
            buttonsContainer.appendChild(buttonVerConsultas);
        }

        // Crear botón 'MODIFICAR CONSULTAS'
        if (userType === "Administrador") {
            const buttonModificarConsultas = document.createElement('button');
            buttonModificarConsultas.textContent = 'MODIFICAR CONSULTAS';
            buttonModificarConsultas.addEventListener('click', function() {
                // Obtener el nombre de la empresa
                const nombreEmpresa = suggestion.NombreEmpresa; 

                // Redirigir a modifacons.php con el nombre de la empresa como parámetro
                window.location.href = `modificacons.php?nombre_empresa=${nombreEmpresa}`;
            });
            buttonsContainer.appendChild(buttonModificarConsultas);
        }

        // Crear botón 'MANDAR SUGERENCIAS'
        if (userType === "Usuario") {
            const buttonMandarSugerencias = document.createElement('button');
            buttonMandarSugerencias.textContent = 'MANDAR SUGERENCIAS';
            buttonMandarSugerencias.addEventListener('click', function() {
                // Redirigir a sugerencias.php
                window.location.href = 'sugerencias.php';
            });
            buttonsContainer.appendChild(buttonMandarSugerencias);
        }

        // Crear botón 'GUARDAR'
        if (userType === "Usuario" || userType === "Administrador") {
            const buttonGuardar = document.createElement('button');
            buttonGuardar.textContent = 'GUARDAR';
            buttonGuardar.addEventListener('click', function() {
                // Obtener el nombre de la empresa
                const nombreEmpresa = suggestion.NombreEmpresa; 

                // Redirigir a guardar.php con el nombre de la empresa como parámetro
                window.location.href = `guardar.php?nombre_empresa=${nombreEmpresa}`;
            });
            buttonsContainer.appendChild(buttonGuardar);

            // Agregar contenedor de botones al companyDetailsContainer
            companyDetailsContainer.appendChild(buttonsContainer);
        }
        

    }

    function appendUrlRow(url, score, scoreLocal, query, header, table) {
        const urlRow = document.createElement('tr');
    
        // Primera columna: Score Local
        const urlScoreLocalCell = document.createElement('td');
        urlScoreLocalCell.classList.add('score-cell');
        const urlScoreLocalImage = document.createElement('img');
        urlScoreLocalImage.src = getScoreImageUrl(scoreLocal);
        urlScoreLocalImage.alt = 'Score Local';
        urlScoreLocalCell.appendChild(urlScoreLocalImage);
        urlRow.appendChild(urlScoreLocalCell);
    
        // Segunda columna: Score
        const urlScoreCell = document.createElement('td');
        urlScoreCell.classList.add('score-cell');
        const urlScoreImage = document.createElement('img');
        urlScoreImage.src = getScoreImageUrl(score);
        urlScoreImage.alt = 'Score';
        urlScoreCell.appendChild(urlScoreImage);
        urlRow.appendChild(urlScoreCell);
    
        // Tercera columna: Enlace
        const urlHeaderCell = document.createElement('td');
        urlHeaderCell.textContent = header;
        urlRow.appendChild(urlHeaderCell);
    
        const urlCell = document.createElement('td');
        const urlLink = document.createElement('a');
        urlLink.href = getUrlWithQuery(url, query);
        urlLink.innerHTML = highlightQueryTerms(url, query);
        urlLink.target = '_blank';
        urlCell.appendChild(urlLink);
        urlRow.appendChild(urlCell);
    
        table.appendChild(urlRow);
    }

    function getScoreImageUrl(score) {
        if (score >= 0 && score <= 0.15) {
            return 'imags/circulomorado.png';
        } else if (score > 0.15 && score <= 0.45) {
            return 'imags/circulorojo.png';
        } else if (score > 0.45 && score <= 0.65) {
            return 'imags/circuloambar.png';
        } else if (score > 0.65 && score <= 0.85) {
            return 'imags/circuloazul.png';
        } else if (score > 0.85 && score <= 1) {
            return 'imags/circuloverde.png';
        }
    }

    function getUrlWithQuery(url, query) {
        if (url.endsWith('.pdf')) {
            return url;
        } else {
            const terms = query.split(" ").join("+");
            return url + '?query=' + terms;
        }
    }

    function highlightQueryTerms(url, query) {
        if (url.endsWith('.pdf')) {
            return url;
        } else {
            return url.replace(new RegExp(query, 'gi'), '<span class="highlighted">$&</span>');
        }
    }
});
