/**
 * movimSeleccionado.js
 * 
 * Acciones al ser seleccionado un comprobante
 * @param {int} id_movim
 */

import buscarDetalleMovim from "./buscarDetalleMovim";
import llenarTablaDetalle from "./llenarTablaDetalle";
import { vaciarBodyDetalle } from "./llenarTablaDetalle";


export default function movimSeleccionado(id_movim) {
    // Buscar detalle de movimiento seleccionado
    buscarDetalleMovim(id_movim)
        .then(data =>{
            // Vaciar tabla
            vaciarBodyDetalle();
            if (data.length > 0) {
                // Llenar tabla de detalle
                _INFO_MOVS_STOCK._detalleComp = data;
                llenarTablaDetalle(data);
            }
        })
        .catch(error => {
            console.log('Error:', error);
        });

    return null
}

