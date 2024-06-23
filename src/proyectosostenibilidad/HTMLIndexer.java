package proyectosostenibilidad;

import org.apache.lucene.analysis.es.SpanishAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.index.IndexWriter;
import org.apache.lucene.index.IndexWriterConfig;
import org.apache.lucene.store.Directory;
import org.apache.lucene.store.FSDirectory;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.nio.file.Paths;

import org.apache.lucene.document.Field;
import org.apache.lucene.document.TextField;
import org.apache.lucene.document.StringField;

public class HTMLIndexer {
    
    private static final String DIRECTORIO_CSV = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/TextoPaginas";
    private static final String RUTA_INDICE = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/Indice";

    public static void main(String[] args) {
        HTMLIndexer indexador = new HTMLIndexer();
        indexador.indexarDirectorios();
    }

    private void indexarDirectorios() {
        try {
            File[] carpetas = new File(DIRECTORIO_CSV).listFiles(File::isDirectory);
            if (carpetas != null) {
                for (File carpeta : carpetas) {
                    indexarCarpeta(carpeta);
                }
            } else {
                System.out.println("No hay carpetas dentro de " + DIRECTORIO_CSV);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void indexarCarpeta(File carpeta) throws IOException {
        String nombreCarpetaIndice = "Indice" + carpeta.getName();
        Directory directorio = FSDirectory.open(Paths.get(RUTA_INDICE, nombreCarpetaIndice));
        SpanishAnalyzer analizador = new SpanishAnalyzer();
        IndexWriterConfig config = new IndexWriterConfig(analizador);
        config.setOpenMode(IndexWriterConfig.OpenMode.CREATE_OR_APPEND);
        IndexWriter escritor = new IndexWriter(directorio, config);

        File[] archivosCSV = carpeta.listFiles((dir, nombre) -> nombre.toLowerCase().endsWith(".csv"));
        if (archivosCSV != null) {
            for (File archivoCSV : archivosCSV) {
                indexarArchivoCSV(escritor, archivoCSV);
            }
        }

        escritor.close();
        System.out.println("La indexación para la carpeta " + carpeta.getName() + " ha finalizado correctamente.");
    }

    private void indexarArchivoCSV(IndexWriter escritor, File archivoCSV) throws IOException {
        try (BufferedReader lector = new BufferedReader(new FileReader(archivoCSV))) {
            String linea;
            int numeroLinea = 0;
            String id = "";
            String palabrasHabladas = "";
            String url = "";
            String localidad = "";
            String sector = "";
            int empleados = 0;
            int indiceId = -1;
            int indicePalabrasHabladas = -1;
            int indiceUrl = -1;
            int indiceLocalidad = -1;
            int indiceSector = -1;
            int indiceEmpleados = -1;

            while ((linea = lector.readLine()) != null) {
                if (numeroLinea == 0) {
                    int[] indices = obtenerIndicesCampos(linea);
                    indiceId = indices[0];
                    indicePalabrasHabladas = indices[1];
                    indiceUrl = indices[2];
                    indiceLocalidad = indices[3];
                    indiceSector = indices[4];
                    indiceEmpleados = indices[5];
                } else if (numeroLinea == 1) {
                    String[] valores = linea.split(",");
                    if (valores.length >= 6) {
                        id = valores[indiceId].trim();
                        palabrasHabladas = valores[indicePalabrasHabladas].trim();
                        url = valores[indiceUrl].trim();
                        localidad = valores[indiceLocalidad].trim();
                        sector = valores[indiceSector].trim();
                        String empleadosString = valores[indiceEmpleados].trim().replaceAll("\"", "");
                        empleados = Integer.parseInt(empleadosString);
                    }
                }
                numeroLinea++;
            }

            Document doc = crearDocumento(id, palabrasHabladas, url, localidad, sector, empleados);
            escritor.addDocument(doc);
        }
    }

    private int[] obtenerIndicesCampos(String lineaCabecera) throws IOException {
        String[] campos = lineaCabecera.split(",(?=([^\"]*\"[^\"]*\")*[^\"]*$)");
        int indiceId = -1;
        int indicePalabrasHabladas = -1;
        int indiceUrl = -1;
        int indiceLocalidad = -1;
        int indiceSector = -1;
        int indiceEmpleados = -1;
        for (int i = 0; i < campos.length; i++) {
            String nombreCampo = campos[i].replaceAll("^\"|\"$", "");
            if (nombreCampo.trim().equalsIgnoreCase("ID")) {
                indiceId = i;
            } else if (nombreCampo.trim().equalsIgnoreCase("contenido")) {
                indicePalabrasHabladas = i;
            } else if (nombreCampo.trim().equalsIgnoreCase("URL")) {
                indiceUrl = i;
            } else if (nombreCampo.trim().equalsIgnoreCase("Localidad")) {
                indiceLocalidad = i;
            } else if (nombreCampo.trim().equalsIgnoreCase("Sector")) {
                indiceSector = i;
            } else if (nombreCampo.trim().equalsIgnoreCase("Empleados")) {
                indiceEmpleados = i;
            }
        }
        if (indiceId == -1 || indicePalabrasHabladas == -1 || indiceUrl == -1 || indiceLocalidad == -1 || indiceSector == -1 || indiceEmpleados == -1) {
            throw new IOException("No se encontraron los campos 'ID', 'contenido', 'URL', 'Localidad', 'Sector' y 'Empleados' en el archivo.");
        }
        return new int[]{indiceId, indicePalabrasHabladas, indiceUrl, indiceLocalidad, indiceSector, indiceEmpleados};
    }

    private Document crearDocumento(String id, String palabrasHabladas, String url, String localidad, String sector, int empleados) {
        Document doc = new Document();
        doc.add(new StringField("id", id, Field.Store.YES));
        doc.add(new TextField("contenido", palabrasHabladas, Field.Store.YES));
        doc.add(new StringField("url", url, Field.Store.YES));
        doc.add(new StringField("localidad", localidad, Field.Store.YES));
        doc.add(new StringField("sector", sector, Field.Store.YES));
        doc.add(new StringField("empleados", String.valueOf(empleados), Field.Store.YES));
        return doc;
    }
}
