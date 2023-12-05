/**
 * Imprime ticket venta (confirma la venta)
 * y guarda datos del comprobante de venta
 */
import { _drvImpresion } from '../print/drvImpresion';
import MensajeAlerta from '../utils/MensajeAlerta';


export default class ImprimeTicketVenta {
    inicio() {
        this._muestraPantallaImprimir();

        return null;
    }

    _muestraPantallaImprimir() {
        if (_VENTA.impresora[0].nombre) {     // SI HAY NOMBRE IMPRESORA IMPRIME DIRECTO
            const parametros = this._getParamsTicketVta();

            _drvImpresion('ticket_vta', parametros)
                .then(function (response) {
                    //console.log('Impresion finalizada.', response);
                    return response;
                })
                .catch(function (error) {       // handle error
                    MensajeAlerta('Error al imprimir !\n Intente mas tarde.');
            });
        } else {
            const url = _VENTA.pathImprimeTicket + '?id=' + _VENTA.comprobante_id_guardado;
            window.open(url, '_blank'); // Abre pestaña del ticket
        }

        return null;
    }

    _getParamsTicketVta() {
        return {
            idcomp: _VENTA.comprobante_id_guardado,
            idsuc: _VENTA.sucursal_id,
            iduser: _VENTA.usuario_id,
            turnosuc: _VENTA.turno_sucursal,
            impres: _VENTA.impresora[0].nombre,
            env: _VENTA.env,
        };
    }

}