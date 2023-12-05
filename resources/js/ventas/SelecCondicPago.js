/**
 * Clase para manejar acciones al seleccionar Condición de pago
 * 
 */
import MensajeAlerta from "../utils/MensajeAlerta";


export default class SelecCondicPago {
    constructor(condicPago) {
        this.condicPago = condicPago;
        this.selFormasPago = document.querySelector('select#forma_pago_id');
        this.inputPagoContado = document.querySelector('input#pagoContado');
        this.lblFormaPago = document.querySelector('label#lblFormaPago');
    }

    deshabilitaBotones() {
        this.selFormasPago.setAttribute('disabled', true);      // Deshabilita select de Formas de pago
        this.inputPagoContado.setAttribute('disabled', true);   // Deshabilita ingreso pago manual
        return null;
    }

    accionesSegunCondicion() {
        const inputCliente = document.querySelector('input#cliente');
        const divBtnBorrarClie = document.querySelector('#divBtnBorrarClie');

        if (this.condicPago === 2 && _VENTA.cliente_id === 0) {
            MensajeAlerta("Debe seleccionar un cliente !");
            this.selFormasPago.value = 0;
            document.querySelector('input#cliente').focus();

            return null;
        }

        if (this.condicPago === 1 && _VENTA.cliente_id > 0) {
            MensajeAlerta("Había seleccionado un cliente de cta. cte !");
            _VENTA.cliente_id = 0;
            inputCliente.value = 'Consumidor final';
            inputCliente.focus();
            divBtnBorrarClie.classList.add('hidden');
            this.inputPagoContado.removeAttribute('disabled');

            return null;
        }

        if (this.condicPago === 2 ) {
            this.lblFormaPago.innerText = 'Cta. Cte.';
        }
    }
}