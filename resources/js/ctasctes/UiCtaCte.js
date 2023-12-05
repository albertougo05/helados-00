/**
 * UiCtaCte.js
 * 
 * Clase que construye la lista de cuenta corriente.
 * 
 */
import { strToCurrency } from '../utils/solo-numeros';


class UiCtaCte {
    constructor() {
        this.bodyDataTable = document.querySelector('#bodyDataCtaCte');
        this.total = 0;
    }

    llenarTabla(data) {
        this._vaciarBody();
        this._loopData(data);
        this._setTotal();
    }


    _vaciarBody() {
        this.bodyDataTable.innerHTML = "";

        return null;
    }

    _loopData(data) {
        const estaClase = this;

        data.forEach(elem => {
            const tr = estaClase._setLinea(elem);
            estaClase.bodyDataTable.appendChild(tr);
            estaClase.total += elem.importe;
        });

    }

    _setLinea(data) {
        let tr = document.createElement("tr");
        tr.dataset.idprod = data.id;
        tr.classList.add("text-gray-700", "text-sm", "hover:bg-gray-200", "cursor-pointer");

        let tdFecha = document.createElement("td");
        tdFecha.classList.add("text-center", "pl-2", "whitespace-normal");
        tdFecha.textContent = data.fecha_registro;
        let tdHora = document.createElement("td");
        tdHora.classList.add("text-center", "pl-2", "whitespace-normal");
        tdHora.textContent = data.hora;
        let tdNro = document.createElement("td");
        tdNro.classList.add("text-center", "pl-2", "whitespace-normal");
        tdNro.textContent = data.nro_comprobante;
        let tdImport = document.createElement("td");
        tdImport.classList.add("text-right", "pr-4");
        tdImport.textContent = strToCurrency(data.importe);

        tr.appendChild(tdFecha);
        tr.appendChild(tdHora);
        tr.appendChild(tdNro);
        tr.appendChild(tdImport);

        return tr;
    }

    _setTotal() {
        const spTotal = document.querySelector('#spTotal');
        spTotal.textContent = strToCurrency(this.total);

        return null;
    }
}


export default UiCtaCte;