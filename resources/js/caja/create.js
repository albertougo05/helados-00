import IMask from 'imask';
import masks from "../utils/masks";
import formatMinutos from '../turnos/formatMinutos';
import MensajeAlerta from '../utils/MensajeAlerta';
import { strToFloat } from '../utils/solo-numeros';
import { spanishToEnglish } from '../utils/transformFechas';
import imprimeMovimCaja from './imprimeMovimCaja.js';

const _checkDataForm = (importe, selecTipo) => {
    let data = {
        result: false,
        message: '',
        input: '',
    }
    if (selecTipo.value == 0) {
        data.message = 'Seleccione movimiento !!';
        data.input = '#tipo_comprobante_id';

        return data;
    }
    if (importe === '') {
        data.message = 'Ingrese importe !!';
        data.input = '#importe';
    } else {
        data.result = true;
    }

    return data;
}

function _changeTipoMovimSelec(ev) {
    const tipo_mov = ev.target.value;
    const id_tipo_movim = ev.target.options[ev.target.selectedIndex].dataset.idtipomovim;
    const importe = document.querySelector('#importe_1');
    const descrip = document.querySelector('#descrip_tipo_mov');
    document.querySelector('[name="tipo_movim_id"]').value = id_tipo_movim;

    switch (tipo_mov) {
        case '4': // Egreso
            CAJA._tipo_movim = 'SALIDA';
            importe.classList.remove('bg-green-600');
            importe.classList.add('bg-red-500');
            descrip.innerHTML = '<p></p><span class="bg-red-500 text-red-100 text-xs font-bold py-1 px-5 rounded-full">Egreso</span></p>';
            break;
        case '5':  // Ingreso
            CAJA._tipo_movim = 'ENTRADA';
            importe.classList.remove('bg-red-500');
            importe.classList.add('bg-green-600');
            descrip.innerHTML = '<p><span class="bg-green-600 text-green-100 text-xs font-bold py-1 px-5 rounded-full">Ingreso</span></p>';
            break;
    }
    importe.focus();

    return null;
};

function _setFormParaSubmit(tipo_movim_selec) {
    const fecha = spanishToEnglish(document.querySelector('#fecha').value);
    const hora = document.querySelector('#hora').value;
    const importe = document.querySelector('#importe_1').value;
    // 5 - Ingreso / 4 - Egreso
    const text_concepto = tipo_movim_selec.options[tipo_movim_selec.selectedIndex].text;
    document.querySelector('#concepto').value = text_concepto;
    document.querySelector('#fecha_hora').value = fecha + ' ' + hora;
    document.querySelector('#importe').value = strToFloat(importe);

    return null;
}


window.onload = function() {
    // Inicializar form
    const inputHora = document.getElementById('hora');
    const inputImporte = IMask(document.getElementById('importe_1'), masks.currency);
    document.getElementById('fecha').value = CAJA._fecha_actual;
    document.getElementById('fecha_registro').value = spanishToEnglish(CAJA._fecha_actual);
    document.querySelector('#empleado').value = CAJA._usuario_nombre;

    // Si muestra status, lo quita a los 3 segundos...
    if (CAJA._hayStatus) {
        setTimeout(function(){
            const alert = document.querySelector('.alert');
            alert.classList.add('hidden');
        }, 3000);
    }

    const _hora = new Date();
    inputHora.value = formatMinutos(_hora.getHours()) + ':' + formatMinutos(_hora.getMinutes());

    // Change select tipo comprobante...
    const tipo_movim_selec = document.querySelector('#tipo_comprobante_id');
    tipo_movim_selec.addEventListener('change', _changeTipoMovimSelec);

    // Click boton confirmar (IMPRIME MOVIMIENTO !!)
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', function (e) {
        e.preventDefault();
        const tipo_movim_selec = document.querySelector('#tipo_comprobante_id');
        const checkData = _checkDataForm(inputImporte.value, tipo_movim_selec);

        if (checkData.result) {
            const spin = document.querySelector('#spinGuardando');
            spin.classList.remove('hidden');

            _setFormParaSubmit(tipo_movim_selec);
            imprimeMovimCaja();
            document.querySelector('#formMovim').submit();

        } else {
            MensajeAlerta(checkData.message);
            document.querySelector(checkData.input).focus();
        }
    });

}