/**
 * Controla campos de Pago vuelto y total
 */

import { strToFloat, strToCurrency } from "../utils/solo-numeros";


export default class PagoVueltoTotal {
    constructor() {
        this.inputVuelto = document.querySelector('#vuelto');
        this.inputPago = document.querySelector('#pagoContado');
    }

    ingresoPago(valor) {
        const importe = strToFloat(valor);

        if (valor === '' || _VENTA.total === 0) {
            this.inputVuelto.value = '';
            return null;
        } else if (importe < _VENTA.total) {
            this.inputVuelto.value = strToCurrency(importe - _VENTA.total);
            this.inputVuelto.classList.add('text-red-500');
            this.inputPago.classList.add('text-red-500');
            return null;
        } else if (importe => _VENTA.total) {
            const vuelto = importe - _VENTA.total;
            this.inputVuelto.value = strToCurrency(vuelto);
            this.inputVuelto.classList.remove('text-red-500');
            this.inputPago.classList.remove('text-red-500');
        } else this.inputVuelto.value = 0;
    }

    actualizoVuelto(total) {
        const pago = strToFloat(this.inputPago.value);

        if (_VENTA.forma_pago > 0) { // Si forma pago no es contado
            this.inputVuelto.value = "";
            _VENTA.inputPagoContado.typedValue = total;

            return null;
        }

        if (pago == 0) return null;

        if (pago < total) {     // Pongo el importe en rojo
            this.inputVuelto.value = strToCurrency(pago - total);
            this.inputVuelto.classList.add('text-red-500');
            this.inputPago.classList.add('text-red-500');
        } else {
            this.inputVuelto.value = strToCurrency(pago - total);
            this.inputVuelto.classList.remove('text-red-500');
            this.inputPago.classList.remove('text-red-500');
        }
    }

}