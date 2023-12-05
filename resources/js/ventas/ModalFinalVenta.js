//
// Clase para mostrar ventana modal de confirmación de venta
//

import UiLineaVenta from "./UiLineaVenta";
import { strToCurrency } from "../utils/solo-numeros";
import { englishToSpanish } from "../utils/transformFechas";

export default class ModalFinalVenta {
    constructor() {
        this.modal = document.querySelector('.modal');
    }

    setDatos() {   // Carga datos en modal
        const horaActual = new Date().toLocaleTimeString('en-GB', { hour: "numeric", minute: "numeric"});
        const fecha = englishToSpanish(_VENTA.fecha);
        _VENTA.hora = horaActual;

        document.querySelector('#spanCliente').textContent = this._getNombreCliente();
        document.querySelector('#spanFecha').textContent = fecha + " - " + _VENTA.hora;
        document.querySelector('#spanComprobante').textContent = this._getNroComprobante();
        document.querySelector('#spanFormaPago').textContent = this._getFormaPago();

        this._llenarBodyTablaVenta();
        this._importesPagoVueltoTotal();
    }

    muestraModal() {
        this.modal.classList.remove('hidden');
    }


    /**
     * Funciones privadas
     */
    _getNombreCliente() {
        let cliente = document.querySelector("#cliente").value;
        if (cliente == '' || cliente == null) {
            cliente = 'Consumidor final';
        }
        return cliente;
    }
    
    _getNroComprobante() {
        const numero = document.querySelector('#nro_comprobante_completo').value;

        return `Ticket ${numero}`;
    }

    _llenarBodyTablaVenta() {
        // lista de productos de la venta
        const uiLinea = new UiLineaVenta(document.querySelector('#bodyVentaModal'));
        uiLinea.resetBody();

        _VENTA.detalle.forEach(prod => {
            uiLinea.productoALinea(prod, true);
        });
    }

    /**
     * Cambia el formato de fecha yyyy-mm-dd, a dd/mm/yyyy
     * 
     * @param {string} fecha 
     * @return {string} 
     * */
    _formatFecha(fecha) {
        const arrFecha = fecha.split('-');
        return arrFecha[2] + '/' + arrFecha[1] + '/' + arrFecha[0];
    }

    /**
     * Escribe valores de Pago, vuelto y Total
     * 
     * @returns null
     */
    _importesPagoVueltoTotal() {
        document.querySelector('#pagoModal').textContent = 'Pago $ ' + strToCurrency(document.querySelector('#pagoContado').value);
        document.querySelector('#vueltoModal').textContent = 'Vuelto $ ' + strToCurrency(document.querySelector('#vuelto').value);
        document.querySelector('#totalModal').textContent = 'Total $ ' + strToCurrency(_VENTA.total);

        return null;
    }
    
    _getFormaPago() {
        let formaPago = '';

        switch (_VENTA.forma_pago) {
            case 0:
                formaPago = "Efectivo";
                break;
            case 1:
                formaPago = "Tarjeta de Débito";
                break;
            case 2:
                formaPago = "Tarjeta de Crédito";
                break;
            case 3:
                formaPago = "Transferencia";
                break;
            case 4:
                formaPago = "Cta. Cte.";
                break;
        }

        return formaPago;
    }

}