/**
 * funcionesIndex.js
 * 
 * Funciones varias, exportadas
 */

import axios from "axios";
import Swal from "sweetalert2";
import llenarTablaDetalle from "./llenarTablaDetalle";
import { vaciarBodyDetalle } from "./llenarTablaDetalle";


/**
 * Acciones al ser seleccionado un comprobante
 * 
 * @param {int} id_pedido 
 */
function pedidoSeleccionado(id_pedido) {
    // Buscar detalle de pedido seleccionado
    _buscarDetalle(id_pedido)
        .then(data =>{
            // Vaciar tabla
            vaciarBodyDetalle();
            if (data.length > 0) {
                // Convertir a int y float a strings que vienen de DB
                _PEDIDO._detalleComp = data.map(el => {
                    el.cantidad = parseInt(el.cantidad);
                    el.precio_unitario = parseFloat(el.precio_unitario);
                    el.subtotal = parseFloat(el.subtotal);

                    return el;
                });
                //llenarTablaDetalle(data);
                llenarTablaDetalle(_PEDIDO._detalleComp);
            }
        })
        .catch(error => {
            console.log('Error:', error);
        });

    return null
};

/**
 * Cambiar el backgroud de linea seleccionada
 * 
 * @param {html element} tr
 */
function cambiarBGLineaSel(tr) {
    _resetBGTodasLasLineas();
    tr.classList.remove('bg-white');
    tr.classList.add('bg-green-300');

    return null;
};

function anularComprobante(nroComp) {
    Swal.fire({
        title: '¿ Está seguro ?',
        text: `Quiere anular el pedido: ${nroComp}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Si, anular',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            // llamar a funcion que anula pedido (comprobante)
            _anularPedidoDB(nroComp);
        }
    });
};


/**
 * Buscar Detalle de pedido seleccionado (private)
 */
async function _buscarDetalle(id_pedido) {
    let url = _PEDIDO._pathDetalleComprob.slice(0, -1);
    url += id_pedido + '?suc_id=' + _PEDIDO._sucursal_id;
    let res = await axios.get(url);
    let data = res.data;

    return data.detalle;
};

function _resetBGTodasLasLineas() {
    const lineasTabla = document.querySelectorAll('#bodyComprobs > tr');
    lineasTabla.forEach(item => {
        item.classList.remove('bg-green-300');
        item.classList.add('bg-white'); 
    });

    return null;
};

/**
 * Anular pedido seleccionado (private)
 */
function _anularPedidoDB(nro_comp) {
    const pedido = _PEDIDO._comprobantes.find(elem => elem.nro_comprobante == nro_comp);
    const idComp = pedido.id;

    Promise.all([
        _anulaComprobante(idComp),
        _anulaDetalleVenta(idComp),
        _anulaDetalleReceta(idComp),
        _anulaMovimientoCaja(nro_comp)
    ])
    .then(data => {
        const comprobante  = data[0],
             detalleVenta  = data[1],
             detalleReceta = data[2];

        //console.log('Resultados:', comprobante.data, detalleVenta.data, detalleReceta.data);

        Swal.fire({
            title: 'Anulado!',
            text: `Pedido: ${nro_comp} ha sido anulado!`,
            icon: 'success',
            confirmButtonText: 'Ok',
        }).then((result) => {
            // Recarga la página
            location.reload();
        });
    });

    return null;
}

    /** 
     * Funciones privadas de anulacion comprovante venta y detalles
     */
    const _anulaComprobante = id => {
        let url = _PEDIDO._pathAnularComprob.slice(0, -1);
        url += id;
        return axios.get(url);
    };

    const _anulaDetalleVenta = id => {
        let url = _PEDIDO._pathAnulaDetalleVenta.slice(0, -1);
        url += id;
        return axios.get(url);
    };

    const _anulaDetalleReceta = id => {
        let url = _PEDIDO._pathAnulaDetalleReceta.slice(0, -1);
        url += id;
        return axios.get(url);
    };

    const _anulaMovimientoCaja = nroComp => {
        let url = _PEDIDO._pathAnulaMovCaja.slice(0, -1);
        url += nroComp;
        return axios.get(url);
    };

export { pedidoSeleccionado, cambiarBGLineaSel, anularComprobante };