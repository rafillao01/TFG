package proyectosostenibilidad;

import java.util.List;

public class MuestraResultados {
    
    public static void main(String[] args) {
        // Ruta del archivo CSV con los párrafos
        String csvFilePath = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/Terminos/terminos.txt";

        // Llama al método leerParrafosDesdeTXT para obtener los párrafos
        List<String> parrafos = ParrafosReader.leerParrafosDesdeTXT(csvFilePath);
        System.out.println("Párrafos:");

        int contador = 0; // Inicializamos el contador fuera del bucle principal

        for (String parrafo : parrafos) {
            System.out.println(parrafo);

            // Realizar búsqueda con el párrafo obtenido
            List<ResultadoBusqueda> resultados = HTMLBusqueda.buscarDocumentoSimilar(parrafo);
            System.out.println("\nResultados de la búsqueda:");

            for (ResultadoBusqueda resultado : resultados) {
                String[] campos = resultado.getFieldValues().split("\\|");
                String url = campos[campos.length - 1].trim(); // Obtener la URL que está en el último campo
                System.out.println(url); // Imprimimos la URL solo si es diferente a la anterior

                System.out.println("ID: " + resultado.getId());
                System.out.println("Score: " + resultado.getScore());

                System.out.println("Field Values:");
                StringBuilder fieldValueBuilder = new StringBuilder();
                for (String campo : campos) {
                    fieldValueBuilder.append("\n").append(campo.trim());
                }
                // Imprimir el resultado de los campos de "Field Values" en una sola línea
                System.out.println(fieldValueBuilder.toString().trim());
                System.out.println("---------------------------------------");
                contador++; // Incrementamos el contador cada vez que se imprime un resultado

                if (contador % 1 == 0 && contador != 0) { // Verificamos si el contador es múltiplo de 5
                    System.out.println("\n=======================================================\n");
                }
            }
        }
        // Llamada al método para normalizar los scores por filas
        HTMLBusqueda.normalizarScoresPorFilas();
        // Llamada al método para imprimir los scores normalizados
        HTMLBusqueda.imprimirMapaScoresNormalizado(); 
        
        // Llamada al método para normalizar los scores por filas
        HTMLBusqueda.normalizarScoresPorColumnas();
        // Llamada al método para imprimir los scores normalizados
        HTMLBusqueda.imprimirMapaScoresColumnas(); 
    }
}
