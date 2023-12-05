/** 
 * Manejo de combo Formas de Pago
 * 
 */

import SelecCondicPago from "./SelecCondicPago";


export default class FormasPago {
    constructor(formaSelec, inputPagoContado, inputVuelto) {
        this.formaSelec = parseInt(formaSelec);
        this.inputPagoContado = inputPagoContado;
        this.inputVuelto = inputVuelto;
        this.divVuelto = document.querySelector('div#div-vuelto');
        this.lblFormaPago = document.querySelector('#lblFormaPago');
        // otras formas pago...
    }

    inicio() {
        if (this.formaSelec != 4) {     // Si no es Cta.Cte...first-letter:
            this._esconderDivs();
        }

        switch (this.formaSelec) {
            case 0: // Efectivo
                this.lblFormaPago.innerText = 'Pago efectivo';
                this._mostrarVuelto();
                this._habilitoInputPago();
                const selCondicPagoEf = new SelecCondicPago(1);
                selCondicPagoEf.accionesSegunCondicion();
                break;
            case 1: // Debito
                this.lblFormaPago.innerText = 'Pago c/débito';
                break;
            case 2: // Tarjeta
                this.lblFormaPago.innerText = 'Pago c/tarjeta';
                break;
            case 3: // Transferencia
                this.lblFormaPago.innerText = 'Pago c/tranf.';
                break;
            case 4:  // Cta. Cte.
                //this.lblFormaPago.innerText = 'Cta. Cte.';
                const selCondicPagoCta = new SelecCondicPago(2);
                selCondicPagoCta.accionesSegunCondicion();
                break;
        }

        if (this.formaSelec > 0) {
            this._deshabilitoInputPago();
        }

        this._actualizarInputs();
        // Actualizo forma de pago global
        _VENTA.forma_pago = this.formaSelec;

        return null;
    }

    _esconderDivs() {
        this.divVuelto.classList.add('hidden');
    }

    _mostrarVuelto() {
        this.divVuelto.classList.remove('hidden');
    }

    _actualizarInputs() {
        if (_VENTA.total > 0) {
            this.inputPagoContado.typedValue = _VENTA.total;
            this.inputVuelto.value = "";
        }
    }

    _deshabilitoInputPago() {
        const inputPago = document.getElementById('pagoContado');
        inputPago.setAttribute("disabled", "");
    }

    _habilitoInputPago() {
        const inputPago = document.getElementById('pagoContado');
        inputPago.disabled = false;
    }
}