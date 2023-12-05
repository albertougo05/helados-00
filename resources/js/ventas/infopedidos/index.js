/**
 * index.js - ../ventas/infopedidos
 */

import { pedidoSeleccionado, cambiarBGLineaSel, anularComprobante } from './funcionesIndex';
import { _drvImpresion } from '../../print/drvImpresion';


// Botones
_PEDIDO._btnReimprimir = document.querySelector('#btnReimprimir');
_PEDIDO._btnAnular = document.querySelector('#btnAnular');

/**
 * Funcion cambiar estado botones
 */
const _cambiarEstadoBtns = estado => {
    //console.log('Estado:', estado)
    switch (estado) {
        case '0':
            _PEDIDO._btnReimprimir.disabled = true;
            _PEDIDO._btnAnular.disabled = true;        
            break;

        case '1':
            _PEDIDO._btnReimprimir.removeAttribute('disabled');
            _PEDIDO._btnAnular.removeAttribute('disabled');
            break;
    }

    return null;
}

const _getParamsTicket = () => {
    return {
        idcomp: _PEDIDO._idComprobSelec,
        idsuc: _PEDIDO._sucursal_id,
        iduser: _PEDIDO._usuario_id,
        turnosuc: _PEDIDO._turno_sucursal,
        impres: _PEDIDO._impresora[0].nombre,
        env: _PEDIDO._env,
    };
};

const _muestraSpinImprime = show => {
    const spinImprime = document.querySelector('#spinImprime');
    if (show) {
        spinImprime.classList.remove('hidden');
    } else {
        spinImprime.classList.add('hidden');
    }

     return null;
};

window.onload = function() {
    // Desabilitar botones
    _PEDIDO._btnReimprimir.disabled = true;
    _PEDIDO._btnAnular.disabled = true;

    // Click en línea de pedidos
    const lineasPedido = document.querySelectorAll('#bodyComprobs > tr');
    // Loop para dar evento click a cada linea
    lineasPedido.forEach(item => {
        item.addEventListener('click', function (e) {
            let tr = e.target.parentElement;

            if (tr.tagName === 'TD') {
                tr = tr.parentElement;
            }

            cambiarBGLineaSel(tr);
            _cambiarEstadoBtns(tr.dataset.estado);
            _PEDIDO._nroComprobSelec = parseInt(tr.dataset.nrocomp);
            _PEDIDO._idComprobSelec = parseInt(tr.dataset.idpedido);
            pedidoSeleccionado(tr.dataset.nrocomp);
        });
    });

    // Click en boton Reimprimir
    _PEDIDO._btnReimprimir.addEventListener('click', e => {
        if (_PEDIDO._impresora[0].nombre) {
            const parametros = _getParamsTicket();
            _muestraSpinImprime(true);

            _drvImpresion('ticket_vta', parametros)
                .then(function (response) {
                    //console.log('Impresion finalizada.', response);
                    _muestraSpinImprime(false);
                    return response;
                })
                .catch(function (error) {    // handle error
                    _muestraSpinImprime(false);
                    console.log('Error al imprimir', error);
            });
        } else {
            const url = _PEDIDO._pathImprimir + '?id=' + _PEDIDO._idComprobSelec;
            window.open(url, '_blank');
        }

        return null;
    });


    // Click en boton Anular
    //const btnAnular = document.querySelector('#btnAnular');
    _PEDIDO._btnAnular.addEventListener('click', function (e){
        e.preventDefault();

        if (_PEDIDO._nroComprobSelec > 0) {
            anularComprobante(_PEDIDO._nroComprobSelec);
        }
    });


};
