/**
 * ../imprime/cierre.js
 */
import { _drvImpresion } from '../../print/drvImpresion';
import axios from 'axios';


const _getParamsCierreCaja = () => {
    return {
        sucursal_id: _CIERRE.sucursal_id,
        cierre_id: _CIERRE.cierre_id,
        impres: _CIERRE.impresora[0].nombre,
        env: _CIERRE.env,
    };
};

if (_CIERRE.impresora[0].nombre) {     // SI HAY NOMBRE IMPRESORA IMPRIME DIRECTO

    Promise.allSettled([
            _drvImpresion('cierre_caja', _getParamsCierreCaja()),
            axios.get(_CIERRE.pathToEmail)
        ])
        .then(function (response) {
            //window.location.assign(_CIERRE.pathExit);
        })
        .catch(function (error) {            // handle error
            alert('Error al imprimir ! \n' + error);
            //console.log('Error al imprimir:', error);
        })
        .finally(function () {
            setTimeout(function () {
                window.location.assign(_CIERRE.pathExit);
            }, 5000);
    });

    // _drvImpresion('cierre_caja', _getParamsCierreCaja())
    //     .then(function (response) {
    //         //console.log('Impresion finalizada. (Sale y vuelve al index)', response);
    //         window.location.assign(_CIERRE.pathExit);
    //         return null;
    //     })
    //     .catch(function (error) {
    //         // handle error
    //         alert('Error al imprimir ! \n' + error);
    //         return null;
    // });

} else {
    // Si no hay impresora, NO IMPRIME NADA !! Y SALE !
    window.location.assign(_CIERRE.pathExit);
}
