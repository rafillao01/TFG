package proyectosostenibilidad;

import java.io.File;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.List;
import java.util.Map;
import java.util.ArrayList;

public class ConectDataBase {

    // Método para crear la tabla URLEMPRESAS
    public static void crearTabla(Connection connection) throws SQLException {
        String crearTablaURLEmpresasSQL = "CREATE TABLE IF NOT EXISTS URLEMPRESAS (" +
        "NombreEmpresa VARCHAR(255) PRIMARY KEY NOT NULL," +
        "Localidad VARCHAR(255) NOT NULL," +
        "Sector VARCHAR(255) NOT NULL," +
        "Empleados VARCHAR(255) NOT NULL," +
        "URLContaminacionRecursos VARCHAR(255) NOT NULL," +
        "URLAmbientalDiversidad VARCHAR(255) NOT NULL," +
        "URLSocialesPersonal VARCHAR(255) NOT NULL," +
        "URLDerechosHumanos VARCHAR(255) NOT NULL," +
        "URLCorrupcionSoborno VARCHAR(255) NOT NULL," +
        "ScoreContaminacionRecursos FLOAT NOT NULL," +
        "ScoreAmbientalDiversidad FLOAT NOT NULL," +
        "ScoreSocialesPersonal FLOAT NOT NULL," +
        "ScoreDerechosHumanos FLOAT NOT NULL," +
        "ScoreCorrupcionSoborno FLOAT NOT NULL," +
        "ExplicacionContaminacionRecursos TEXT NOT NULL," +
        "ExplicacionAmbientalDiversidad TEXT NOT NULL," +
        "ExplicacionSocialesPersonal TEXT NOT NULL," +
        "ExplicacionDerechosHumanos TEXT NOT NULL," +
        "ExplicacionCorrupcionSoborno TEXT NOT NULL," +
        "ScoreLocalContaminacionRecursos FLOAT NOT NULL," +
        "ScoreLocalAmbientalDiversidad FLOAT NOT NULL," +
        "ScoreLocalSocialesPersonal FLOAT NOT NULL," +
        "ScoreLocalDerechosHumanos FLOAT NOT NULL," +
        "ScoreLocalCorrupcionSoborno FLOAT NOT NULL)";

        String crearTablaConsultaSQL = "CREATE TABLE IF NOT EXISTS CONSULTAS (" +
                "QueryContaminacionRecursos TEXT NOT NULL," +
                "QueryAmbientalDiversidad TEXT NOT NULL," +
                "QuerySocialesPersonal TEXT NOT NULL," +
                "QueryDerechosHumanos TEXT NOT NULL," +
                "QueryCorrupcionSoborno TEXT NOT NULL)";
        
        String crearTablaParrafosSQL = "CREATE TABLE IF NOT EXISTS PARRAFOS (" +
                "ParrafoContaminacionRecursos TEXT NOT NULL," +
                "ParrafoAmbientalDiversidad TEXT NOT NULL," +
                "ParrafoSocialesPersonal TEXT NOT NULL," +
                "ParrafoDerechosHumanos TEXT NOT NULL," +
                "ParrafoCorrupcionSoborno TEXT NOT NULL)";

        String crearTablaTablasUserSQL = "CREATE TABLE IF NOT EXISTS TABLASUSER (" +
                "NombreEmpresa VARCHAR(255) NOT NULL," +
                "NombreUsuario VARCHAR(255) NOT NULL," +
                "PRIMARY KEY (NombreEmpresa, NombreUsuario)," + // Clave primaria compuesta
                "FOREIGN KEY (NombreEmpresa) REFERENCES URLEMPRESAS(NombreEmpresa)," +
                "FOREIGN KEY (NombreUsuario) REFERENCES USUARIOS(NombreUsuario))";

        Statement statement = connection.createStatement();
        statement.execute(crearTablaURLEmpresasSQL);
        statement.execute(crearTablaConsultaSQL);
        statement.execute(crearTablaTablasUserSQL);
        statement.execute(crearTablaParrafosSQL);       
        System.out.println("Tablas creadas correctamente");
    }

    // Método para eliminar las tablas
    public static void eliminarTablas(Connection connection) throws SQLException {
        String eliminarTablaURLEmpresasSQL = "DROP TABLE IF EXISTS URLEMPRESAS";
        String eliminarTablaConsultaSQL = "DROP TABLE IF EXISTS CONSULTAS";
        String eliminarTablaTablasUserSQL = "DROP TABLE IF EXISTS TABLASUSER";
        String eliminarTablaParrafosSQL = "DROP TABLE IF EXISTS PARRAFOS";
        
        Statement statement = connection.createStatement();
        statement.execute(eliminarTablaTablasUserSQL);
        statement.execute(eliminarTablaURLEmpresasSQL);
        statement.execute(eliminarTablaConsultaSQL);
        statement.execute(eliminarTablaParrafosSQL);
        System.out.println("Tablas eliminadas correctamente");
    }

    // Método para insertar datos de empresas y URLs en la tabla URLEMPRESAS
    public static void insertarDatos(Connection connection, String nombreEmpresa, String localidad, String sector, String empleados, String urlContaminacion, String urlAmbientalDiversidad, String urlSocialesPersonal, String urlDerechosHumanos, String urlCorrupcionSoborno, Float scoreContaminacionRecursos, Float scoreAmbientalDiversidad, Float scoreSocialesPersonal, Float scoreDerechosHumanos, Float scoreCorrupcionSoborno, String explicacionContaminacionRecursos, String explicacionAmbientalDiversidad, String explicacionSocialesPersonal, String explicacionDerechosHumanos, String explicacionCorrupcionSoborno, Float scoreLocalContaminacionRecursos, Float scoreLocalAmbientalDiversidad, Float scoreLocalSocialesPersonal, Float scoreLocalDerechosHumanos, Float scoreLocalCorrupcionSoborno) throws SQLException {
        String insertarDatosSQL = "INSERT INTO URLEMPRESAS (NombreEmpresa, Localidad, Sector, Empleados, URLContaminacionRecursos, URLAmbientalDiversidad, URLSocialesPersonal, URLDerechosHumanos, URLCorrupcionSoborno, ScoreContaminacionRecursos, ScoreAmbientalDiversidad, ScoreSocialesPersonal, ScoreDerechosHumanos, ScoreCorrupcionSoborno, ExplicacionContaminacionRecursos, ExplicacionAmbientalDiversidad, ExplicacionSocialesPersonal, ExplicacionDerechosHumanos, ExplicacionCorrupcionSoborno, ScoreLocalContaminacionRecursos, ScoreLocalAmbientalDiversidad, ScoreLocalSocialesPersonal, ScoreLocalDerechosHumanos, ScoreLocalCorrupcionSoborno) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        PreparedStatement preparedStatement = connection.prepareStatement(insertarDatosSQL);
        preparedStatement.setString(1, nombreEmpresa);
        preparedStatement.setString(2, localidad);
        preparedStatement.setString(3, sector);
        preparedStatement.setString(4, empleados);
        preparedStatement.setString(5, urlContaminacion);
        preparedStatement.setString(6, urlAmbientalDiversidad);
        preparedStatement.setString(7, urlSocialesPersonal);
        preparedStatement.setString(8, urlDerechosHumanos);
        preparedStatement.setString(9, urlCorrupcionSoborno);
        preparedStatement.setFloat(10, scoreContaminacionRecursos != null ? scoreContaminacionRecursos : 0); 
        preparedStatement.setFloat(11, scoreAmbientalDiversidad != null ? scoreAmbientalDiversidad : 0); 
        preparedStatement.setFloat(12, scoreSocialesPersonal != null ? scoreSocialesPersonal : 0); 
        preparedStatement.setFloat(13, scoreDerechosHumanos != null ? scoreDerechosHumanos : 0); 
        preparedStatement.setFloat(14, scoreCorrupcionSoborno != null ? scoreCorrupcionSoborno : 0); 
        preparedStatement.setString(15, explicacionContaminacionRecursos);
        preparedStatement.setString(16, explicacionAmbientalDiversidad);
        preparedStatement.setString(17, explicacionSocialesPersonal);
        preparedStatement.setString(18, explicacionDerechosHumanos);
        preparedStatement.setString(19, explicacionCorrupcionSoborno);
        preparedStatement.setFloat(20, scoreLocalContaminacionRecursos != null ? scoreLocalContaminacionRecursos : 0); 
        preparedStatement.setFloat(21, scoreLocalAmbientalDiversidad != null ? scoreLocalAmbientalDiversidad : 0); 
        preparedStatement.setFloat(22, scoreLocalSocialesPersonal != null ? scoreLocalSocialesPersonal : 0); 
        preparedStatement.setFloat(23, scoreLocalDerechosHumanos != null ? scoreLocalDerechosHumanos : 0); 
        preparedStatement.setFloat(24, scoreLocalCorrupcionSoborno != null ? scoreLocalCorrupcionSoborno : 0); 

        preparedStatement.executeUpdate();
        System.out.println("Datos insertados correctamente para la empresa: " + nombreEmpresa);
    }

    // Método para insertar consultas en la tabla Consulta
    public static void insertarConsulta(Connection connection, String queryContaminacionRecursos, String queryAmbientalDiversidad, String querySocialesPersonal, String queryDerechosHumanos, String queryCorrupcionSoborno) throws SQLException {
        String insertarConsultaSQL = "INSERT INTO CONSULTAS (QueryContaminacionRecursos, QueryAmbientalDiversidad, QuerySocialesPersonal, QueryDerechosHumanos, QueryCorrupcionSoborno) VALUES (?, ?, ?, ?, ?)";

        PreparedStatement preparedStatement = connection.prepareStatement(insertarConsultaSQL);
        preparedStatement.setString(1, queryContaminacionRecursos);
        preparedStatement.setString(2, queryAmbientalDiversidad);
        preparedStatement.setString(3, querySocialesPersonal);
        preparedStatement.setString(4, queryDerechosHumanos);
        preparedStatement.setString(5, queryCorrupcionSoborno);

        preparedStatement.executeUpdate();
        System.out.println("Consulta insertada correctamente");
    }

    // Método para insertar párrafos en la tabla PARRAFOS
    public static void insertarParrafos(Connection connection, String parrafoContaminacionRecursos, String parrafoAmbientalDiversidad, String parrafoSocialesPersonal, String parrafoDerechosHumanos, String parrafoCorrupcionSoborno) throws SQLException {
        String insertarParrafosSQL = "INSERT INTO PARRAFOS (ParrafoContaminacionRecursos, ParrafoAmbientalDiversidad, ParrafoSocialesPersonal, ParrafoDerechosHumanos, ParrafoCorrupcionSoborno) VALUES (?, ?, ?, ?, ?)";

        PreparedStatement preparedStatement = connection.prepareStatement(insertarParrafosSQL);
        preparedStatement.setString(1, parrafoContaminacionRecursos);
        preparedStatement.setString(2, parrafoAmbientalDiversidad);
        preparedStatement.setString(3, parrafoSocialesPersonal);
        preparedStatement.setString(4, parrafoDerechosHumanos);
        preparedStatement.setString(5, parrafoCorrupcionSoborno);

        preparedStatement.executeUpdate();
        System.out.println("Párrafos insertados correctamente");
    }

    public static void main(String[] args) {
        Connection connection = null;

        try {
            // Paso 1: Registrar el driver JDBC
            Class.forName("com.mysql.cj.jdbc.Driver");

            // Paso 2: Establecer la conexión con la base de datos
            String url = "jdbc:mysql://localhost:3306/ProyectoSostenibilidad";
            String usuario = "rcordobam";
            String contraseña = "rcordobam";
            connection = DriverManager.getConnection(url, usuario, contraseña);

            // Paso 3: Eliminar las tablas si existen
            eliminarTablas(connection);

            // Paso 4: Crear las tablas
            crearTabla(connection);
            
            // Paso 5: Obtener el HashMap mapaURLS de HTMLBusqueda
            String csvFilePath = "D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/Terminos/terminos.txt";

            // Llama al método leerParrafosDesdeTXT para obtener los párrafos
            List<String> parrafos = ParrafosReader.leerParrafosDesdeTXT(csvFilePath);
            // Lista para almacenar los párrafos preprocesados
            List<String> parrafosPreprocesados = new ArrayList<>();
            
            if (parrafos.size() >= 5) {
                insertarParrafos(connection, parrafos.get(0), parrafos.get(1), parrafos.get(2), parrafos.get(3), parrafos.get(4));
            } else {
                System.out.println("No hay suficientes párrafos en el archivo CSV");
            }
            
            HTMLBusqueda htmlBusqueda = new HTMLBusqueda();
            for (String parrafo : parrafos) {
                // Realizar búsqueda con el párrafo obtenido
                List<ResultadoBusqueda> resultados = HTMLBusqueda.buscarDocumentoSimilar(parrafo);
                String parrafoPreprocesado = HTMLBusqueda.preprocesarParrafo(parrafo);
                parrafosPreprocesados.add(parrafoPreprocesado);
                System.out.println("Párrafo preprocesado: " + parrafoPreprocesado);
            }
            
            HTMLBusqueda.normalizarScoresPorFilas();
            HTMLBusqueda.normalizarScoresPorColumnas();
            // Insertar los párrafos preprocesados en la tabla Consulta
            if (!parrafosPreprocesados.isEmpty()) {
                insertarConsulta(connection, parrafosPreprocesados.get(0), parrafosPreprocesados.get(1), parrafosPreprocesados.get(2), parrafosPreprocesados.get(3), parrafosPreprocesados.get(4));
            }

            Map<String, String> mapaURLS = htmlBusqueda.obtenerMapaURLs();
            Map<String, String> mapaExplicaciones = htmlBusqueda.obtenerMapaExplicaciones();
            Map<String, Float> mapaScoresGlobal = htmlBusqueda.obtenerMapaScoresGlobal();
            Map<String, Float> mapaScoresLocal = htmlBusqueda.obtenerMapaScoresLocal();
            Map<String, String> mapaValores = htmlBusqueda.obtenerMapaValores();
            
            // Paso 6: Insertar datos de las empresas en las carpetas
            File carpeta = new File("D:/GIIADE/5º AÑO/2ºCuatri/TFG INFORMÁTICA/PROYECTO SOSTENIBILIDAD/TextoPaginas");
            if (carpeta.isDirectory()) {
                File[] carpetasEmpresas = carpeta.listFiles();
                if (carpetasEmpresas != null) {
                    int columna = 0;
                    int fila = 0;
                    for (File empresa : carpetasEmpresas) {
                        if (empresa.isDirectory()) {
                            String nombreEmpresa = empresa.getName();

                            String[] claves = new String[5];
                            for (int i = 0; i < claves.length; i++) {
                                claves[i] = (fila + i) + "," + columna;
                            }

                            String URLContaminacionRecursos = mapaURLS.getOrDefault(claves[0], "NO HAY RESULTADOS COINCIDENTES");
                            String URLAmbientalDiversidad = mapaURLS.getOrDefault(claves[1], "NO HAY RESULTADOS COINCIDENTES");
                            String URLSocialesPersonal = mapaURLS.getOrDefault(claves[2], "NO HAY RESULTADOS COINCIDENTES");
                            String URLDerechosHumanos = mapaURLS.getOrDefault(claves[3], "NO HAY RESULTADOS COINCIDENTES");
                            String URLCorrupcionSoborno = mapaURLS.getOrDefault(claves[4], "NO HAY RESULTADOS COINCIDENTES");

                            Float scoreContaminacionRecursos = mapaScoresGlobal.get(claves[0]);
                            Float scoreAmbientalDiversidad = mapaScoresGlobal.get(claves[1]);
                            Float scoreSocialesPersonal = mapaScoresGlobal.get(claves[2]);
                            Float scoreDerechosHumanos = mapaScoresGlobal.get(claves[3]);
                            Float scoreCorrupcionSoborno = mapaScoresGlobal.get(claves[4]);
                            
                            Float scorelocalContaminacionRecursos = mapaScoresLocal.get(claves[0]);
                            Float scorelocalAmbientalDiversidad = mapaScoresLocal.get(claves[1]);
                            Float scorelocalSocialesPersonal = mapaScoresLocal.get(claves[2]);
                            Float scorelocalDerechosHumanos = mapaScoresLocal.get(claves[3]);
                            Float scorelocalCorrupcionSoborno = mapaScoresLocal.get(claves[4]);

                            String explicacionContaminacionRecursos = mapaExplicaciones.getOrDefault(claves[0], "");
                            String explicacionAmbientalDiversidad = mapaExplicaciones.getOrDefault(claves[1], "");
                            String explicacionSocialesPersonal = mapaExplicaciones.getOrDefault(claves[2], "");
                            String explicacionDerechosHumanos = mapaExplicaciones.getOrDefault(claves[3], "");
                            String explicacionCorrupcionSoborno = mapaExplicaciones.getOrDefault(claves[4], "");

                            insertarDatos(connection, nombreEmpresa, mapaValores.get(claves[0]), mapaValores.get(claves[1]), mapaValores.get(claves[2]), URLContaminacionRecursos, URLAmbientalDiversidad, URLSocialesPersonal, URLDerechosHumanos, URLCorrupcionSoborno,
                                    scoreContaminacionRecursos, scoreAmbientalDiversidad, scoreSocialesPersonal, scoreDerechosHumanos, scoreCorrupcionSoborno, explicacionContaminacionRecursos, explicacionAmbientalDiversidad, explicacionSocialesPersonal, explicacionDerechosHumanos, explicacionCorrupcionSoborno, 
                                    scorelocalContaminacionRecursos, scorelocalAmbientalDiversidad, scorelocalSocialesPersonal, scorelocalDerechosHumanos, scorelocalCorrupcionSoborno);
                        }
                        columna++;
                    }
                }
            }

        } catch (ClassNotFoundException e) {
            System.out.println("No se pudo cargar el driver JDBC");
            e.printStackTrace();
        } catch (SQLException e) {
            System.out.println("Error al establecer la conexión con la base de datos o al crear la tabla");
            e.printStackTrace();
        } finally {
            // Paso 7: Cerrar la conexión y liberar recursos
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