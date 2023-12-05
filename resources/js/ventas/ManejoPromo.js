/**
 * 
 * Manejo eventos de seleccio de Promocion
 * 
 */

import { strToCurrency } from '../utils/solo-numeros';


const __crearVentanaOpciones = (producto, opciones) => {
    let comboActual = 1;
    const defH2 = __creaNombreProducto(producto),
          defPrecio = __creaPrecioProducto(producto),
          bodyModal = document.querySelector('#modal-promo-body');

    bodyModal.innerHTML = '';       // Vacio el body del modal
    bodyModal.appendChild(defH2);
    bodyModal.appendChild(defPrecio);

    opciones.forEach(combo => {
        const defDiv = __creaDiv(),
              defLabel = __creaLabel(),
              defSelect = __creaSelect();

        defLabel.innerHTML = 'Combo ' + comboActual;
        defLabel.htmlFor = 'selCombo-' + comboActual;
        defDiv.appendChild(defLabel);
        defSelect.id = 'selCombo-' + comboActual;

        combo.forEach(opc => {
            const opcion = document.createElement('option');
            opcion.value = opc.producto_codigo_opcion;
            opcion.text = Math.trunc(opc.cantidad) + ' * ' + opc.descripcion.trim();
            defSelect.appendChild(opcion);
        });

        defDiv.appendChild(defSelect);
        bodyModal.appendChild(defDiv);
        comboActual++;
    });

    return null;
}

const __creaDiv = () => {
    const div = document.createElement("div");
    div.classList.add('pl-1', 'mb-2');

    return div;
}

const __creaLabel = () => {
    const label = document.createElement("label");
    label.classList.add('block', 'pl-1', 'font-medium', 'text-sm', 'text-gray-700');

    return label;
}

const __creaSelect = () => {
    const select = document.createElement("select");
    select.classList.add('h-8', 'w-full', 'pt-0.5', 'text-gray-700', 'text-base');
    select.classList.add('placeholder-gray-600', 'rounded', 'shadow-sm', 'border-gray-300');
    select.classList.add('focus:border-indigo-300', 'focus:ring', 'focus:ring-indigo-200');
    select.classList.add('focus:ring-opacity-50appearance-none', 'focus:shadow-outline');

    return select;
}

const __creaNombreProducto = prod => {
    const elh2 = document.createElement("H2");
    elh2.id = 'tituloModalPromo';
    elh2.classList.add('p-1', 'mb-0', 'mt-1', 'font-bold', 'text-lg', 'text-gray-100', 'text-center', 'rounded', 'bg-indigo-800');
    elh2.innerHTML = prod.descripcion;

    return elh2;
}

const __creaPrecioProducto = prod => {
    const precio = document.createElement("h3");
    precio.classList.add('p-1', 'font-medium', 'text-sm', 'text-center');
    precio.innerHTML = '$' + strToCurrency(prod.precio_vta, false, 2);

    return precio;
}


export default class ManejoPromo {
    constructor(promo) {
        this.promoSelec = promo;
        this.productoPromo = undefined;
        this.opcionesPromo = undefined;
    }

    selecPromo() {
        this.opcionesPromo = this._getOpcionesPromo(this.promoSelec.producto_codigo);
        this.productoPromo = this._getProductoPromo(this.promoSelec.producto_codigo);

        const combos = this._dividirCombos(this.opcionesPromo);

        //console.log('Combos div:', combos);

        __crearVentanaOpciones(this.productoPromo, combos);
        _VENTA.promo_producto_seleccionado = this.productoPromo;

        document.querySelector('.modal-promo').classList.remove('hidden');      // Muestra el modal

        return null;
    }

    _getOpcionesPromo(codigo) {
        return _VENTA.promo_opcs.filter(e => e.producto_codigo == codigo);
    }

    _getProductoPromo(codigo) {
        const idx = _VENTA.productos.findIndex(e => e.producto_id == codigo);

        return _VENTA.productos[idx];
    }

    _dividirCombos(opciones) {
        let combos = [],
            comboActual = 0;

        opciones.forEach (el => {
            if (el.nro_combo !== comboActual) {
                comboActual = el.nro_combo;
                combos.push([]);
            }
            combos[comboActual - 1].push(el);
        });
        _VENTA.promo_cant_combos = combos.length;

        return combos;
    }


}
