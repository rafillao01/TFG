package proyectosostenibilidad;

import org.apache.lucene.analysis.es.SpanishAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.index.DirectoryReader;
import org.apache.lucene.queryparser.classic.QueryParser;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.TopDocs;
import org.apache.lucene.search.ScoreDoc;
import org.apache.lucene.search.Query;
import org.apache.lucene.search.similarities.ClassicSimilarity;
import org.apache.lucene.store.FSDirectory;
import org.apache.lucene.analysis.TokenStream;
import org.apache.lucene.analysis.tokenattributes.CharTermAttribute;
import org.apache.lucene.queryparser.classic.ParseException;
import org.tartarus.snowball.ext.SpanishStemmer;

import java.nio.file.Files;
import java.io.StringReader;
import java.io.IOException;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.HashMap;
import org.apache.lucene.search.Explanation;
import org.apache.lucene.search.similarities.BM25Similarity;



public class HTMLBusqueda {

    private static final String INDEX_PARENT_PATH = "/var/TFG/Indice";
    private static int[] consul = {0}; 
    private static int[] empresa = {0};
    private static int numeroEmpresas = 0;
    private static int numeroConsultas = 0;
    private static String explicacionDocumento;
    private static boolean numeroEmpresasActualizado = false;
    private static Map<String, String> mapaURLS = new HashMap<>();
    private static Map<String, String> mapaValores = new HashMap<>();
    private static Map<String, String> mapaExplicaciones = new HashMap<>();
    private static Map<String, Float> mapaScores = new HashMap<>();
    private static Map<String, Float> mapaScoresLocal = new HashMap<>();
    private static Map<String, Float> mapaScoresGlobal = new HashMap<>();
    private static Map<String, Integer> mapaLongitudesConsultas = new HashMap<>(); 
    
    public static List<ResultadoBusqueda> buscarDocumentoSimilar(String parrafo) {
        List<ResultadoBusqueda> resultados = new ArrayList<>();
        
        String parrafoPreprocesado = preprocesarParrafo(parrafo);
        System.out.println("Párrafo preprocesado: " + parrafoPreprocesado);
        
        try {
            // Obtener la lista de todos los directorios dentro de INDEX_PARENT_PATH
            Files.list(Paths.get(INDEX_PARENT_PATH))
                 .filter(Files::isDirectory)
                 .forEach(indexDirectory -> {
                     
                     // Por cada directorio, abrir el lector de índices
                     try (DirectoryReader reader = DirectoryReader.open(FSDirectory.open(indexDirectory))) {
                         IndexSearcher searcher = new IndexSearcher(reader);
                         BM25Similarity BM25Similaridad = new BM25Similarity(0.8f,0.8f);
                         searcher.setSimilarity(BM25Similaridad);

                         // Preprocesar el párrafo y construir la consulta   
                         String consulta = preprocesarParrafo(parrafo);
                         Query query = construirQuery(consulta);

                         // Agregar impresión de la consulta generada
                         System.out.println("Consulta generada: " + query.toString());

                         if (query != null) {
                             // Buscar documentos similares
                             TopDocs topDocs = searcher.search(query, 1); // Limitamos a los 5 mejores resultados

                             Document docConMayorScore = null;
                             float mayorScore = Float.MIN_VALUE;
                             

                             for (ScoreDoc scoreDoc : topDocs.scoreDocs) {
                                 Document doc = searcher.doc(scoreDoc.doc);
                                 float score = scoreDoc.score;
                                 String fieldValues = obtenerFieldValues(doc);
                                 String id = doc.get("id"); // Tratar el ID como una cadena
                                 resultados.add(new ResultadoBusqueda(id, score, fieldValues));
                                 
                                 Explanation explanation = searcher.explain(query, scoreDoc.doc);
                                 // imprimirExplicacionCompleta(explanation);
                                 List<TerminoRelevante> terminosRelevantes = extraerTerminosRelevantes(explanation, score, parrafo);
                                 StringBuilder sb = new StringBuilder();
                                 sb.append("Explicación para el documento ID \"").append(id).append("\": ");
                                 for (TerminoRelevante termino : terminosRelevantes) {
                                     sb.append(termino).append(", ");
                                 }
                                 explicacionDocumento = sb.toString();
                                 System.out.println(explicacionDocumento);

                                 // Seguimiento del documento con el mayor score
                                 if (score > mayorScore) {
                                     mayorScore = score;
                                     docConMayorScore = doc;
                                 }
                             }

                             // Imprimir el tercer campo solo para el documento con mayor score
                             if (docConMayorScore != null) {
                                 String fieldValues = obtenerFieldValues(docConMayorScore);
                                 String[] campos = fieldValues.split("\\|");
                                 if (campos.length >= 3) {
                                     String tercerCampo = campos[2].trim();
                                     String cuartoCampo = campos[3].trim();
                                     String quintoCampo = campos[4].trim();
                                     String sextoCampo = campos[5].trim();
                                     
                                     tercerCampo = tercerCampo.replace("\"", "");
                                     tercerCampo = tercerCampo.replace("url:", "");
                                     tercerCampo = tercerCampo.replace(" ", "");
                                     
                                     cuartoCampo = cuartoCampo.replace("\"", "");
                                     cuartoCampo = cuartoCampo.replace("localidad:", "");
                                     
                                     quintoCampo = quintoCampo.replace("\"", "");
                                     quintoCampo = quintoCampo.replace("sector:", "");
                                     
                                     sextoCampo = sextoCampo.replace("\"", "");
                                     sextoCampo = sextoCampo.replace("empleados:", "");
                                     sextoCampo = sextoCampo.replace(" ", "");
                                     
                                     // Guardar la URL en el mapa usando la coordenada como clave
                                     mapaURLS.put(consul[0] + "," + empresa[0], tercerCampo);
                                     mapaExplicaciones.put(consul[0] + "," + empresa[0], explicacionDocumento);
                                     mapaValores.put(0 + "," + empresa[0], cuartoCampo);
                                     mapaValores.put(1 + "," + empresa[0], quintoCampo);
                                     mapaValores.put(2 + "," + empresa[0], sextoCampo);
                                     mapaScores.put(consul[0] + "," + empresa[0], mayorScore);
                                     
                                     int longitudConsulta = contarPalabras(parrafoPreprocesado); 
                                     mapaLongitudesConsultas.put(consul[0] + "," + empresa[0], longitudConsulta);
                                     
                                     System.out.println("Score del documento con mayor score: " + mayorScore);
                                     System.out.println("Url del documento con mayor score: " + tercerCampo + "\n");
                                 } else {
                                     System.out.println("El documento con mayor score no tiene suficientes campos para imprimir el tercero.");
                                 }
                             } else {
                                 System.out.println("No se encontraron resultados para imprimir el tercer campo del documento con mayor score.");
                             }
                             empresa[0]++;
                         }
                     } catch (IOException | ParseException e) {
                         e.printStackTrace();
                     }
                 });
            consul[0]++;
            if (!numeroEmpresasActualizado) { 
                numeroEmpresas += empresa[0]; 
                numeroEmpresasActualizado = true; 
            }
            empresa[0] = 0;
            
        } catch (IOException e) {
            e.printStackTrace();
        }
        
        imprimirMapaURLs();
        imprimirMapaScores();
        imprimirMapaValores();
        
        return resultados;
    }
    
    public static Map<String, String> obtenerMapaURLs() {
        return mapaURLS;
    }
    
    public static Map<String, Float> obtenerMapaScores() {
        return mapaScores;
    }
    
    public static Map<String, Float> obtenerMapaScoresGlobal() {
        return mapaScoresGlobal;
    }
    
    public static Map<String, Float> obtenerMapaScoresLocal() {
        return mapaScoresLocal;
    }
    
    public static Map<String, String> obtenerMapaValores() {
        return mapaValores;
    }
    
    public static Map<String, String> obtenerMapaExplicaciones() {
        return mapaExplicaciones;
    }
    
    public static String preprocesarParrafo(String parrafo) {
        SpanishAnalyzer analyzer = new SpanishAnalyzer();
        TokenStream tokenStream = analyzer.tokenStream("contenido", new StringReader(parrafo));
        CharTermAttribute charTermAttribute = tokenStream.addAttribute(CharTermAttribute.class);

        StringBuilder consultaBuilder = new StringBuilder();
        try {
            tokenStream.reset();
            while (tokenStream.incrementToken()) {
                String token = charTermAttribute.toString();
                if (!token.isEmpty()) {
                    consultaBuilder.append(token).append(" ");
                }
            }
            tokenStream.end();
        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            analyzer.close();
            try {
                tokenStream.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }

        return consultaBuilder.toString().trim();
    }


    private static Query construirQuery(String consulta) throws ParseException {
        QueryParser parser = new QueryParser("contenido", new SpanishAnalyzer());
        return parser.parse(consulta);
    }

    private static String obtenerFieldValues(Document doc) {
        StringBuilder fieldValues = new StringBuilder();

        for (org.apache.lucene.index.IndexableField field : doc.getFields()) {
            fieldValues.append(field.name()).append(": ").append(doc.get(field.name())).append("  |  ");
        }

        return fieldValues.toString();
    }
    
    // Método para imprimir el contenido del HashMap de URLs
    public static void imprimirMapaURLs() {
        System.out.println("Contenido del Mapa de URLs:");
        for (Map.Entry<String, String> entry : mapaURLS.entrySet()) {
            System.out.println("Coordenada: " + entry.getKey() + ", URL: " + entry.getValue());
        }
    }
    
    // Método para imprimir el contenido del HashMap de Scores
    public static void imprimirMapaScores() {
        System.out.println("Contenido del Mapa de Scores:");
        for (Map.Entry<String, Float> entry : mapaScores.entrySet()) {
            System.out.println("Coordenada: " + entry.getKey() + ", Score: " + entry.getValue());
        }
    }
    
    public static void imprimirMapaValores() {
        System.out.println("Contenido del Mapa de Valores:");
        for (Map.Entry<String, String> entry : mapaValores.entrySet()) {
            System.out.println("Coordenada: " + entry.getKey() + ", Valor: " + entry.getValue());
        }
    }
    
    // Método para contar las palabras en un párrafo preprocesado
    public static int contarPalabras(String parrafoPreprocesado) {
        // Dividir el párrafo en palabras utilizando el espacio como delimitador
        String[] palabras = parrafoPreprocesado.split("\\s+");
        // Devolver el número de palabras en el párrafo
        return palabras.length;
    }
    
    public static void normalizarScoresPorColumnas() {
        empresa[0] = numeroEmpresas;
        // Iterar sobre cada columna
        for (int j = 0; j < empresa[0]; j++) {
            // Encontrar el máximo y mínimo en la columna actual
            float maxScore = Float.MIN_VALUE;
            float minScore = Float.MAX_VALUE;
            int maxConsultaLength = Integer.MIN_VALUE;
            int minConsultaLength = Integer.MAX_VALUE;

            // Encontrar el máximo y mínimo en longitud de consulta para la columna actual
            for (int i = 0; i < consul[0]; i++) {
                Integer consultaLength = mapaLongitudesConsultas.get(i + "," + j);
                if (consultaLength != null) {
                    if (consultaLength > maxConsultaLength) {
                        maxConsultaLength = consultaLength;
                    }
                    if (consultaLength < minConsultaLength) {
                        minConsultaLength = consultaLength;
                    }
                }
            }

            // Calcular el máximo y mínimo score ajustado por longitud de consulta
            for (int i = 0; i < consul[0]; i++) {
                Float score = mapaScores.get(i + "," + j);
                Integer consultaLength = mapaLongitudesConsultas.get(i + "," + j);
                if (score != null && consultaLength != null) {
                    float adjustedScore = score / consultaLength;
                    if (adjustedScore > maxScore) {
                        maxScore = adjustedScore;
                    }
                    if (adjustedScore < minScore) {
                        minScore = adjustedScore;
                    }
                }
            }

            // Verificar si maxScore y minScore son iguales para evitar división por cero
            if (maxScore != minScore) {
                // Normalizar los valores en la columna actual
                for (int i = 0; i < consul[0]; i++) {
                    Float score = mapaScores.get(i + "," + j); // Obtener el score en la posición (i, j)
                    Integer consultaLength = mapaLongitudesConsultas.get(i + "," + j); // Obtener la longitud de la consulta en la posición (i, j)
                    if (score != null && consultaLength != null) {
                        // Ponderar el score por la longitud de la consulta
                        float adjustedScore = score / consultaLength;
                        // Normalizar el score ponderado por la longitud de la consulta
                        float normalizedScore = (adjustedScore - minScore) / (maxScore - minScore); // Normalización por mín-max
                        mapaScoresLocal.put(i + "," + j, normalizedScore); // Guardar el score normalizado en el mapaScoresLocal
                        System.out.println("Valor normalizado en la posición (" + i + "," + j + "): " + normalizedScore);
                    }
                }
            } else {
                // Asignar un valor predeterminado a los scores normalizados en esta columna
                for (int i = 0; i < consul[0]; i++) {
                    mapaScoresLocal.put(i + "," + j, 0.0f); // Puedes cambiar 0.0f por otro valor predeterminado si es necesario
                    System.out.println("Valor normalizado en la posición (" + i + "," + j + "): " + 0.0f);
                }
            }
        }
    }
    
    // Método para imprimir el contenido del HashMap de Scores
    public static void imprimirMapaScoresColumnas() {
        System.out.println("Contenido del Mapa de Scores por Columnas:");
        for (Map.Entry<String, Float> entry : mapaScoresLocal.entrySet()) {
            System.out.println("Coordenada: " + entry.getKey() + ", Score: " + entry.getValue());
        }
    }
    
    // Método para normalizar los scores por filas
    public static void normalizarScoresPorFilas() {
        empresa[0] = numeroEmpresas;
        // Iterar sobre cada fila en el mapa de scores
        for (int i = 0; i < consul[0]; i++) {

            // Encontrar el máximo y mínimo en la fila actual
            float maxScore = Float.MIN_VALUE;
            float minScore = Float.MAX_VALUE;
            for (int j = 0; j < empresa[0]; j++) {
                Float score = mapaScores.get(i + "," + j); // Usar Float en lugar de float para permitir valores nulos
                if (score != null) {
                    if (score > maxScore) {
                        maxScore = score;
                    }
                    if (score < minScore) {
                        minScore = score;
                    }
                } else {
                    // Si el valor es nulo, asignar un valor de 0
                    score = 0.0f;
                }
            }

            // Verificar si maxScore y minScore son iguales para evitar división por cero
            if (maxScore != minScore) {
                // Normalizar los valores en la fila actual
                for (int j = 0; j < empresa[0]; j++) {
                    Float score = mapaScores.get(i + "," + j); // Usar Float en lugar de float para permitir valores nulos
                    if (score != null) {
                        float normalizedScore = (score - minScore) / (maxScore - minScore); // Normalización por mín-max
                        mapaScoresGlobal.put(i + "," + j, normalizedScore);
                        System.out.println("Valor normalizado en la fila " + i + ", columna " + j + ": " + normalizedScore);
                    } else {
                        // Si el valor es nulo, asignar un valor de 0
                        mapaScoresGlobal.put(i + "," + j, 0.0f);
                    }
                }
            } else {
                // Asignar un valor predeterminado a los scores normalizados en esta fila
                for (int j = 0; j < empresa[0]; j++) {
                    mapaScoresGlobal.put(i + "," + j, 0.0f); // Puedes cambiar 0.0f por otro valor predeterminado si es necesario
                }
            }
        }
    }
    
    public static void imprimirMapaScoresNormalizado() {
        System.out.println("Contenido del Mapa de Scores Normalizado:");
        for (Map.Entry<String, Float> entry : mapaScoresGlobal.entrySet()) {
            System.out.println("Coordenada: " + entry.getKey() + ", Score Normalizado: " + entry.getValue());
        }
    }
    
    private static List<TerminoRelevante> extraerTerminosRelevantes(Explanation explanation, float totalScore, String parrafoOriginal) {
        List<TerminoRelevante> terminosRelevantes = new ArrayList<>();
        String[] terminosOriginales = parrafoOriginal.split(" "); // Dividir el párrafo original en términos individuales

        for (Explanation detail : explanation.getDetails()) {
            if (detail.getDescription().startsWith("weight(")) {
                String descripcion = detail.getDescription();
                String terminoPreprocesado = descripcion.substring(descripcion.indexOf(':') + 1, descripcion.indexOf(' ')).trim().replace(",", ""); // Eliminar las comas del término preprocesado
                float score = detail.getValue().floatValue();

                // Comparar con los términos originales y guardar el término original si hay una coincidencia
                for (String terminoOriginal : terminosOriginales) {
                    // Eliminar comas del término original
                    terminoOriginal = terminoOriginal.replace(",", "");

                    if (terminoPreprocesado.equals(preprocesarParrafo(terminoOriginal).toLowerCase())) {
                        // Calcular el score como un porcentaje del score total del documento
                        float scorePorcentaje = (score / totalScore) * 100;

                        // Agregar el término original y su score al resultado
                        terminosRelevantes.add(new TerminoRelevante(terminoOriginal, scorePorcentaje));
                        break; // Una vez encontrado el término coincidente, salimos del bucle
                    }
                }
            }
        }
        return terminosRelevantes;
    }
    
    public static void imprimirExplicacionCompleta(Explanation explanation) {
        System.out.println("Explicación completa:");
        imprimirExplicacion(explanation, 0);
    }

    private static void imprimirExplicacion(Explanation explanation, int depth) {
        StringBuilder indent = new StringBuilder();
        for (int i = 0; i < depth; i++) {
            indent.append("\t");
        }

        System.out.println(indent.toString() + explanation.getDescription() + ": " + explanation.getValue());

        Explanation[] details = explanation.getDetails();
        if (details != null) {
            for (Explanation detail : details) {
                imprimirExplicacion(detail, depth + 1);
            }
        }
    }
      
}