/**
 * 
 * Funciones para guardar datos de cierre de turnos/caja
 * 
 */

import axios from "axios";
import { fechaActual } from '../utils/transformFechas';


const guardarIdsCierre = async () => {
    const options = {
        params: {
            cierre_id: TURNO._turno_sucursal,
            sucursal_id: TURNO._sucursal_id,
            caja_nro: TURNO._caja_nro,
        }
    };

    try {
        const response = await axios.get(TURNO._pathCierraTurno, options);
        //console.log('Guardar ids Ok !', response.data[0]);
        return true;
    } catch (error) {
        console.log('Error:', error);
        return false;
    }
};

const actualizarFechaCierreTurno = () => {
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

export { guardarIdsCierre, actualizarFechaCierreTurno };