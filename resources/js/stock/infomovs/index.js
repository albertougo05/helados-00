/**
 * index.js
 * 
 * Informe movimientos de caja
 */

import MensajeAlerta from '../../utils/MensajeAlerta';
import movimSeleccionado from './movimSeleccionado';
import { _drvImpresion } from '../../print/drvImpresion';


if (_INFO_MOVS_STOCK._mensaje_error.length > 0) {

    MensajeAlerta(_INFO_MOVS_STOCK._mensaje_error, 'error');
}

// Botones
_INFO_MOVS_STOCK._btnReimprimir = document.querySelector('#btnReimprimir');
_INFO_MOVS_STOCK._btnEditar = document.querySelector('#btnEditar');

/**
 * Funcion cambiar estado botones
 */
const _cambiarEstadoBtns = estado => {
    //console.log('Estado:', estado)
    switch (estado) {
        case '0':
            _INFO_MOVS_STOCK._btnReimprimir.disabled = true;
            _INFO_MOVS_STOCK._btnEditar.disabled = true;        
            break;
        default:
            _INFO_MOVS_STOCK._btnReimprimir.removeAttribute('disabled');
            _INFO_MOVS_STOCK._btnEditar.removeAttribute('disabled');
            break;
    }

    return null;
}

function _resetBGTodasLasLineas() {
    const lineasTabla = document.querySelectorAll('#bodyComprobs > tr');
    lineasTabla.forEach(item => {
        item.classList.remove('bg-green-300');
        item.classList.add('bg-white'); 
    });

    return null;
}

/**
 * Cambiar el backgroud de linea seleccionada
 * 
 * @param {html element} tr
 */
 function _cambiarBGLineaSel(tr) {
    _resetBGTodasLasLineas();
    tr.classList.remove('bg-white');
    tr.classList.add('bg-green-300');

    return null;
}

function __setEventosModal() {
    const elemCloseModal = document.querySelectorAll('.close-modal'),
          modalEdit  = document.querySelector('#modalEditar');;

    elemCloseModal.forEach(function (elem) {
        elem.addEventListener('click', function () {
          modalEdit.classList.add('hidden');
        });
      });

    
};

function _getParamsIngStk() {
    return {
        comprob_id: _INFO_MOVS_STOCK._idComprobSelec,
        sucursal_id: _INFO_MOVS_STOCK._sucursal_id,
        impres: _INFO_MOVS_STOCK._nombreImpresora,
        env: _INFO_MOVS_STOCK._env,
    };
};

window.onload = function() {
    // Desabilitar botones
    _INFO_MOVS_STOCK._btnReimprimir.disabled = true;
    _INFO_MOVS_STOCK._btnEditar.disabled = true;
    __setEventosModal();    // Establecer eventos de modal para editar 

    // Click en línea de pedidos
    const lineasPedido = document.querySelectorAll('#bodyComprobs > tr');
    // Loop para dar evento click a cada linea
    lineasPedido.forEach(item => {
        item.addEventListener('click', function (e) {
           let tr = e.target.parentElement;

           if (tr.tagName === 'TD') {
               tr = tr.parentElement;
           }

           _cambiarBGLineaSel(tr);
           _cambiarEstadoBtns(tr.dataset.estado);
           _INFO_MOVS_STOCK._idComprobSelec = parseInt(tr.dataset.id_comp);
           _INFO_MOVS_STOCK._nroComprobSelec = parseInt(tr.dataset.nro_comp);
           _INFO_MOVS_STOCK._fechaHora = tr.dataset.fecha_hora;
           _INFO_MOVS_STOCK._descrip = tr.dataset.descripcion;
           movimSeleccionado(tr.dataset.id_comp);
        });
    });

    _INFO_MOVS_STOCK._btnReimprimir.addEventListener('click', e => {
        let url = _INFO_MOVS_STOCK._pathImprime + '/' + _INFO_MOVS_STOCK._idComprobSelec;

        if (_INFO_MOVS_STOCK._nombreImpresora) {
            _drvImpresion('ingreso_stock', _getParamsIngStk())
                .then(function (response) {
                    return null;
                })
                .catch(function (error) {   // handle error
                    alert('Error al imprimir ! \n' + error);
                    return null;
            });
        } else {
            window.open(url, '_blank');
        }

        return null;
    });

    _INFO_MOVS_STOCK._btnEditar.addEventListener('click', e => {
        const comprobSelecId = '/' + _INFO_MOVS_STOCK._idComprobSelec + '/';
        let url = _INFO_MOVS_STOCK._pathEditarComprob;
        url = url.replace('/0/', comprobSelecId);

        if (_INFO_MOVS_STOCK._idComprobSelec > 0) {
            location.assign(url);
        }

        return null;
    });

}