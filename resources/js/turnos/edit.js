/** 
 * 
 * Turnos - Cerrar caja/turno
 * 
 */

import formatMinutos from './formatMinutos';
import InitEditForm from './InitEditForm';
import { fechaActual } from '../utils/transformFechas';
import Swal from "sweetalert2";


TURNO._tablaBilletes = [];

TURNO._initTablaBilletes = () => {
    TURNO._billetes.forEach(elem => {
        TURNO._tablaBilletes.push({
            id: elem.id,
            cant: 0,
            importe: parseInt(elem.importe),
            total: 0,
        });
    });
};

TURNO._actualizarHora = (_inp) => {
    const _hora = new Date();
    _inp.HoraCierre.value = _hora.getHours() + ':' + formatMinutos(_hora.getMinutes());

    return null;
};

TURNO._actualizarInputsHidden = () => {
    const inpDifer = document.querySelector("[name='diferencia']");
    const difer = TURNO._sumaBilletes() - TURNO._totales['caja_teorica'];
    inpDifer.value = difer.toFixed(2);

    const cierreFechaHora = document.querySelector("[name='cierre_fecha_hora']");
    const hora = document.querySelector("#cierre_hora");
    cierreFechaHora.value = TURNO._fecha_actual + ' ' + hora.value;

    return null;
};

// Obtiene los inputs
const _init = new InitEditForm();
TURNO._inputs = _init.getInputs();

const _actualizarFechaCierreTurno = () => {
    const inputFechaHora = document.querySelector("[name='cierre_fecha_hora']");
    inputFechaHora.value = _formatCierreFechaHora();

    return null;
};

const _formatCierreFechaHora = () => {
    //const fecha = document.querySelector('#cierre_fecha').value;
    const fecha = fechaActual('en');
    const hora = document.querySelector('#cierre_hora').value;

    return fecha + ' ' + hora;
};


window.onload = function() {

    console.log('Caja teorica:', TURNO._totales.caja_teorica);

    // Enter y flecha abajo baja en inputs - Flecha arriba, sube foco en inputs numericos
    document.addEventListener('keydown', function (ev) {
        //if (ev.key === "Enter" && ev.target.nodeName === 'INPUT') {
        if ((ev.key === "Enter" || ev.key === "ArrowDown" || ev.key === "ArrowUp") && ev.target.nodeName === 'INPUT') {
            //const focusableElementsString = 'a[href], input:not([disabled]), select:not([disabled]), button:not([disabled]), [tabindex="0"], [contenteditable]';
            const focusableElementsString = 'input:not([readonly])';
            let ol = document.querySelectorAll(focusableElementsString);
            let o = {};

            for (let i=0; i<ol.length; i++) {
                if (ol[i] === ev.target) {
                    if (ev.key === "ArrowUp") {
                        //let o = i < ol.length - 1 ? ol[i-1] : ol[0];
                        o = i < ol.length - 1 ? ol[i-1] : ol[i-1];    // Sube
                    } else {
                        o = i < ol.length - 1 ? ol[i+1] : ol[0];    // Baja
                    }
                    o.focus(); 
                    break;
                }
            }
            ev.preventDefault();
        }
    });

    // Set fecha actual a fecha del navegador
    document.getElementById('cierre_fecha').value = fechaActual('es');
    // Set hora actual del navegador
    TURNO._actualizarHora(TURNO._inputs);

    // Armo tabla (array) para contar billetes
    TURNO._initTablaBilletes();

    // Evento click boton Confirma cierre de caja
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', async (e) => {
        e.preventDefault();

        Swal.fire({
            title: 'Está seguro que cierra turno ?',
            showCancelButton: true,
            icon: 'question',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#e53e3e ',
            confirmButtonText: 'Si, cerrar',
            confirmButtonColor: '#2f855a',
        }).then((result) => {
            /* Si confirma... */
            if (result.isConfirmed) {
                const spinGuardando = document.querySelector('#spinGuardando');
                spinGuardando.classList.remove('hidden');
                TURNO._actualizarHora(TURNO._inputs);
                TURNO._actualizarInputsHidden();
                _actualizarFechaCierreTurno();
                document.querySelector('#formTurno').submit();
            }
        });
    });
};


//
// Funciones para eventos
//
TURNO._formatCurrency = (value) =>{
    const options = { style: 'currency', currency: 'EUR' };
    const numberFormat = new Intl.NumberFormat('de-DE', options);

    return numberFormat.format(value).slice(0, -2);
};

TURNO._setCantBilletes = function (id, cant, total) {
    TURNO._tablaBilletes[id].cant = cant;
    TURNO._tablaBilletes[id].total = total;

    return null;
};

TURNO._sumaBilletes = function () {
    let sumaArqueo = 0;

    TURNO._tablaBilletes.forEach(elem => {
        sumaArqueo += elem.total;
    });

    return sumaArqueo;
};

TURNO._sumoTotales = (suma) => {
    let total = suma;

    total += parseFloat(TURNO._totales['tarj_cred']);
    total += parseFloat(TURNO._totales['tarj_debi']);
    total += parseFloat(TURNO._totales['cta_cte']);
    total += parseFloat(TURNO._totales['transfer']);
    //total += parseFloat(TURNO._totales['ingresos']);
    // Resto los egresos
    //total -= parseFloat(TURNO._totales['egresos']);

    return total;
};

TURNO._actualizoInputs = (suma) => {
    TURNO._inputs.Arqueo.typedValue = suma; //TURNO._formatCurrency(suma);

    if (suma > 0) {
        arqueo.classList.add('ring', 'ring-indigo-200');
    } else {
        arqueo.classList.remove('ring', 'ring-indigo-200');
    }

    return null;
};

TURNO._onkeyUpBilletes = function (elem) {
	const cantidad = parseInt(elem.value);
    const nombre = elem.id;
    const id_bill = parseInt(nombre.slice(5));
    const idx = TURNO._billetes.findIndex(x => x.id == id_bill);
    const div_tot = document.querySelector('#tot-' + id_bill);

    if (cantidad) {  // Solo si existe un valor en cantidad
        const importe = TURNO._billetes[idx].importe;
        const total = cantidad * importe;
        div_tot.innerHTML = TURNO._formatCurrency(total);
        elem.value = cantidad;
        TURNO._setCantBilletes(idx, cantidad, total);
        
    } else {
        elem.value = '';
        TURNO._setCantBilletes(idx, 0, 0);
        div_tot.innerHTML = '';
    }

    this._actualizoInputs(this._sumaBilletes());

    return null;
};
