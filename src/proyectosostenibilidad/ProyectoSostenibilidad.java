package proyectosostenibilidad;

import java.io.File;

public class ProyectoSostenibilidad {

    public static void main(String[] args) {
        // Llamada al método 
        HTMLParserDocker.main(args);
        // Llamada al método 
        AddCamposDocker.main(args);          
        // Llamada al método 
        HTMLIndexerDocker.main(args);
        // Llamada al método 
        ConectDataBaseDocker.main(args);
    }
}
