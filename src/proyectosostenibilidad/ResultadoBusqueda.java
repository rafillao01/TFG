/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package proyectosostenibilidad;

public class ResultadoBusqueda {
    private final String id;
    private final float score;
    private final String fieldValues;

    public ResultadoBusqueda(String id, float score, String fieldValues) {
        this.id = id;
        this.score = score;
        this.fieldValues = fieldValues;
    }

    public String getId() {
        return id;
    }

    public float getScore() {
        return score;
    }

    public String getFieldValues() {
        return fieldValues;
    }
}