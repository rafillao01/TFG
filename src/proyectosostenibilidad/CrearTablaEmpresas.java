package proyectosostenibilidad;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.HashSet;
import java.util.Set;

public class CrearTablaEmpresas {

    // Método para crear la tabla de empresas
    public static void crearTablaEmpresas(Connection connection) throws SQLException {
        String crearTablaEmpresasSQL = "CREATE TABLE IF NOT EXISTS EMPRESASESPANA (" +
                "URLEmpresa VARCHAR(255) PRIMARY KEY," +
                "NombreEmpresa VARCHAR(255) NOT NULL," +
                "Localidad VARCHAR(255) NOT NULL," +
                "Sector VARCHAR(255) NOT NULL," +
                "Empleados INT NOT NULL)";

        Statement statement = connection.createStatement();
        statement.execute(crearTablaEmpresasSQL);
        System.out.println("Tabla EmpresasEspana creada correctamente");
    }

    // Método para crear la tabla de usuarios
    public static void crearTablaUsuarios(Connection connection) throws SQLException {
        String crearTablaUsuariosSQL = "CREATE TABLE IF NOT EXISTS USUARIOS (" +
                "Nombre VARCHAR(255) NOT NULL," +
                "Apellidos VARCHAR(255) NOT NULL," +
                "NombreUsuario VARCHAR(255) PRIMARY KEY," +
                "Correo VARCHAR(255) NOT NULL," +
                "Telefono VARCHAR(255) NOT NULL," +
                "Tipo VARCHAR(255) NOT NULL," +
                "Contrasena VARCHAR(255) NOT NULL)";

        Statement statement = connection.createStatement();
        statement.execute(crearTablaUsuariosSQL);
        System.out.println("Tabla Usuarios creada correctamente");
    }
    
    
    // Método para crear la tabla de sugerencias
    public static void crearTablaSugerencias(Connection connection) throws SQLException {
        String crearTablaSugerenciasSQL = "CREATE TABLE IF NOT EXISTS SUGERENCIAS (" +
                "ID INT AUTO_INCREMENT PRIMARY KEY," +
                "NombreUsuario VARCHAR(255) NOT NULL," +
                "Sugerencia TEXT NOT NULL," +
                "Fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP," +
                "FOREIGN KEY (NombreUsuario) REFERENCES USUARIOS(NombreUsuario))";

        Statement statement = connection.createStatement();
        statement.execute(crearTablaSugerenciasSQL);
        System.out.println("Tabla Sugerencias creada correctamente");
    }

    // Método para insertar datos en la tabla de empresas
    public static void insertarDatosEmpresas(Connection connection, String urlEmpresa, String localidad, String sector,
            int empleados) throws SQLException {
        String insertarDatosEmpresasSQL = "INSERT INTO EMPRESASESPANA (URLEmpresa, NombreEmpresa, Localidad, Sector, Empleados) VALUES (?, ?, ?, ?, ?)";

        // Extraer el nombre de la empresa de la URL
        String nombreEmpresa = urlEmpresa.substring(urlEmpresa.indexOf("www.") + 4,
                urlEmpresa.indexOf(".", urlEmpresa.indexOf("www.") + 4));

        try (PreparedStatement preparedStatement = connection.prepareStatement(insertarDatosEmpresasSQL)) {
            preparedStatement.setString(1, urlEmpresa);
            preparedStatement.setString(2, nombreEmpresa);
            preparedStatement.setString(3, localidad);
            preparedStatement.setString(4, cleanSpecialCharacters(sector)); // Limpiar tildes del sector
            preparedStatement.setInt(5, empleados);

            preparedStatement.executeUpdate();
        }
    }

    // Método para insertar datos en la tabla de usuarios
    public static void insertarDatosUsuarios(Connection connection, String nombre, String apellidos, String nombreUsuario,
            String correo, String telefono, String tipo, String contrasena) throws SQLException {
        String insertarDatosUsuariosSQL = "INSERT INTO USUARIOS (Nombre, Apellidos, NombreUsuario, Correo, Telefono, Tipo, Contrasena) VALUES (?, ?, ?, ?, ?, ?, ?)";

        try (PreparedStatement preparedStatement = connection.prepareStatement(insertarDatosUsuariosSQL)) {
            preparedStatement.setString(1, nombre);
            preparedStatement.setString(2, apellidos);
            preparedStatement.setString(3, nombreUsuario);
            preparedStatement.setString(4, correo);
            preparedStatement.setString(5, telefono);
            preparedStatement.setString(6, tipo);
            preparedStatement.setString(7, contrasena);

            preparedStatement.executeUpdate();
        }
    }

    private static String cleanSpecialCharacters(String text) {
        text = text.replaceAll("[áÁ]", "a");
        text = text.replaceAll("[éÉ]", "e");
        text = text.replaceAll("[íÍ]", "i");
        text = text.replaceAll("[óÓ]", "o");
        text = text.replaceAll("[úÚ]", "u");
        text = text.replaceAll("[ñÑ]", "n");
        text = text.replaceAll(",", ""); 
        return text;
    }

    public static void main(String[] args) {
        Connection connection = null;
        Set<String> urlsInsertadas = new HashSet<>();

        try {
            // Paso 1: Registrar el driver JDBC
            Class.forName("com.mysql.cj.jdbc.Driver");

            // Paso 2: Establecer la conexión con la base de datos
            String url = "jdbc:mysql://localhost:3306/ProyectoSostenibilidad";
            String usuario = "rcordobam";
            String contraseña = "rcordobam";
            connection = DriverManager.getConnection(url, usuario, contraseña);

            // Paso 3: Crear las nuevas tablas
            crearTablaEmpresas(connection);
            crearTablaUsuarios(connection);
            crearTablaSugerencias(connection);

            // Ruta de los archivos CSV
            String csvFilePathEmpresas = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/INFO PREVIA SABI/Empresas.csv";
            String csvFilePathLocalidades = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/INFO PREVIA SABI/Localidades.csv";
            String txtFilePathSector = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/INFO PREVIA SABI/Sector.txt";
            String csvFilePathEmpleados = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/INFO PREVIA SABI/Empleados.csv";

            // Lectura del archivo CSV de empresas
            try (BufferedReader readerEmpresas = new BufferedReader(new FileReader(csvFilePathEmpresas));
                 BufferedReader readerLocalidades = new BufferedReader(new FileReader(csvFilePathLocalidades));
                 BufferedReader readerSector = new BufferedReader(new FileReader(txtFilePathSector));
                 BufferedReader readerEmpleados = new BufferedReader(new FileReader(csvFilePathEmpleados))) {

                // Saltar la primera línea de cada archivo CSV
                readerEmpresas.readLine();
                readerLocalidades.readLine();
                readerEmpleados.readLine();

                String nextLineEmpresas;
                String nextLineLocalidades;
                String nextLineSector;
                String nextLineEmpleados;

                while ((nextLineEmpresas = readerEmpresas.readLine()) != null
                        && (nextLineLocalidades = readerLocalidades.readLine()) != null
                        && (nextLineSector = readerSector.readLine()) != null
                        && (nextLineEmpleados = readerEmpleados.readLine()) != null) {

                    String[] datosEmpresa = nextLineEmpresas.split(",");
                    String[] datosLocalidades = nextLineLocalidades.split(",");
                    String[] datosEmpleados = nextLineEmpleados.split(",");

                    String urlEmpresa = datosEmpresa[0];
                    String localidad = datosLocalidades[0];
                    String sector = nextLineSector.trim(); // Trim to remove leading/trailing whitespaces
                    int empleados = Integer.parseInt(datosEmpleados[0]);

                    // Verificar si la URL ya fue insertada
                    if (urlsInsertadas.contains(urlEmpresa)) {
                        System.out.println("URL duplicada encontrada: " + urlEmpresa);
                        continue; // Pasar a la siguiente fila
                    }

                    // Insertar los datos en la tabla de empresas
                    insertarDatosEmpresas(connection, urlEmpresa, localidad, sector, empleados);

                    // Agregar la URL al conjunto de URLs insertadas
                    urlsInsertadas.add(urlEmpresa);
                }
            }

            System.out.println("Datos insertados correctamente desde los archivos CSV");

        } catch (ClassNotFoundException e) {
            System.out.println("No se pudo cargar el driver JDBC");
            e.printStackTrace();
        } catch (SQLException e) {
            System.out.println("Error al establecer la conexión con la base de datos o al insertar datos");
            e.printStackTrace();
        } catch (IOException e) {
            System.out.println("Error al leer los archivos CSV");
            e.printStackTrace();
        } finally {
            // Cerrar la conexión
            try {
                if (connection != null) {
                    connection.close();
                    System.out.println("Conexión cerrada");
                }
            } catch (SQLException e) {
                System.out.println("Error al cerrar la conexión");
                e.printStackTrace();
            }
        }
    }
}
