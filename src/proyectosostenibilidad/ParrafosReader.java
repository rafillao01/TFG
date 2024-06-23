/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package proyectosostenibilidad;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.List;

public class ParrafosReader {

    public static List<String> leerParrafosDesdeTXT(String txtFilePath) {
        List<String> parrafos = new ArrayList<>();
        
        try (BufferedReader br = new BufferedReader(new FileReader(txtFilePath, StandardCharsets.UTF_8))) {
            String parrafo;
            while ((parrafo = br.readLine()) != null) {
                parrafos.add(parrafo);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
        
        return parrafos;
    }
}
