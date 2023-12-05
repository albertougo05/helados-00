/**
 * 
 * Eventos Modal Confirma Venta
 * 
 */

import { strToFloat } from "../utils/solo-numeros";
import MensajeAlerta, { mensajeErrorEntendido } from "../utils/MensajeAlerta";
import ImprimeTicketVenta from "./ImprimeTicketVenta";


export default class ConfirmaVenta {
    constructor() {
        this.modal  = document.querySelector('.modal');
        this.comprobante = {
            sucursal_id: _VENTA.sucursal_id,
            usuario_id: _VENTA.usuario_id,
            caja_nro: _VENTA.caja_nro,
            nro_comprobante: _VENTA.nro_comprobante,
            turno_id: _VENTA.turno_id,
            turno_sucursal: _VENTA.turno_sucursal,
            tipo_comprobante_id: 1,
            fecha: _VENTA.fecha,
            hora: _VENTA.hora,
            firma: document.querySelector('#cliente').value,
            cliente_id: _VENTA.cliente_id,
            condicion_iva: 'CF',
            total: _VENTA.total
        }
    }

    guardarDatosVenta() {
        // store datos del comprobante
        this._storeComprobante()
            .then(data => {
                //console.log('Then, _storeComprobante():', data)
                return null;
        });

        return null;
    }

    escondeModal() {
        this.modal.classList.add('hidden');
        //console.log('Datos comprobante:', this.comprobante);
        spinEspera();   // Activa spin de espera

        return null;
    }

    async _getNroComprobante() {
        const parametro = '?sucursal_id=' + this.comprobante.sucursal_id;

        try {
            const resp = await axios(_VENTA.pathNroComprobante + parametro);
            //console.log('Dentro de _getNroComprobante...', resp.data);
            return resp.data.numero;
        } catch (error) {
            //console.log('Error al buscar nro comprobante...');
            return null;
        }
    }

    async _storeComprobante() {
        let respuest = 'ok';
        const nroComp = await this._getNroComprobante();
        if (nroComp) {
            this.comprobante.nro_comprobante = nroComp;
            _VENTA.nro_comprobante = nroComp;
        } else {
            mensajeErrorEntendido('No se pudo obtener número de comprobante. \n\r', e);

            return null;
        }

        const data = this.comprobante;

        await axios({
            method: 'post',
            url: _VENTA.pathVentasComprobante,
            data: data
        })
        .then(resp => {
            if (resp.data.estado == 'ok') {
                // store productos del comprobante (devuelve el ID del comprobante)
                _VENTA.comprobante_id_guardado = resp.data.comprobante_id;
                //console.log('Comprobante id:', resp.data.comprobante_id);
                this._storeDetalleProductos(_VENTA.nro_comprobante, resp.data.comprobante_id)
                    .then(resp => {
                        if (resp === 'ok') {
                            // store productos de productos con receta
                            this._storeDetalleProdsReceta()
                                .then(res => {
                                    // store movimiento de caja
                                    this._storeDetalleCaja()
                                        .then(res => {
                                            const imprimeTicketVta = new ImprimeTicketVenta();
                                            imprimeTicketVta.inicio();

                                            setTimeout(function () {    // Espero 3 segundos para recarga la pagina
                                                location.reload();
                                            }, 4000);
                                    });
                            });
                        }
                    });
            } else {
                mensajeErrorEntendido('No se pudo guardar el comprobante. \n\r', e);
            }
        })
        .catch(e => {
            mensajeErrorEntendido('No se pudo guardar el comprobante. \n\r', e);
            respuest = 'error';
        });

        return respuest;
    }

    async _storeDetalleProductos(nro_comprob, comprob_id) {
        const data = this._setDetalleVenta(nro_comprob, comprob_id);
        let resp = 'ok';
        //console.log('Detalle productos venta:', data);
        await axios({
            method: 'post',
            url: _VENTA.pathDetalleComprobVentaStore,
            data: data
        })
        .then(resp => {
            if (resp.data.estado == 'ok') {
                //console.log('Guardado detalle, ok!');
            }
         })
        .catch(e => {
            mensajeErrorEntendido('No se guardó detalle de comprobante. <br>', e);
            resp = 'error';
        });

        return resp;
    }

    async _storeDetalleProdsReceta() {
        // comprobar si hay productos con receta...
        const prodsConReceta = _VENTA.detalle.filter(elem => elem.con_receta === 1);
        let result = 'error';

        if (prodsConReceta.length > 0) {
            await axios({
                method: 'post',
                url: _VENTA.pathDetalleComprobReceta,
                data: prodsConReceta
            })
            .then(resp => {
                if (resp.data.estado == 'ok') {
                    result = 'ok';
                }
             })
            .catch(e => {
                mensajeErrorEntendido('No se guardó detalle de receta.\n\r', e);
            });
        }

        return result;
    }

    async _storeDetalleCaja() {
        const data = this._getDetalleCaja();
        let result = 'error';
        //console.log('Guardar movimiento de caja:', data);
        await axios({
            method: 'post',
            url: _VENTA.pathCajaMovimiento,
            data: data
        })
        .then(resp => {
            if (resp.data.estado == 'ok') {
                result = 'ok';
            }
        })
        .catch(e => {
            mensajeErrorEntendido('No se guardó detalle de caja.\n\r', e);
        });

        return result;
    }

    _setDetalleVenta(nro_comprobante, comprobante_id) {
        return _VENTA.detalle.map(elem => {
            delete elem.id;
            delete elem.unid_producto;
            delete elem.promo_opciones;
            elem.nro_comprobante = nro_comprobante;
            elem.comprobante_id = comprobante_id;
            elem.hora = _VENTA.hora;
            elem.peso_helado = elem.peso_helado * elem.cantidad;    // Sumo total peso en helado

            return elem;
        });
    }

    /**
     * Actualiza datos para guardar detalle da caja en tabla 'caja_movimientos'
     * 
     * @returns array
     */
    _getDetalleCaja() {
        _VENTA.detalle_caja.tipo_comprobante_id = 1;
        _VENTA.detalle_caja.fecha_hora =  _VENTA.fecha + ' ' + _VENTA.hora;
        _VENTA.detalle_caja.fecha_registro = _VENTA.fecha;
        _VENTA.detalle_caja.nro_comprobante = _VENTA.nro_comprobante;
        _VENTA.detalle_caja.importe = _VENTA.total;
        _VENTA.detalle_caja.vuelto = strToFloat(document.querySelector('#vuelto').value);

        switch (_VENTA.forma_pago) {
            case 0:
                _VENTA.detalle_caja.total_efectivo = _VENTA.total;
                break;
            case 1:
                _VENTA.detalle_caja.total_debito = _VENTA.total;
                _VENTA.detalle_caja.forma_pago_id = 5;
                _VENTA.detalle_caja.concepto = 'Vta. T. Débito';
                break;
            case 2:
                _VENTA.detalle_caja.total_tarjeta = _VENTA.total;
                _VENTA.detalle_caja.forma_pago_id = 4;
                _VENTA.detalle_caja.concepto = 'Vta. T. Crédito';
                break;
            case 3:
                _VENTA.detalle_caja.total_transfer = _VENTA.total;
                _VENTA.detalle_caja.forma_pago_id = 3;
                _VENTA.detalle_caja.concepto = 'Vta. Transf.';
                break;
            case 4:
                _VENTA.detalle_caja.total_valores = _VENTA.total;
                _VENTA.detalle_caja.forma_pago_id = 3;
                _VENTA.detalle_caja.concepto = 'Vta. c/valores';
                break;
            case 5:
                _VENTA.detalle_caja.cuenta_corriente = _VENTA.total;
                _VENTA.detalle_caja.forma_pago_id = 2;
                _VENTA.detalle_caja.concepto = 'Vta. a Cta.Cte';
                break;
            case 6:
                _VENTA.detalle_caja.total_otros = _VENTA.total;
                _VENTA.detalle_caja.forma_pago_id = 3;
                break;
        }
        _VENTA.detalle_caja.firma_id = _VENTA.cliente_id;

        return _VENTA.detalle_caja;
    }
}


function controlConfirmaVenta() {
    const pago = strToFloat(document.querySelector('#pagoContado').value);
    const vuelto = strToFloat(document.querySelector('#vuelto').value);
    const condicPago = parseInt(document.querySelector('#forma_pago_id').value);

    if (condicPago === 4) { // Si condicion de pago es Cta Cte pasa...
        return true;
    }

    if (vuelto < 0) {
        MensajeAlerta("Importe de pago insuficiente!");
        document.querySelector('#pagoContado').focus();
        return false;
    }
    if (pago == 0) {
        MensajeAlerta("Debe ingresar importe de pago !");
        document.querySelector('#pagoContado').focus();
        return false;
    }

    return true;
}


function spinEspera() {
    const modalSpin = document.querySelector('.modal-espera');
    modalSpin.classList.toggle('hidden');
}

export { controlConfirmaVenta, spinEspera };