/**
 * 
 * index.js - Informe desvio stock
 * 
 */

import buscarData from "./buscarData";


//
// Despues de cargar la página...
//
window.onload = function() {

    // Click btn generar informe
    const btnGenerar = document.querySelector('#btnGenerar');
    btnGenerar.addEventListener('click', function (e) {
        e.preventDefault();

        buscarData(e.target);

    });
}