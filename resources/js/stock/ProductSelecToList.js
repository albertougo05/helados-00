/**
 * Pasa el producto seleccionado a lista de stock
 */

import ManejoTablaProdStock from "./ManejoTablaProdStock";
import { strToFloat } from "../utils/solo-numeros";


const manejoTablaProdStock = new ManejoTablaProdStock();

const _set_producto = (codigo, cantidad, unid_medida, id) => {
    //const producto = _STOCK.productos.find(el => el.id == prod_selec.producto_id);
    const producto = _STOCK.productos.find(el => el.codigo == codigo);

    return {
        id: id,
        idx: _STOCK.idx_productos_stock,
        producto_id: producto.id,
        codigo: producto.codigo,
        grupo_id: producto.grupo_id,
        descripcion: producto.descripcion_ticket,
        cantidad: strToFloat(cantidad),
        unid_medida: unid_medida,
        costo_x_unidad: parseFloat(producto.costo_x_unidad),
        costo_x_bulto: parseFloat(producto.costo_x_bulto),
        articulo_indiv_id: producto.articulo_indiv_id ? parseInt(producto.articulo_indiv_id) : 0,
        unidades_x_bulto: producto.unidades_x_bulto ? parseInt(producto.unidades_x_bulto) : 0,
        unidades_x_caja: producto.unidades_x_caja ? parseInt(producto.unidades_x_caja) : 0,
        peso_materia_prima: producto.peso_materia_prima ? parseFloat(producto.peso_materia_prima) : 0,
        observaciones: '',
    };
};


class ProductSelecToList {
    constructor(inputCant) {
        this.inputCant = inputCant;
    }

    acciones(prod) {
        // cargo cantidad y unidad de medida
        const producto = _set_producto(
            prod.codigo, 
            this.inputCant.value, 
            document.querySelector('#unid_medida').value,
            0,
        );
        // va a lista en pantalla
        manejoTablaProdStock.producto_a_linea(producto);
        // va lista en array memoria
        _STOCK.productos_a_stock.push(producto);
        // Aumento el indice de productos a stock
        _STOCK.idx_productos_stock += 1;
        // desabilito boton ingreso
        document.querySelector('#btnIngresaProd').disabled = true;
        // pongo en 0 input cantidad
        this.inputCant.typedValue = 0;
 
        return null;
    }

}

class ProductToEdit {

    cargarDetalle() {
        _STOCK.detalle.map(el => {
            const prod = _set_producto(
                el.codigo, 
                parseFloat(el.cantidad), 
                el.unidad_medida,
                el.id,
            );
            _STOCK.productos_a_stock.push(prod);
            _STOCK.idx_productos_stock++;
        });
    }

    detalleAPantalla() {
        const manejoTabla = new ManejoTablaProdStock();
        manejoTabla.rellenaTablaCompleta();
    }

}


export { ProductSelecToList, ProductToEdit };