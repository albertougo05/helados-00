import { strToFloat } from '../../utils/solo-numeros';


/**
 * Devuelve array de inputs modificados
 * 
 * @param {array} inputs 
 * @returns 
 */
const _getInputsModif = inputs => {
    let modificados = [];

    inputs.forEach(elem => {
        const valor = strToFloat(elem.value),
              original = parseFloat(elem.dataset.valororig);

        if (valor != original) {     // Si es diferente del valor original...
            let inputModif = _setModeloProd(elem, valor);
            modificados.push(inputModif);
        }
    });

    return modificados;
};

/**
 * Devuelve objeto del modelo de producto
 * 
 * @param {object} elem 
 * @param {float} valor 
 * @returns 
 */
const _setModeloProd = (elem, valor) => {
    const modelProducto = {};
    modelProducto.id = elem.dataset.idprod;

    switch (elem.dataset.campo) {
        case 'costo_x_unidad':
            modelProducto.campo = elem.dataset.campo;
            modelProducto.importe = valor;
            break;
        case 'costo_x_bulto':
            modelProducto.campo = elem.dataset.campo;
            modelProducto.importe = valor;
            break;
        case 'precio_lista_1':
            modelProducto.campo = elem.dataset.campo;
            modelProducto.importe = valor;
            break;
        case 'precio_lista_2':
            modelProducto.campo = elem.dataset.campo;
            modelProducto.importe = valor;
            break;
        case 'precio_lista_3':
            modelProducto.campo = elem.dataset.campo;
            modelProducto.importe = valor;
            break;
    }

    return modelProducto;
};


export default _getInputsModif;