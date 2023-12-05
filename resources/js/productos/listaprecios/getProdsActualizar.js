/**
 * Devuelve lista para pasar a axios y actualizar DB
 * 
 * @param {array} listaInputs 
 * @returns 
 */
const _getProdsActualizar = listaInputs => {
    const listado = [];

    listaInputs.forEach(elem => {
        const idx = listado.findIndex(el => el.id == elem.id);

        if (idx >= 0) {
            switch (elem.campo) {
                case 'costo_x_unidad':
                    listado[idx].costo_x_unidad = elem.importe;
                    break;
                case 'costo_x_bulto':
                    listado[idx].costo_x_bulto = elem.importe;
                    break;
                case 'precio_lista_1':
                    listado[idx].precio_lista_1 = elem.importe;
                    break;
                case 'precio_lista_2':
                    listado[idx].precio_lista_2 = elem.importe;
                    break;
                case 'precio_lista_3':
                    listado[idx].precio_lista_3 = elem.importe;
                    break;
            }
        } else {
            listado.push(_setProdActualiz(elem));
        }
    });

    return { data: listado };
};

/**
 * Devuelve modelo de producto a actualizar
 * 
 * @param {object} elem 
 * @returns 
 */
const _setProdActualiz = (elem) => {
    const modelProducto = {};
    const idx = _LISTA_PREC._productos.findIndex(el => el.id == elem.id);

    modelProducto.id = elem.id;
    modelProducto.costo_x_unidad = _LISTA_PREC._productos[idx].costo_x_unidad;
    modelProducto.costo_x_bulto = _LISTA_PREC._productos[idx].costo_x_bulto;
    modelProducto.precio_lista_1 = _LISTA_PREC._productos[idx].precio_lista_1;
    modelProducto.precio_lista_2 = _LISTA_PREC._productos[idx].precio_lista_2;
    modelProducto.precio_lista_3 = _LISTA_PREC._productos[idx].precio_lista_3;

    switch (elem.campo) {
        case 'costo_x_unidad':
            modelProducto.costo_x_unidad = elem.importe;
            break;
        case 'costo_x_bulto':
            modelProducto.costo_x_bulto = elem.importe;
            break;
        case 'precio_lista_1':
            modelProducto.precio_lista_1 = elem.importe;
            break;
        case 'precio_lista_2':
            modelProducto.precio_lista_2 = elem.importe;
            break;
        case 'precio_lista_3':
            modelProducto.precio_lista_3 = elem.importe;
            break;
    }

    return modelProducto;
};


export default _getProdsActualizar;