/**
 * ../ventas/mix/index.js   (Mix de ventas)
 */

import IMask from 'imask';
import masks from "../../utils/masks";
import MensajeAlerta from '../../utils/MensajeAlerta';
import { fechaActual, _sumaRestaFecha } from '../../utils/transformFechas';


const inputHoraDesde = IMask(document.getElementById('hora_desde'), masks.hora),
      inputHoraHasta = IMask(document.getElementById('hora_hasta'), masks.hora),
      fechaDesde = document.querySelector('#fecha_desde'),
      fechaHasta = document.querySelector('#fecha_hasta');

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

    return result;
};

const _getDataUrl = () => {
    const desde = `${fechaDesde.value} ${inputHoraDesde.value}`;
    const hasta = `${fechaHasta.value} ${inputHoraHasta.value}`;

    return `?desde=${desde}&hasta=${hasta}`;
};


//
// Despues de cargar la página...
//
window.onload = function() {
    // Inicializar form
    const btnGenerarInfo = document.querySelector('#btnGenerarInfo');

    inputHoraDesde.typedValue = '08:00';
    inputHoraHasta.typedValue = '23:59';
    document.getElementById('fecha_hasta').value = fechaActual('en');
    document.getElementById('fecha_desde').value = _sumaRestaFecha('', '-', 30);

    btnGenerarInfo.addEventListener('click', e => {
        if (_dataIsOk()) {
            const url = _pathDataMix + _getDataUrl();
            //console.log('Datos:', data);
            window.open(url, "_blank");
        }
    });


}
