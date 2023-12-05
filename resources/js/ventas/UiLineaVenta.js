/**
 * Clase para ingreso linea de producto a tabla venta
 */
import { strToCurrency } from "../utils/solo-numeros";
import UiTotal from "./UiTotal";


const _clickEnLineaVenta = function (e) {
    //const elem = e.target.parentElement;
    let elem = e.target;
    let tagName = elem.tagName;

    if (tagName === 'svg') {
        elem = elem.parentElement;
    } else if (tagName === 'path') {
        elem = elem.parentElement;
        elem = elem.parentElement;
    }
    
    const idprod = elem.dataset.idprod;
    const idx = _VENTA.detalle.findIndex(e => e.id == idprod);

    console.log('Id prod:', idprod)

    const producto = _VENTA.detalle[idx];

    if (producto.cantidad == 1) {
        _VENTA.detalle.splice(idx, 1);        // Quito el productos de la lista

        if(producto.promo_opciones) {       // Si tiene opciones, es promo y hay que borrar las opciones
            console.log('Producto a borrar:', producto)
            const arrCodigosOpcs = producto.promo_opciones.split(' ');
            arrCodigosOpcs.forEach(elem => {
                let indx = _VENTA.detalle.findIndex(e => e.codigo == elem);
                _VENTA.detalle.splice(indx, 1);        // Quito producto promo de la lista
            });
        }
    } else {
        producto.cantidad -= 1;
        producto.subtotal = parseFloat(producto.precio_unitario) * producto.cantidad;
    }

    // Refresco la lista
     const uiLinea = new UiLineaVenta(document.querySelector('#bodyVenta'));
     uiLinea.resetBody();

    _VENTA.detalle.forEach(prod => {
        uiLinea.productoALinea(prod);
    });

    const uiTotal = new UiTotal();    // Actualizo el total
    uiTotal.setTotal();

    if (_VENTA.detalle.length == 0) {
        // Desactivo el boton confirmar venta
        document.querySelector('#btnConfirmaVenta').disabled = true;
    }

    return null;
}


export default class UiLineaVenta {
    constructor(bodyTabla) {
        this.bodyTabla = bodyTabla;
    }

    resetBody() {
        this.bodyTabla.innerHTML = "";

        return null;
    }

    productoALinea(data, esModal = false) {
        const orden = [
            "descripcion",
            "precio",
            "cantidad",
            "total",
            "trash"
        ];

        let esTrashModal = false,
            esOpcionPromo = false;

        if (data.promo_opciones.includes(data.codigo)) {
            esModal = esOpcionPromo = true;
        }

        const tr = document.createElement("tr");
        tr.classList.add("text-gray-700");
        if (esOpcionPromo) {
            tr.classList.add("text-xs", "bg-yellow-100");
        } else {
            tr.classList.add("text-sm");
        }
        if (esModal) {
            if (!esOpcionPromo) {
                tr.classList.add("hover:bg-gray-100");
            }
        }

        orden.forEach((e) => {
            let td = document.createElement("td");

            switch (e) {
                case "descripcion":
                    td.classList.add("pl-2", "py-1", "text-left");
                    td.textContent = data.descripcion;
                    break;
                case "precio":
                    td.classList.add("text-right", "py-1", "pr-1");
                    td.textContent = strToCurrency(data.precio_unitario);
                    break;
                case "cantidad":
                    td.classList.add("text-right", "py-1", "pr-1");
                    td.textContent = data.cantidad;
                    break;
                case "total":
                    td.classList.add("text-right", "py-1", "pr-1");
                    td.textContent = strToCurrency(data.subtotal);
                    break;
                case "trash":
                    if (!esModal) {
                        td = this._creaBotonTrash(td, esModal, data.id);
                    } else {
                        esTrashModal = true;
                        if (esOpcionPromo) {
                            tr.appendChild(td);
                        }
                    }
                    break;
            }
            
            if (!esTrashModal) {
                tr.appendChild(td);
            }
        });
        this.bodyTabla.appendChild(tr);

        return null;
    }

    _creaBotonTrash(td, esModal, prod_id) {
        td.classList.add("w-16", "flex", "justify-center", "hover:bg-gray-100", "cursor-pointer");
        td.dataset.idprod = prod_id;
        const svg = this._creaSvgTrash();

        if (!esModal) {
            td.onclick = _clickEnLineaVenta;
        }

        td.insertAdjacentHTML('afterbegin', svg);

        return td;
    }

    _creaSvgTrash() {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="rgba(239, 68, 68, 0.8)" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>`;

        return svg;
    }


}
