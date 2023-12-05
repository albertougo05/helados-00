/**
 * Servicio de impresion al crear Movimiento de Caja
 */
import { _drvImpresion } from '../print/drvImpresion';

const __formatFechaHora = fecha_or => {
    const arr1 = fecha_or.split(' ');
    const arr2 = arr1[0].split('-');

    return `${arr2[2]}-${arr2[1]}-${arr2[0]} ${arr1[1]}`;
};

const __getParams = () => {
    return {
        sucursal: CAJA._sucursal,
        movim_nro: CAJA._new_id,
        tipo_movim: CAJA._tipo_movim,
        turno_nro: CAJA._turno_nro,
        concepto: document.querySelector('#concepto').value,
        fecha_hora: __formatFechaHora(document.querySelector('#fecha_hora').value),
        usuario: CAJA._usuario_nombre,
        importe: document.querySelector('#importe_1').value,
        observac: document.querySelector('#observaciones').value,
        impres: CAJA._nombreImpresora,
        env: CAJA._env,
    };
}


export default function imprimeMovimCaja() {
    if (CAJA._nombreImpresora) {
        _drvImpresion('movim_caja', __getParams())
            .then(function (response) {
                return null;
            })
            .catch(function (error) {   // handle error
                alert('Error al imprimir ! \n' + error);
                return null;
        });
    }
}