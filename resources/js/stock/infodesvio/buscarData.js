/**
 * Buscar data del informe desvio
 */
import MensajeAlerta from '../../utils/MensajeAlerta';
import llenarTablaInfo from './llenaTablaInfo';


const buscarData = (btnGenerar) => {
    // enviar parametros para busqueda
    const desde = document.querySelector('#selPeriodoDesde').value;
    const hasta = document.querySelector('#selPeriodoHasta').value;
    const divSpin = document.querySelector('#spinCargando');

    if (desde === hasta) {
        MensajeAlerta('Debe seleccionar períodos diferentes !');

        return null;
    }

    btnGenerar.disabled = true;     // Deshabilitar boton hasta cargar pagina
    divSpin.classList.remove('hidden');

    _axiosData(desde, hasta)
        .then(datos => {
            //console.log(datos.data);
            llenarTablaInfo(datos.data);
            divSpin.classList.add('hidden');
            btnGenerar.disabled = false; 
        })
        .catch(err => {
            MensajeAlerta('Error buscando datos desvio !!');
            console.log('Error buscando datos:', err);
        });

    return null;
}


/**
 * FUNCIONES PRIVADAS
 */
const _axiosData = async (desde, hasta) => {
    const params = {
        params: {
            desde: desde,
            hasta: hasta,
        }
    };

    try {
        const response = await axios.get(_DESVIO._pathGetData, params);

        return response.data;

    } catch (error) {
        console.error(error);

        return error;
    }
};


export default buscarData;