/**
 * UiListaProdStock.js
 * 
 * Clase que maneja la lista de productos,
 * al cambiar de grupo de producto, se carga 
 * nueva lista de productos, según grupo
 * 
 */



/**
 * Rellena datos de producto seleccionado para stock
 * 
 * @param {int} id 
 * @param {float} costo 
 * @param {string} descrip 
 */
const _setProductoSeleccionado = (id, codigo, costo_x_unidad, costo_x_bulto, descrip) => {
    _STOCK.producto_selec.producto_id = id; //parseInt(id);
    _STOCK.producto_selec.codigo = codigo;
    _STOCK.producto_selec.descripcion = descrip;
    _STOCK.producto_selec.costo_x_unidad = parseFloat(costo_x_unidad);
    _STOCK.producto_selec.costo_x_bulto = parseFloat(costo_x_bulto);
};

/**
 * Lista de productos a seleccionar
 */
class UiListaProdStock {
    constructor() {
        this.bodyProductos = document.querySelector('#bodyProductos');
    }

    productosALinea(grupoId) {
        grupoId = this._extractId(grupoId);
        const prods = _STOCK.productos.filter(el => el.grupo_id === grupoId);

        this._vaciarBody();
        this._loopProds(prods);
        this._clickEnLineaProd();
    }

    ponerListaProdEnBlanco() {
        // Saco clase por si color anterior
        const lineaProd = document.querySelectorAll('#bodyProductos > tr > td');
        lineaProd.forEach(linea => {
            linea.classList.remove('linSelected');
        });

        return null;
    }

    _vaciarBody() {
        this.bodyProductos.innerHTML = "";

        return null;
    }

    _extractId(txtGrupoId) {
        return parseInt(txtGrupoId.substring(3));
    }

    _loopProds(prods) {
        const estaClase = this;

        prods.forEach(elem => {
            const tr = estaClase._setLinea(elem);
            estaClase.bodyProductos.appendChild(tr);
        });

    }

    _setLinea(data) {
        let tr = document.createElement("tr");
        tr.dataset.idprod = data.id;
        tr.dataset.codigo = data.codigo;    // ACA VA CODIGO !!!
        tr.dataset.costo_x_unidad = data.costo_x_unidad;
        tr.dataset.costo_x_bulto = data.costo_x_bulto;
        tr.classList.add("text-gray-700", "text-sm", "hover:bg-gray-200", "cursor-pointer");

        let tdDesc = document.createElement("td");
        tdDesc.classList.add("text-center", "pl-2", "whitespace-normal");
        if (_STOCK.grupos_helado.includes(data.grupo_id)) {  // Si es helado comun (sabores)
            tdDesc .textContent = data.descripcion_ticket;
        } else {
            tdDesc .textContent = data.descripcion;
        }

        tr.appendChild(tdDesc);

        return tr;
    }

    _cambiarColorLineaProd = (target) => {
        this.ponerListaProdEnBlanco();
        // Agrego clase color a linea actual
        target.classList.add('linSelected');

        return null;
    }

    _clickEnLineaProd() {   // Click en línea productos a ingresar
        const esta = this;
        const lineaProd = document.querySelectorAll('#bodyProductos > tr');
        // Loop para dar evento click a cada linea de producto
        lineaProd.forEach(item => {
            item.addEventListener('click', function (e) {
                const elem = e.target.parentElement;
                // FIJAR COLOR EN LA LINEA
                esta._cambiarColorLineaProd(e.target);
                // Foco en input cantidad
                document.querySelector('#cantidad').focus();
                document.querySelector('#cantidad').select();

                //console.log('Producto seleccionado, idprod:', elem.dataset.idprod);

                // CARGAR ID PRODUCTO EN VAR PRODUCTO_SELEC
                _setProductoSeleccionado(
                    elem.dataset.idprod,
                    elem.dataset.codigo,
                    elem.dataset.costo_x_unidad, 
                    elem.dataset.costo_x_bulto, 
                    e.target.textContent);
            });
        });
    }
}

export default UiListaProdStock;