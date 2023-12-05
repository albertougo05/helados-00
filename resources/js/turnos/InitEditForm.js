/**
 * Inicializa form Cierre de turnos (edit.js)
 */
import IMask from 'imask';
import masks from "../utils/masks";


export default class InitEditForm  {
    constructor() {
        this.inputs = undefined;
        this._setInputs();
    }

    getInputs() {
        return this.inputs;
    }

    _setInputs() {
        this.inputs = {
            SaldoInic: IMask(document.getElementById('saldo_inicio'), masks.currency),
            HoraCierre: document.getElementById('cierre_hora'),
            TarjCred: IMask(document.getElementById('tarjeta_credito'), masks.currency),
            TarjDebi: IMask(document.getElementById('tarjeta_debito'), masks.currency),
            CtaCte: IMask(document.getElementById('cuenta_corriente'), masks.currency),
            Otros: IMask(document.getElementById('otros'), masks.currency),
            Arqueo: IMask(document.getElementById('arqueo'), masks.currency),

            //Total: IMask(document.getElementById('venta_total'), masks.currency),
            //Efectivo: IMask(document.getElementById('efectivo'), masks.currency),
            //Egresos: IMask(document.getElementById('egresos'), masks.currency),
            //Ingresos: IMask(document.getElementById('ingresos'), masks.currency),
            //TotalVta: IMask(document.getElementById('total_venta'), masks.currency),
            //Diferencia: IMask(document.getElementById('diferencia'), masks.currency),
        }
    }
};