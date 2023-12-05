/**
 * index.js   (Informe movimientos caja)
 */

import IMask from 'imask';
import masks from "../../utils/masks";
import MensajeAlerta from '../../utils/MensajeAlerta';
import { fechaActual, horaActual, _sumaRestaFecha } from '../../utils/transformFechas';


const inputHoraDesde = IMask(document.getElementById('hora_desde'), masks.hora),
      inputHoraHasta = IMask(document.getElementById('hora_hasta'), masks.hora),
      selUsuario = document.querySelector('#usuario_id'),
      selTipoMov = document.querySelector('#tipo_movim'),
      fechaDesde = document.querySelector('#fecha_desde'),
      fechaHasta = document.querySelector('#fecha_hasta'),
      selFormPago = document.querySelector('#formas_pago');

const _dataIsOk = () => {
    let result = true;

    if (fechaDesde.value === '') {
        MensajeAlerta('Fecha desde inválida !!', 'error');
        fechaDesde.focus();
        result = false;
    } else if (fechaHasta.value === '') {
        MensajeAlerta('Fecha hasta inválida !!', 'error');
        fechaHasta.focus();
        result = false;
    } 
    if (selTipoMov.value == 0 && selFormPago.value == 0) {
        MensajeAlerta('Debe seleccionar movimiento !!', 'error');
        selTipoMov.focus();
        result = false;
    }

    return result;
};

const _getDataUrl = () => {
    let data = 'desde=' + fechaDesde.value + ' ' + inputHoraDesde.value;

    data += '&hasta=' + fechaHasta.value + ' ' + inputHoraHasta.value;
    data += '&user=' + selUsuario.value;
    data += '&timo=' + selTipoMov.value;
    data += '&fopa=' + selFormPago.value;

    return data;
};


//
// Despues de cargar la página...
//
window.onload = function() {
    // Inicializar form
    const btnGenerarInfo = document.querySelector('#btnGenerarInfo');

    inputHoraDesde.typedValue = '00:00';
    inputHoraHasta.typedValue = horaActual();
    document.getElementById('fecha_hasta').value = fechaActual('en');
    document.getElementById('fecha_desde').value = _sumaRestaFecha('', '-', 30);

    selTipoMov.addEventListener('change', e => {
        if (selFormPago.value !== '0') {
            selFormPago.value = '0';
        }
    });

    selFormPago.addEventListener('change', e => {
        if (selTipoMov.value !== '0') {
            selTipoMov.value = '0';
        }
    });

    btnGenerarInfo.addEventListener('click', e => {
        if (_dataIsOk()) {
            const data = _getDataUrl();
            const url = _INFORM._pathShowInfo + '?' + data;
            //console.log('Datos:', data);
            window.open(url, "_blank");
        }
    });


}
