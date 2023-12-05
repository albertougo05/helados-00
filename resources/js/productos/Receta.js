/**
 * Maneja los eventos del modal de ingreso productos a receta
 */

import { _roundToTwo, strToFloat } from "../utils/solo-numeros";


const Receta = class {
    constructor() {
        this.totalReceta = 0;
    }

    // Carga los productos si vienen del controlador
    cargaProductos(prods) {
        if (prods.length > 0) {
            _productosReceta = [];
            prods = prods.forEach(prod =>{
                let subtotal = _roundToTwo(prod.cantidad * prod.costo);
                let temp = { 
                    id: prod.id,
                    producto_id: prod.producto_id,
                    producto_receta_id: prod.producto_receta_id, 
                    descripcion: prod.descripcion, 
                    costo: prod.costo, 
                    cantidad: prod.cantidad,
                    subtotal: subtotal 
                };
                // sub-producto a la receta (array)
                _productosReceta.push(temp);
                // Sub-producto a la tabla en pantalla
                this.nuevoProductoATabla();
            });
        }
    }

    // Agrega producto desde modal
    setNuevoProd(idprodrecet, cant, costo) {

        //console.log('Costo:', costo, 'Tipo:', typeof costo);

        const idx = _productos_receta.findIndex(e => e.codigo == idprodrecet);
        const cantidad = strToFloat(cant);
        const costoProd = strToFloat(costo);
        const subtotal = _roundToTwo(cantidad * costoProd);
        const subprod = { 
            id: 0,
            producto_id: _producto_codigo,
            producto_receta_id: idprodrecet,
            descripcion: _productos_receta[idx].descripcion, 
            costo: costoProd, 
            cantidad: cantidad,
            subtotal: subtotal };

        _productosReceta.push(subprod);
    }

    nuevoProductoATabla() {
        const prod = _productosReceta[_productosReceta.length - 1];
        const tableBoby = document.querySelector('#bodyTableReceta');
        // Armo la línea
        const linea = this.__armoLinea(prod);
        tableBoby.appendChild(linea);
        this.__actualizoTotalReceta();
    }

    getListaProductosToStore() {
        return _productosReceta.map(e => {
            delete e.subtotal;

            return e;
        });
    }

    /**
     * Set producto editado (pasa valores del los inputs)
     * 
     * @param {integer} idProd 
     * @param {string} costo 
     * @param {string} cant 
     */
    setProductoEdit(idProd, costo, cant) {
        const idx = _productosReceta.findIndex(e => e.producto_receta_id == idProd);
        //console.log('Idx:', idx, 'productosReceta:', _productosReceta);
        _productosReceta[idx].costo = strToFloat(costo);
        _productosReceta[idx].cantidad = strToFloat(cant);
        _productosReceta[idx].subtotal = _roundToTwo(strToFloat(cant) * strToFloat(costo));
    }

    redibujaTabla() {
        const tableBoby = document.querySelector('#bodyTableReceta');
        tableBoby.innerHTML= "";

        _productosReceta.forEach(prod => {
            const linea = this.__armoLinea(prod);
            tableBoby.appendChild(linea);
        });
        this.__actualizoTotalReceta();
    }

    /**
     * Verifica si el producto a ingresar ya está en la Receta
     * 
     * @param integer idprod 
     * @returns 
     */
    checkProdEnLista(idprod) {
        let indice = true;
        const idx = _productosReceta.findIndex(e => e.producto_receta_id == idprod);

        if (idx === -1) indice = false;

        return indice;
    }

    getProductoEnLista(idprod) {
        const idx = _productosReceta.findIndex(e => e.producto_receta_id == idprod);

        return _productosReceta[idx];
    }

    eliminaProductoDeReceta(idprod) {
        _productosReceta = _productosReceta.filter(e => e.producto_receta_id != idprod );
    }

    async eliminaProdDeDB(idprod) {
        try {
            const response = await axios('/producto_receta/elimina/' + idprod);
            //console.log('Data respuesta:', response);
            return response;
        } catch (error) {
            console.log('Error: ', error);
            return null;
        }
    }


/**   FUNCIONES PRIVADAS   **/

    // Armo linea para tabla Receta (un producto)
    __armoLinea(prod) {
        let tr = document.createElement('tr'),
        td = {};
        tr.classList.add("hover:bg-gray-100", "cursor-pointer");
        tr.onclick = this.__onClickTr;

        Object.entries(prod).forEach(([key, value]) => {
            td = document.createElement('td');
            td.textContent = value;

            switch (key) {
                case 'id':
                    return;
                case 'producto_id':
                    return;
                case 'producto_receta_id':
                    td.classList.add('text-center');                    
                    break;
                case 'descripcion':
                    td.classList.add('text-left', 'pl-1', 'w-1/2', 'overflow-ellipsis'); 
                    break;
                case 'costo':
                    td.classList.add('text-right', 'pr-1');
                    td.textContent = this.__numberFormat(value);
                    break;
                case 'cantidad':
                    td.classList.add('text-right', 'pr-1');
                    td.textContent = this.__numberFormat(value, 3);
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

    __actualizoTotalReceta() {
        this.totalReceta = 0;
        _productosReceta.forEach(prod => {
            this.totalReceta += prod.subtotal;
        });
        // Actualizo la pantalla
        document.querySelector('#totalReceta').textContent = this.__numberFormat(this.totalReceta, 2);

        if (_productosReceta.length == 1) {
            // Si es el primer producto, muestro boton de guardar
            document.querySelector('#divConfirmarReceta').classList.remove('hidden');
        }
    }

    /**
     * Click en línea de tabla Receta (Edita producto de la receta)
     * 
     * @param {elemento html} e (linea de producto)
     */
    __onClickTr(e) {
        // codigo sale del primer elemento de la linea
        const codigo = e.target.parentElement.cells[0].textContent;
        const idx = _productosReceta.findIndex(e => e.producto_receta_id == codigo);
        const modal = document.querySelector('.modalReceta');
        const btnElimina = document.querySelector('button#btnEliminaProdReceta');
        const titulo = document.querySelector('h2#tituloModal');
        const selProdReceta = document.querySelector('#selProductoReceta');
        
        //console.log('Codigo:', codigo, 'Producto:', _productosReceta[idx]);

        titulo.textContent = "Editar producto de receta";
        modal.classList.remove('hidden');
        btnElimina.classList.remove('hidden');
        selProdReceta.value = codigo;
        selProdReceta.disabled = true;
        _inputModalCosto.typedValue = _productosReceta[idx].costo;
        _inputModalCantidad.typedValue = _productosReceta[idx].cantidad;
        _editaProducto = true;
        _idProdEditado = codigo;
    }

}


export default Receta;