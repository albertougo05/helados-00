/**
 * Maneja los eventos del modal de ingreso articulos a promocion
 * 
 */

import { _roundToTwo, strToFloat } from "../utils/solo-numeros";
import MensajeAlerta from "../utils/MensajeAlerta";


const __colores = [
    '0',     //  Indice 0 sin color
    'bg-orange-200',
    'bg-blue-200',
    'bg-teal-200',
    'bg-yellow-200',
    'bg-indigo-200',
    'bg-red-200',
];

const __cambiaClaseColor = (elem, idx) => {

    for (let index = 1; index < __colores.length; index++) {
        if (elem.classList.contains(__colores[index])) {
            elem.classList.remove(__colores[index]);
            elem.classList.add(__colores[idx]);
            break;
        }
    }

    return null;
}

const __spinGuardando = show => {
    const spinGuardando = document.querySelector('#spinGuardandoPromo');
     if (show) {
         spinGuardando.classList.remove('hidden');
     } else {
         spinGuardando.classList.add('hidden');
     }

     return null;
 };

const __getFechaHora = cuando => {
    let fecha = '',
        hora = '',
        fechaHora = '';

    switch (cuando) {
        case 'desde':
            fecha = document.querySelector('#promo_desde').value;
            hora = '00:00';
            break;
    
        case 'hasta':
            fecha = document.querySelector('#promo_hasta').value;
            hora = '23:59';
            break;
    }
    fechaHora = fecha + ' ' + hora;

    return fechaHora;
}


const Promocion = class {
    constructor() {
        this._totalPromocion = 0;
        this._articulosPromo = [];
    }

    // Carga los articulos, si vienen del controlador
    cargaArticOpciones() {
        if (_promocion_opciones.length > 0) {
                // Opciones a la tabla en pantalla
                //this.nuevoProductoATabla();
        }

        return null;
    }

    // Agrega producto desde modal
    setNuevoArtic(combo, codigo, precio, cant) {
        const selArtic = document.querySelector('#selArtPromo');
        const descrip = selArtic.options[selArtic.selectedIndex].text;
        const opcArtic = {..._PROMO._modeloArtOpcion};

        opcArtic.producto_codigo = _producto_codigo;
        opcArtic.nro_combo = combo;
        opcArtic.producto_codigo_opcion = codigo;
        opcArtic.descripcion = descrip;
        opcArtic.cantidad = cant;
        opcArtic.costo = 0;
        opcArtic.precio = precio;
        opcArtic.subtotal = cant * precio;

        _PROMO._opciones.push(opcArtic);
        // Ordenar por nro de combo
        _PROMO._opciones.sort((a, b) => (a.nro_combo > b.nro_combo) ? 1 : -1)

        return null;
    }

    nuevoArticuloATabla() {
        const prod = _PROMO._opciones[_PROMO._opciones.length - 1];
        const tableBoby = document.querySelector('#bodyTablePromo');
        // Armo la línea
        const linea = this.__armoLinea(prod);
        tableBoby.appendChild(linea);
        //this.__actualizoTotalPromo();
    }

    getListaArticulosToStore() {
        return _PROMO._opciones.map(e => {
            delete e.id;
            delete e.subtotal;

            return e;
        });
    }

    /**
     * Set producto editado (pasa valores del los inputs)
     * 
     * @param {integer} combo 
     * @param {integer} idProd 
     * @param {string} precio
     * @param {string} cant 
     */
    setProductoEditado(combo, idProd, precio, cant) {
        const idx = _PROMO._opciones.findIndex(e => e.producto_codigo_opcion == idProd);

console.log('Combo:', combo, 'Codigo prod:', idProd, 'Indice:', idx)

        _PROMO._opciones[idx].nro_combo = parseInt(combo);
        //_PROMO._opciones[idx].costo = this.__stringToNumber(costo);
        _PROMO._opciones[idx].precio = this.__stringToNumber(precio);
        _PROMO._opciones[idx].cantidad = this.__stringToNumber(cant);
        _PROMO._opciones[idx].subtotal = _roundToTwo(this.__stringToNumber(cant) * this.__stringToNumber(precio));
    }

    redibujaTabla() {
        const tableBoby = document.querySelector('#bodyTablePromo');
        tableBoby.innerHTML= "";

        _PROMO._opciones.forEach(prod => {
            if (prod.estado === 1) {
                const linea = this.__armoLinea(prod);
                tableBoby.appendChild(linea);
            }
        });
        //this.__actualizoTotalPromo();

        return null;
    }

    getArticuloEnLista(idprod) {
        const idx = _PROMO._opciones.findIndex(e => e.producto_codigo_opcion == idprod);

        return _PROMO._opciones[idx];
    }

    eliminaArticuloDePromo(idprod) {
        if (_promocion_producto) {  // Si producto tiene promocion...
            const idx = _PROMO._opciones.findIndex(e => e.producto_codigo_opcion == idprod);
            _PROMO._opciones[idx].estado = 0;
        } else {                   // Si no tiene elimina del array
            _PROMO._opciones = _PROMO._opciones.filter(e => e.producto_codigo_opcion != idprod );
        }

        return null
    }

    cambiarClaseColor(elem, idx) {
        __cambiaClaseColor(elem, idx);

        return null;
    }

    salvarDatos() {
        __spinGuardando(true);
        this.__setProductoPromo();

        Promise.all([ this._updateDatosProductoPromo(), 
                      this._updateDatosArticulosOpc() ])
            .then(function (results) {
                const producto = results[0],
                      articulos = results[1];
                //console.log('Resultados:', producto.data.status, articulos.data.status);
                if (producto.data.status == 'error' || articulos.data.status == 'error') {
                    MensajeAlerta('Error salvando datos promoción !', 'error')
                } else {
                    MensajeAlerta('Promoción guardada con exito !', 'success');
                    setTimeout(() => {
                        const form = document.querySelector('form');
                        form.submit();
                    }, 3000);
                }
            })
            .catch(function (error) {
                console.log('Error:', error);
                MensajeAlerta('Error salvando datos promoción !', 'error')
            })
            .finally(e => {
                __spinGuardando(false);
            });
    }


/**   FUNCIONES PRIVADAS   **/

    // Salva datos producto promo
    _updateDatosProductoPromo() {
        return axios.post(_PROMO._pathSalvarProducto, _PROMO._producto_promocion);
    }

    // Salvar datos opciones promo
    _updateDatosArticulosOpc() {
        return axios.post(_PROMO._pathSalvarArtOpc, _PROMO._opciones);
    }

    // Armo linea para tabla Receta (un producto)
    __armoLinea(prod) {
        let tr = document.createElement('tr'),
        td = {};
        tr.classList.add("hover:bg-gray-200", "cursor-pointer");
        tr.onclick = this.__onClickTr;

        Object.entries(prod).forEach(([key, value]) => {
            td = document.createElement('td');
            td.textContent = value;

            switch (key) {
                case 'id':
                    return;
                case 'producto_codigo':
                    return;
                case 'costo':
                    return;
                case 'estado':
                    return;
                case 'nro_combo':
                    tr.classList.add(__colores[value]);
                    td.classList.add('text-center');
                    //td.textContent = this.__numberFormat(value);
                    break;
                case 'producto_codigo_opcion':
                    td.classList.add('text-center');                    
                    break;
                case 'descripcion':
                    td.classList.add('text-left', 'pl-1', 'w-1/2', 'overflow-ellipsis'); 
                    break;
                case 'cantidad':
                    td.classList.add('text-right', 'pr-1');
                    td.textContent = this.__numberFormat(value, 3);
                    break;
                case 'precio':
                    td.classList.add('text-right', 'pr-1');
                    td.textContent = this.__numberFormat(value);
                    break;
                case 'subtotal':
                    td.classList.add('text-right', 'pr-2');
                    td.textContent = this.__numberFormat(value, 2);
                    break;
            }

            tr.appendChild(td);
        });

        return tr;
    }

    /**
     * Recibe un número y devuelve un string con dos o tres decimales
     * 
     * @param {float||int} num 
     * @param integer dec 
     * @return string
     */
	__numberFormat(num, dec = 2) {
        num = Number(num);
        return ( num.toFixed(dec) // always two decimal digits
                    .replaceAll('.', ',') // replace decimal point character with ,
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') ); // use . as a separator
	}

    /**
     * Convienrte string a número (entero o float)
     * @param {string} str 
     * @returns Number
     */
    __stringToNumber(str) {
        let num = str;

        if (typeof num === 'string') {
            num = str.replaceAll('.', '');     // Quita los puntos de miles
            num = str.replace(',', '.');         // Reemplaza coma decimal por punto
            num = Number(num);
        }

        return num;
    }

    // __actualizoTotalPromo() {
    //     this.totalPromo = 0;
    //     _PROMO._opciones.forEach(prod => {
    //         this.totalPromo += prod.subtotal;
    //     });
    //     // Actualizo la pantalla
    //     document.querySelector('#totalPromo').textContent = this.__numberFormat(this.totalPromo, 2);

    //     return null;
    // }

    /**
     * Click en línea de tabla Promo (Edita producto de la promocion)
     * 
     * @param {elemento html} e (linea de producto)
     */
    __onClickTr(e) {
        // codigo sale del segundo elemento de la linea
        const codigo = e.target.parentElement.cells[1].textContent;
        const idx = _PROMO._opciones.findIndex(e => e.producto_codigo_opcion == codigo);
        const modal = document.querySelector('#modalArticuloPromo');
        const btnElimina = document.querySelector('button#btnEliminaArtPromo');
        const titulo = document.querySelector('h2#tituloModalPromo');
        const selArtPromo = document.querySelector('#selArtPromo');
        const selComboPromo = document.querySelector('#selComboPromo');
        const divPromo = document.querySelector('#divPromo');

        titulo.textContent = "Editar producto de receta";
        __cambiaClaseColor(divPromo, _PROMO._opciones[idx].nro_combo);
        modal.classList.remove('hidden');
        btnElimina.classList.remove('hidden');
        selArtPromo.value = codigo;
        selArtPromo.disabled = true;
        selComboPromo.value =  _PROMO._opciones[idx].nro_combo;
        _PROMO._inputModalPromoPrecio.typedValue = _PROMO._opciones[idx].precio;
        _PROMO._inputModalPromoCantidad.typedValue = _PROMO._opciones[idx].cantidad;
        _editaArtPromo = true;
        _idArtEditado = codigo;
    }

    __setProductoPromo() {
        delete _PROMO._producto_promocion.id;
        _PROMO._producto_promocion.producto_codigo = _producto_codigo;
        _PROMO._producto_promocion.desde_fecha_hora = __getFechaHora('desde');
        _PROMO._producto_promocion.hasta_fecha_hora = __getFechaHora('hasta');
        _PROMO._producto_promocion.dias_semana = this.__getDiasSemana();
        _PROMO._producto_promocion.costo = 0;
        _PROMO._producto_promocion.precio_vta = strToFloat(document.getElementById('precioPromo').value);

        return null;
    }

    __getDiasSemana() {
        const arrDiasPromo = [],
        dias_promo = document.getElementsByName('dias_promo');

        for (let dia of dias_promo) {
            if (dia.checked) {
                arrDiasPromo.push(dia.value);
            }
        }

        return arrDiasPromo.join(' ');
    }

}


export default Promocion;