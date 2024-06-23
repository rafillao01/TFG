/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package proyectosostenibilidad;

public class TerminoRelevante {
    private String termino;
    private float score;

    public TerminoRelevante(String termino, float score) {
        this.termino = termino;
        this.score = score;
    }

    @Override
    public String toString() {
        return "termino:" + termino + " y score=" + score;
    }
}
