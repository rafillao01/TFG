package proyectosostenibilidad;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class AddCamposDocker {

    public static void main(String[] args) {
        File resultadosFolder = new File("/var/TFG/ProyectoSostenibilidad/resultados");

        if (!resultadosFolder.exists() || !resultadosFolder.isDirectory()) {
            System.out.println("La carpeta de resultados no existe o no es una carpeta.");
            return;
        }

        Connection connection = null;

        try {
            // Paso 1: Registrar el driver JDBC
            Class.forName("com.mysql.cj.jdbc.Driver");

            // Paso 2: Establecer la conexión con la base de datos
            String url = "jdbc:mysql://mysql:3306/ProyectoSostenibilidad";
            String usuario = "rcordobam";
            String contraseña = "rcordobam";
            connection = DriverManager.getConnection(url, usuario, contraseña);

            File[] carpetas = resultadosFolder.listFiles();

            if (carpetas != null) {
                for (File carpeta : carpetas) {
                    if (carpeta.isDirectory()) {
                        procesarCarpeta(carpeta, connection);
                    }
                }
            } else {
                System.out.println("No hay carpetas dentro de la carpeta de resultados.");
            }
        } catch (ClassNotFoundException e) {
            System.out.println("No se pudo cargar el driver JDBC");
            e.printStackTrace();
        } catch (SQLException e) {
            System.out.println("Error al establecer la conexión con la base de datos");
            e.printStackTrace();
        } finally {
            // Cerrar la conexión
            if (connection != null) {
                try {
                    connection.close();
                } catch (SQLException e) {
                    System.out.println("Error al cerrar la conexión");
                    e.printStackTrace();
                }
            }
        }
    }

    public static void procesarCarpeta(File carpeta, Connection connection) {
        File textoPaginasFolder = new File("/var/TFG/TextoPaginas");

        File archivoCsv = new File(carpeta, carpeta.getName() + ".csv");

        if (!archivoCsv.exists() || !archivoCsv.isFile()) {
            System.out.println("El archivo no existe en la carpeta " + carpeta.getName());
            return;
        }

        File carpetaTextoPaginas = new File(textoPaginasFolder, carpeta.getName());

        if (!carpetaTextoPaginas.exists() || !carpetaTextoPaginas.isDirectory()) {
            System.out.println("La carpeta " + carpetaTextoPaginas.getName() + " no existe en TextoPaginas.");
            return;
        }

        try (BufferedReader br = new BufferedReader(new FileReader(archivoCsv))) {
            String linea;
            while ((linea = br.readLine()) != null) {
                String[] partes = linea.split(": ");
                if (partes.length == 2) {
                    String nombreArchivo = partes[0];
                    String url = partes[1];

                    // Obtener el nombre base del archivo
                    String nombreBaseArchivo = obtenerNombreBase(nombreArchivo);

                    // Buscar el archivo correspondiente en la carpeta de texto de páginas
                    File archivoHtmlCsv = buscarArchivoCsv(carpetaTextoPaginas, nombreBaseArchivo);

                    if (archivoHtmlCsv == null) {
                        System.out.println("No se encontró el archivo correspondiente para " + nombreBaseArchivo);
                        continue;
                    }

                    // Obtener datos adicionales de la base de datos para esta empresa
                    String nombreEmpresa = carpeta.getName();
                    String localidad = "";
                    String sector = "";
                    int empleados = 0;

                    // Consulta SQL para obtener los datos de la empresa
                    String sql = "SELECT Localidad, Sector, Empleados FROM EMPRESASESPANA WHERE NombreEmpresa = ?";
                    try (PreparedStatement statement = connection.prepareStatement(sql)) {
                        statement.setString(1, nombreEmpresa);
                        ResultSet resultSet = statement.executeQuery();
                        if (resultSet.next()) {
                            localidad = resultSet.getString("Localidad");
                            sector = resultSet.getString("Sector");
                            empleados = resultSet.getInt("Empleados");
                        }
                    } catch (SQLException e) {
                        System.out.println("Error al ejecutar la consulta SQL para la empresa " + nombreEmpresa);
                        e.printStackTrace();
                    }

                    // Guardar el texto de la primera línea antes de modificar el archivo
                    String primeraLineaOriginal = "";
                    try (BufferedReader br2 = new BufferedReader(new FileReader(archivoHtmlCsv))) {
                        primeraLineaOriginal = br2.readLine();
                    } catch (IOException e) {
                        System.out.println("Error al leer la primera línea del archivo " + archivoHtmlCsv.getName());
                        e.printStackTrace();
                    }

                    // Guardar el texto de la segunda línea antes de modificar el archivo
                    String segundaLineaOriginal = "";
                    try (BufferedReader br2 = new BufferedReader(new FileReader(archivoHtmlCsv))) {
                        br2.readLine(); // Saltar la primera línea
                        segundaLineaOriginal = br2.readLine();
                    } catch (IOException e) {
                        System.out.println("Error al leer la segunda línea del archivo " + archivoHtmlCsv.getName());
                        e.printStackTrace();
                    }

                    // Escribir en el archivo html.csv
                    try (BufferedWriter bw = new BufferedWriter(new FileWriter(archivoHtmlCsv))) {
                        // Escribir la primera línea modificada
                        bw.write(primeraLineaOriginal + ",\"URL\",\"Localidad\",\"Sector\",\"Empleados\"");
                        bw.newLine();

                        // Escribir la segunda línea modificada 
                        bw.write(segundaLineaOriginal + ",\"" + url + "\",\"" + localidad + "\",\"" + sector + "\",\"" + empleados + "\"");
                        bw.newLine(); // Agregar una nueva línea después de escribir la segunda línea modificada

                        System.out.println("URL escrita en " + archivoHtmlCsv.getName() + ": " + url);
                    } catch (IOException e) {
                        System.out.println("Error al escribir en el archivo " + archivoHtmlCsv.getName());
                        e.printStackTrace();
                    }
                }
            }
            System.out.println("Procesamiento completado para la carpeta " + carpeta.getName());
        } catch (IOException e) {
            System.out.println("Error al leer el archivo CSV en la carpeta " + carpeta.getName());
            e.printStackTrace();
        }
    }

    public static String obtenerNombreBase(String nombreArchivo) {
        // Buscar la posición del signo de interrogación
        int indiceInterrogacion = nombreArchivo.indexOf("?");

        // Si se encuentra un signo de interrogación, devolver la parte del nombre antes de eso
        if (indiceInterrogacion != -1) {
            return nombreArchivo.substring(0, indiceInterrogacion);
        }

        // Si no se encuentra un signo de interrogación, devolver el nombre completo
        return nombreArchivo;
    }

    public static File buscarArchivoCsv(File carpeta, String nombreArchivo) {
        File[] archivos = carpeta.listFiles();
        if (archivos != null) {
            for (File archivo : archivos) {
                if (archivo.isFile() && archivo.getName().startsWith(nombreArchivo) && archivo.getName().endsWith(".csv")) {
                    return archivo;
                }
            }
        }
        return null;
    }
}
