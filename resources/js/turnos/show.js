import IMask from 'imask';
import masks from "../utils/masks";
import MensajeAlerta from '../utils/MensajeAlerta';
import { _drvImpresion } from '../print/drvImpresion';

/** 
 * Turnos - Show turno
 */

window.onload = function() {
    _setInputs();

    if (TURNO._cierre_fecha_hora == "") {
        TURNO._turno_id = "0";
    }

    const btnEnviaEmail = document.querySelector('#btnEnviaEmail');
    btnEnviaEmail.addEventListener('click', btn => {
        Promise.allSettled([ axios.get(TURNO._pathToEmail) ])
            .then(function (response) {
                if (response[0].status === 'fulfilled') {
                    MensajeAlerta('E-mail enviado con éxito !', 'success');
                } else alert('No se envió el email !');
            })
            .catch(function (error) {            // handle error
                alert('No se envió el email ! \n' + error);
        });
    });

    const btnImprime = document.querySelector('#btnImprime');
    btnImprime.addEventListener('click', e => {
        Promise.allSettled([ _drvImpresion('cierre_caja', _getParamsCierre()) ])
            .then(function (response) {
                if (response[0].status === 'fulfilled') {
                    MensajeAlerta('Ticket ya impreso !', 'success');
                } else alert('No se imprimió ticket !');
            })
            .catch(function (error) {            // handle error
                alert('No se imprimió ticket ! \n' + error);
        });
    });
};


const _setInputs = () => {
    IMask(document.getElementById('saldo_inicio'), masks.currency);
    IMask(document.getElementById('cierre_hora'), masks.hora);
    IMask(document.getElementById('venta_total'), masks.currency);
    IMask(document.getElementById('efectivo'), masks.currency);
    IMask(document.getElementById('tarjeta_credito'), masks.currency);
    IMask(document.getElementById('tarjeta_debito'), masks.currency);
    IMask(document.getElementById('cuenta_corriente'), masks.currency);
    IMask(document.getElementById('otros'), masks.currency);
    IMask(document.getElementById('egresos'), masks.currency);
    IMask(document.getElementById('ingresos'), masks.currency);
    IMask(document.getElementById('caja'), masks.currency);
    IMask(document.getElementById('arqueo'), masks.currency);
    IMask(document.getElementById('diferencia'), masks.currency)
};

const _getParamsCierre = () => {
    return {
        sucursal_id: TURNO._sucursal_id,
        cierre_id: TURNO._turno_id,
        impres: TURNO._impresora,
        env: TURNO._env,
    };
};