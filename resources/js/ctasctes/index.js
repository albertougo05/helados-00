/**
 * Index Ctas. Ctes.
 */

import { fechaActual, _sumaRestaFecha, englishToSpanish } from '../utils/transformFechas';
import MensajeAlerta from '../utils/MensajeAlerta';
import UiCtaCte from './UiCtaCte';


window.onload = function() {
    document.getElementById('fecha_hasta').value = fechaActual('en');
    document.getElementById('fecha_desde').value = _sumaRestaFecha('', '-', 30);

    const btnGenerarInfo = document.querySelector('#btnGenerarInfo');
    btnGenerarInfo.addEventListener('click', elem => {
        const selCliente = document.querySelector('#cliente').value;

        if (selCliente === "0") {
            MensajeAlerta('No hay cliente selecionado !!');
            document.querySelector('#cliente').focus();
            return null;
        }

        const buscaCtaCte = _getDataCtaCte(selCliente);
        buscaCtaCte.then(data => {
            if (data.length > 0) {
                const dataCtacte = _getDatosOk(data);
                const uiTabla = new UiCtaCte();
                uiTabla.llenarTabla(dataCtacte);
            } else MensajeAlerta('Sin movimientos !!');
        });
    });
}


const _getDataCtaCte = async firma_id => {
    const config = _getConfigAxios(firma_id);
    const res = await axios.get(_CTASCTES._pathDataCtaCte, config);

    return res.data;
};

const _getConfigAxios = id => {
    return {
        method: 'GET',
        params: {
            firma_id: id,
            fecha_desde: document.querySelector('#fecha_desde').value,
            fecha_hasta: document.querySelector('#fecha_hasta').value,
        }
    };
};

const _getDatosOk = data => {

    return data.map(elem => {
        elem.importe = parseFloat(elem.importe);
        elem.hora = elem.fecha_hora.substring(11, 16);  // 2023-11-01 12:00 
        elem.fecha_registro = englishToSpanish(elem.fecha_registro);

        return elem;
    });
};