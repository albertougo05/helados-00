/**
 * Manejo eventos de la venta
 */
import UiLineaVenta from "./UiLineaVenta";
import UiTotal from "./UiTotal";
import { spanishToEnglish } from "../utils/transformFechas";


export default class ManejoVenta {

    productoSeleccionado(idProdSelec, promoOpcs = '') {
        const idxVenta = _VENTA.detalle.findIndex(e => e.id == idProdSelec);
        const uiTotal = new UiTotal();
        let promoYaEstaEnLista = false;

        if (_VENTA.detalle.length == 0) {
            // Activo el boton confirmar venta
            document.querySelector('#btnConfirmaVenta').disabled = false;
        }

        if (idxVenta == -1) {    // el producto no está en lista venta
            const productoSelecc = this._buscarProducto(idProdSelec, promoOpcs);
            this._productoAListaVenta(productoSelecc);
            uiTotal.setTotal();
        } else {
            this._productoYaEnListaVenta(idProdSelec);
            uiTotal.setTotal();
            promoYaEstaEnLista = true;
        }

        return promoYaEstaEnLista;
    }

    opcionesPromo(opciones) {       // Ingresa opciones de promo a lista de venta
        opciones.forEach(elem => {
            const idOpcion = this._buscarIdOpcionPromo(elem);
            const productoSelecc = this._buscarOpcionPromo(idOpcion, opciones);
            this._productoAListaVenta(productoSelecc);
        });

        return null;
    }

    opcionesPromoYaEnLista(codsCombosSel) {
        codsCombosSel.forEach(elem => {
            const idOpcion = this._buscarIdOpcionPromo(elem);
            const productoSelecc = this._buscarOpcionPromo(idOpcion, codsCombosSel);
            const idxVenta = _VENTA.detalle.findIndex(e => e.id == idOpcion);

            if (idxVenta == -1) {    // el producto no está en lista venta
                this._productoAListaVenta(productoSelecc);
            } else {
                this._productoYaEnListaVenta(idOpcion, true);
            }
        });

        return null;
    }

    _buscarProducto(idProdSel, promoOpciones = '') {     // Formato de tabla 'detalle_comprob_venta'
        const idx = _VENTA.productos.findIndex(e => e.id == idProdSel);

        return {
            id: _VENTA.productos[idx].id,
            sucursal_id: _VENTA.sucursal_id,
            comprobante_id: 0,
            nro_comprobante: 0,
            fecha: _VENTA.fecha,
            hora: '',
            con_receta:  _VENTA.productos[idx].con_receta,
            producto_id: _VENTA.productos[idx].id,
            codigo: _VENTA.productos[idx].codigo,
            descripcion: _VENTA.productos[idx].descripcion,
            cantidad: 1,
            unidades: _VENTA.productos[idx].unidades_x_caja == 0 ? 1 : _VENTA.productos[idx].unidades_x_caja,
            unid_x_caja: _VENTA.productos[idx].unidades_x_caja,
            precio_unitario: _VENTA.productos[idx].precio_vta,
            costo_x_unidad: _VENTA.productos[idx].costo_x_unidad,
            costo_subtotal: _VENTA.productos[idx].costo_x_unidad,
            peso_helado: _VENTA.productos[idx].peso_materia_prima,      // Peso producto X cantidad
            subtotal: _VENTA.productos[idx].precio_vta,
            promo_opciones: promoOpciones,     // Campo a eliminar antes de hacer POST
        };
    }

    _buscarOpcionPromo(idProdSel, promoOpciones = '') {     // Formato de tabla 'detalle_comprob_venta'
        const idx = _VENTA.productos.findIndex(e => e.id == idProdSel);
        const idxOpc = _VENTA.promo_opcs.findIndex(e => e.producto_codigo_opcion == _VENTA.productos[idx].codigo);
        let strOpcionesPromo = '';

        if (promoOpciones.constructor === Array) {
            strOpcionesPromo = promoOpciones.join(' ');
        }

        return {
            id: _VENTA.productos[idx].id,
            sucursal_id: _VENTA.sucursal_id,
            comprobante_id: parseInt(_VENTA.nro_comprobante) - 100,
            nro_comprobante: _VENTA.nro_comprobante,
            fecha: _VENTA.fecha,
            hora: '',
            con_receta:  _VENTA.productos[idx].con_receta,
            producto_id: _VENTA.productos[idx].id,
            codigo: _VENTA.productos[idx].codigo,
            descripcion: '-> ' + _VENTA.productos[idx].descripcion,
            cantidad: parseInt(_VENTA.promo_opcs[idxOpc].cantidad),
            //unidades: _VENTA.productos[idx].unidades_x_caja == 0 ? 1 : _VENTA.productos[idx].unidades_x_caja,
            unidades: parseInt(_VENTA.promo_opcs[idxOpc].cantidad),
            unid_x_caja: _VENTA.productos[idx].unidades_x_caja,
            peso_helado: _VENTA.productos[idx].peso_materia_prima,
            precio_unitario: 0,
            costo_x_unidad: 0,
            costo_subtotal: 0,
            subtotal: 0,
            promo_opciones: strOpcionesPromo,     // Campo a eliminar antes de hacer POST
        };
    }

    _buscarIdOpcionPromo(codigo) {
        const idx = _VENTA.productos.findIndex(e => e.codigo == codigo);

        return _VENTA.productos[idx].id;
    }

    _productoAListaVenta(producto) {
        _VENTA.detalle.push(producto);              // Producto a lista de venta (array)
        const uiTablaVenta = new UiTablaVenta();   // Cargo a tabla Venta
        uiTablaVenta.insertaLinea(producto);

        return null;
     }

    _productoYaEnListaVenta(idProd, esPromo = false) {
        const idx = _VENTA.detalle.findIndex(e => e.id == idProd);
        let producto = _VENTA.detalle[idx];
        const idxOpc = _VENTA.promo_opcs.findIndex(e => e.producto_codigo_opcion == producto.codigo);

        if (esPromo) {
            //console.log('Cant en promo_opcs:', _VENTA.promo_opcs[idxOpc].cantidad)
            producto.cantidad += parseInt(_VENTA.promo_opcs[idxOpc].cantidad);
        } else {
            producto.cantidad += 1;
        }

        if (producto.unid_x_caja > 0) {
            producto.unidades = producto.unid_x_caja * producto.cantidad;
        } else {
            producto.unidades = producto.cantidad;
        }

        producto.subtotal = parseFloat(producto.precio_unitario) * producto.cantidad;
        producto.costo_subtotal = producto.cantidad * producto.costo_x_unidad;

        const uiTablaVenta = new UiTablaVenta();    // Actualizar vista lista venta
        uiTablaVenta.actualizarLista();

        return null;
    }

}


class UiTablaVenta {
    constructor() {
        this.bodyTabla = document.querySelector('#bodyVenta');
        this.uiLineaVenta = new UiLineaVenta(this.bodyTabla);
    }

    insertaLinea(prod) {
        this.uiLineaVenta.productoALinea(prod);
        this.bodyTabla.scrollIntoView(false);

        return null;
    }

    actualizarLista() {
        // Vaciar body
        this.uiLineaVenta.resetBody();

        _VENTA.detalle.forEach(prod => {
            this.uiLineaVenta.productoALinea(prod);
        });
        this.bodyTabla.scrollIntoView(false);

        return null;
    }

}