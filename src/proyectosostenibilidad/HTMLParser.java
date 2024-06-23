package proyectosostenibilidad;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.parser.Parser;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import com.opencsv.CSVWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStreamWriter;
import java.io.IOException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class HTMLParser {
    private static int idCounter = 0; // Contador global para el ID

    public static void main(String[] args) {
        // Ruta del directorio que contiene los archivos HTML
        String directoryPath = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/ProyectoSostenibilidad/resultados";
        String outputDirectoryPath = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/TextoPaginas";
        System.out.println("Ruta del directorio de archivos HTML y CSHTML: " + directoryPath);
        parseDirectory(new File(directoryPath), new File(outputDirectoryPath));
    }

    public static void parseDirectory(File directory, File outputDirectory) {
        File[] files = directory.listFiles();
        if (files != null) {
            for (File file : files) {
                if (file.isDirectory()) {
                    // Si es una carpeta, verificamos si ya existe en el directorio de salida
                    File existingFolder = new File(outputDirectory, file.getName());
                    if (existingFolder.exists()) {
                        System.out.println("La carpeta " + file.getName() + " ya existe en el directorio de salida, pasando a la siguiente carpeta.");
                        continue; // Pasar a la siguiente carpeta sin procesar
                    }
                    // Si la carpeta no existe, la creamos y llamamos recursivamente a parseDirectory
                    File newOutputDirectory = new File(outputDirectory, file.getName());
                    newOutputDirectory.mkdirs();
                    parseDirectory(file, newOutputDirectory);
                } else if (file.isFile() && (file.getName().endsWith(".html") || file.getName().contains(".html") || file.getName().endsWith(".cshtml") || file.getName().endsWith(".pdf"))) {
                    try {
                        parseFile(file, outputDirectory);
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }
        }
    }

    private static void parseFile(File file, File outputDirectory) throws IOException {
        // Incrementar el contador de ID
        idCounter++;

        // Parsear el archivo dependiendo de su tipo
        String bodyText;
        if (file.getName().endsWith(".pdf")) {
            bodyText = extractTextFromPDF(file);
        } else {
            bodyText = extractTextFromHTML(file);
        }
        System.out.println("Texto del cuerpo del archivo " + file.getName() + ":" + bodyText);

        // Limpiar caracteres especiales
        bodyText = cleanSpecialCharacters(bodyText);
        // Eliminar comillas dobles
        bodyText = bodyText.replaceAll("\"", "");
        bodyText = bodyText.replaceAll(";", "");
        bodyText = bodyText.replaceAll(",", "");
        bodyText = bodyText.replaceAll("\\|", "");

        // Crear el directorio de salida si no existe
        if (!outputDirectory.exists()) {
            outputDirectory.mkdirs();
        }

        // Guardar el texto en un archivo CSV en la nueva carpeta
        String csvFileName = new File(outputDirectory, file.getName() + ".csv").getAbsolutePath();
        try (CSVWriter writer = new CSVWriter(new OutputStreamWriter(new FileOutputStream(csvFileName), "UTF-8"))) {
            // Escribir la primera línea con el encabezado "ID, contenido"
            writer.writeNext(new String[]{"ID", "contenido"});
            // Escribir el cuerpo del texto en una sola línea
            writer.writeNext(new String[]{String.valueOf(idCounter), bodyText});
        } catch (IOException e) {
            e.printStackTrace();
        }
        System.out.println("El contenido se ha guardado en el archivo: " + csvFileName);
    }

    private static String extractTextFromPDF(File file) throws IOException {
        try (PDDocument document = PDDocument.load(file)) {
            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);
            // Eliminar saltos de línea y espacios adicionales
            text = text.replaceAll("\\s+", " ");
            return text;
        }
    }

    private static String extractTextFromHTML(File file) throws IOException {
        // Detectar la codificación del HTML
        String encoding = detectEncoding(file);

        // Parsear el HTML utilizando la codificación detectada
        Document doc = Jsoup.parse(file, encoding);
        return doc.body().text();
    }

    private static String detectEncoding(File file) throws IOException {
        String content = Parser.unescapeEntities(org.apache.commons.io.FileUtils.readFileToString(file, "UTF-8"), true);
        Pattern charsetPattern = Pattern.compile("charset=([a-zA-Z0-9-]+)");
        Matcher matcher = charsetPattern.matcher(content);
        if (matcher.find()) {
            return matcher.group(1);
        } else {
            return "UTF-8"; // Si no se puede detectar, utilizar UTF-8 por defecto
        }
    }

    private static String cleanSpecialCharacters(String text) {
        text = text.replaceAll("[áÁ]", "a");
        text = text.replaceAll("[éÉ]", "e");
        text = text.replaceAll("[íÍ]", "i");
        text = text.replaceAll("[óÓ]", "o");
        text = text.replaceAll("[úÚ]", "u");
        text = text.replaceAll("[ñÑ]", "n");
        return text;
    }
}
