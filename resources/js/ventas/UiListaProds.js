/**
 * 
 * Clase que maneja la lista de productos,
 * al cambiar de grupo de producto, se carga 
 * nueva lista de productos, según grupo
 * 
 */

import { strToCurrency } from "../utils/solo-numeros";
import ManejoVenta from "./ManejoVenta";
import ManejoPromo from "./ManejoPromo";


const __checkPromo = idProd => {
    const idxProd = _VENTA.productos.findIndex(e => e.id == idProd);
    const prod = _VENTA.productos[idxProd];
    const idx = _VENTA.promos.findIndex(e => e.producto_codigo == prod.codigo);

    if (idx === -1) {
        return false;
    } else {
        return _VENTA.promos[idx];
    }
};


class UiListaProds {
    constructor(productos) {
        this.productos = productos;
        this.bodyProductos = document.querySelector('#bodyProductos');
    }

    productosALinea(grupoId) {
        const intGrupoId = parseInt(grupoId.substring(3));
        const prods = this.productos.filter(el => el.grupo_id === intGrupoId);

        this._vaciarBody();
        this._loopProds(prods);
        this._clickEnLineaProd();
    }

    _vaciarBody() {
        this.bodyProductos.innerHTML = "";

        return null;
    }

    _loopProds(prods) {
        const estaClase = this;

        // ACA FILTRAR SI ES PROMO Y ESTA VIGENTE (EN LA LISTA DE PROMOS)
        
        prods.forEach(elem => {
            if (estaClase._checkPromoVigente(elem)) {
                return;
            }
            const tr = estaClase._setLinea(elem);
            estaClase.bodyProductos.appendChild(tr);
        });
        
    }
    
    _checkPromoVigente(elem) {
        if (elem.grupo_id === 17) {     // Si es grupo promo...
            if (this._checkCodigoEnPromo(elem.codigo)) {
                return false;       // Si está en promos vigentes ...
            } else {
                return true;        // NO está en promos vigentes 
            }
        } else {
            return false;
        }
    }

    _checkCodigoEnPromo(codigo) {
        const idx = _VENTA.promos.findIndex(el => el.producto_codigo === codigo);

        if (idx === -1) {
            return false;
        }
        return true;
    }

    _setLinea(data) {
        let tr = document.createElement("tr");
        tr.dataset.idprod = data.id;        // Cambiar por codigo

        //console.log('Id prod:', data.id, ' Codigo:', data.codigo)

        tr.classList.add("text-gray-700", "text-sm", "hover:bg-gray-100", "cursor-pointer");
        let tdDesc = document.createElement("td");
        tdDesc.classList.add("text-left", "pl-2", "whitespace-normal");
        tdDesc .textContent = data.descripcion;
        let tdPrecio = document.createElement("td");
        tdPrecio.classList.add("text-right", "pr-2");
        tdPrecio.textContent = strToCurrency(data.precio_vta);
        tr.appendChild(tdDesc);
        tr.appendChild(tdPrecio);

        return tr;
    }

    _clickEnLineaProd() {   // Click en línea producto
        const lineaProd = document.querySelectorAll('#bodyProductos > tr');
        // Loop para dar evento click a cada linea de producto
        lineaProd.forEach(item => {
            item.addEventListener('click', function (e) {
                const elem = e.target.parentElement,
                      promo = __checkPromo(elem.dataset.idprod);

                 if (promo) {
                    const manejoPromo = new ManejoPromo(promo);
                    manejoPromo.selecPromo();
    
                } else {
                    const manejoVta = new ManejoVenta();
                    manejoVta.productoSeleccionado(elem.dataset.idprod);
                }
            });
        });
    }
}

export default UiListaProds;