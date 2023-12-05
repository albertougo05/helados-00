/**
 * Edita movimiento de caja
 */

import IMask from 'imask';
import masks from "../utils/masks";
import horaFromTimestamp from '../utils/horaFromTimestamp';
//import { strToFloat } from '../utils/solo-numeros';
 
 
// Inicializar form
const inputHora = IMask(document.getElementById('hora'), masks.hora);
const inputImporte_1 = IMask(document.getElementById('importe_1'), masks.currency);

inputHora.typedValue = horaFromTimestamp(CAJA._fecha_hora);

// Click boton confirmar
// const btnConfirma = document.querySelector('#btnConfirma');
// btnConfirma.addEventListener('click', function (e) {
//     e.preventDefault();

//     const importe = document.querySelector('#importe_1').value;
//     document.querySelector('#importe').value = strToFloat(importe);
//     document.querySelector('#formMovim').submit();
// });