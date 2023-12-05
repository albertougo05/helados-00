/**
 * Acciones sobre tabla productos a stock
 */
import { strToCurrency } from "../utils/solo-numeros";


var uiLineaProducto = function() {

    var creaLinea = function(data) {
        const columnas = [
            "descripcion",
            "cantidad",
            "unidad_med",
            "total_unid",
            "trash"
        ];

        const tr = document.createElement("tr");
        tr.classList.add("text-gray-700", "text-sm", "hover:bg-gray-200");

        columnas.forEach((e) => {
            let td = document.createElement("td");

            switch (e) {
                case "descripcion":
                    td.classList.add("pl-4", "py-1", "text-left");
                    td.textContent = data.descripcion;
                    break;
                case "cantidad":
                    td.classList.add("text-right", "py-1", "pr-1");
                    td.textContent = strToCurrency(data.cantidad, false, 3);
                    break;
                case "unidad_med":
                    td.classList.add("text-center", "py-1", "pr-1");
                    td.textContent = data.unid_medida;
                    break;
                case "total_unid":
                    td.classList.add("text-right", "py-1", "pr-1");
                    td.textContent = _getTotalUnidades(data);
                    break;
                case "trash":
                    td = _creaBotonTrash(td, data.producto_id, data.idx);
                    break;
            }
            tr.appendChild(td);
        });

        return tr;
    },

    _getTotalUnidades = function (producto) {
        let total_unid = '';

        if (producto.unid_medida === 'UN') {
            total_unid = producto.cantidad;
    
        } else if (producto.unid_medida === 'BU') {
            total_unid = producto.unidades_x_bulto * producto.cantidad;
        }

        return total_unid; //strToCurrency(producto.cantidad, false, 2);
    },

    _creaBotonTrash = function (td, prod_id, idx) {
        td.classList.add("w-16", "flex", "justify-center", "hover:bg-gray-300", "cursor-pointer");
        td.dataset.idprod = prod_id;
        td.dataset.idx = idx; //_STOCK.productos_a_stock.length;

        const svg = _creaSvgTrash();
        td.onclick = _clickEnTrash;
        td.insertAdjacentHTML('afterbegin', svg);

        return td;
    },

    _creaSvgTrash = function() {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="rgba(239, 68, 68, 0.8)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`;
    };

    // Public members
    return {
        creaLinea: creaLinea,
    };
}();


const _clickEnTrash = function (e) {
        let elem = e.target;
        let tagName = elem.tagName;
    
        if (tagName === 'svg') {
            elem = elem.parentElement;
        } else if (tagName === 'path') {
            elem = elem.parentElement;
            elem = elem.parentElement;
        }

        const idx = parseInt(elem.dataset.idx);     // Busco por el indice

        if (_STOCK._esEdit) {
            // Cargo 0 en cantidad
            const indice = _STOCK.productos_a_stock.findIndex(el => el.idx === idx);
            _STOCK.productos_a_stock[indice].cantidad = 0;

        } else {
            // eliminar producto de la lista
            _STOCK.productos_a_stock = _STOCK.productos_a_stock.filter(el => el.idx !== idx);
        }

        const manejoTabla = new ManejoTablaProdStock();
        manejoTabla.rellenaTablaCompleta();
}


export default class ManejoTablaProdStock {
    constructor() {
        this.bodyProdStock = document.querySelector('#bodyProdStock');
    }

    producto_a_linea(prod) {
        const tr = uiLineaProducto.creaLinea(prod);
        this.bodyProdStock.appendChild(tr);
        this.bodyProdStock.scrollIntoView(false);

        return null;
    }

    _resetBody() {
        this.bodyProdStock.innerHTML = "";

        return null;
    }

    rellenaTablaCompleta() {
        const este = this;
        this._resetBody();
        _STOCK.productos_a_stock.map(elem => {
            if (elem.cantidad > 0) 
                este.producto_a_linea(elem);
        });
    }

}
