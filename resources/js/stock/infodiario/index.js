import IMask from 'imask';
import masks from "../../utils/masks";
import { fechaActual, horaActual } from '../../utils/transformFechas';
import { _drvImpresion } from '../../print/drvImpresion';


const _convertFecha = fecha => {
    const arr = fecha.split('-');

    return arr[2] + '-' + arr[1] + '-' + arr[0];
};

const _getParamsInfo = (hora) => {
    return {
        sucursal_id: _INFO_DIARIO._sucursal_id,
        sucursal: _INFO_DIARIO._sucursal,
        fecha: _convertFecha(document.querySelector('#fecha_toma_stock').value),
        hora: hora,
        impres: _INFO_DIARIO._nombreImpresora,
        env: _INFO_DIARIO._env,
    };
};

const _showSpinImprime = (show) => {
    const spinImp = document.querySelector('#spinImprime');
    if (show) {
        spinImp.classList.remove('hidden');
    } else {
        spinImp.classList.add('hidden');
    }

    return null;
};


//
// Despues de cargar la página...
//
window.onload = function() {
    // Inicializar form
    const inputHora = IMask(document.getElementById('hora_toma_stock'), masks.hora);
    inputHora.typedValue = horaActual();
    document.getElementById('fecha_toma_stock').value = fechaActual('en');

    const btnImprimir = document.querySelector('#btnImprimir');
    btnImprimir.addEventListener('click', function (e) {

        if (_INFO_DIARIO._nombreImpresora) {     // SI HAY NOMBRE IMPRESORA IMPRIME DIRECTO
            e.target.disabled = true;
            _showSpinImprime(true);
            _drvImpresion('info_diario', _getParamsInfo(inputHora.value))
                .then(function (response) {
                    _showSpinImprime(false);
                    e.target.disabled = false;
                    return null;
                })
                .catch(function (error) {   // handle error
                    alert('Error al imprimir ! \n' + error);
                    return null;
            });
        }
        // Si no hay impresora, NADA HACE !
    });

};