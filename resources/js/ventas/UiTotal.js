/**
 * Manejo del total de la venta
 */
import { strToCurrency } from "../utils/solo-numeros";
import PagoVueltoTotal from "./PagoVueltoTotal";

export default class UiTotal {
    setTotal() {
        const pTotal = document.querySelector('#total');

        pTotal.textContent = "Total $ " + strToCurrency(this.calculoTotal());
    }

    calculoTotal() {
        let total = 0;
        _VENTA.detalle.forEach(prod => {
            total += parseFloat(prod.subtotal);
        });

        _VENTA.total = total;

        const pagoVueltoTotal = new PagoVueltoTotal();
        pagoVueltoTotal.actualizoVuelto(total);

        //return total.toFixed(2);
        return total;
    }
}