/** 
 * Llenar tabla detalle de comprobante
 */

import { strToCurrency } from "../../utils/solo-numeros";


export default function llenarTablaDetalle(data) {
    // Recorrer data con map
    data.map(elem => {
        _uiLineaDetalle(elem);
    });
};

const vaciarBodyDetalle = () => {
    document.querySelector('#bodyDetalle').innerHTML = '';
}


/**
 * (private) Dibuja linea en tabla datalle
 * 
 * @param {*} elem 
 */
function _uiLineaDetalle(elem) {
    const bodyDet = document.querySelector('#bodyDetalle');
    const tr = _setTr(elem);
    bodyDet.appendChild(tr);
    
    return null;
}

function _setTr(elem) {
    let tr = document.createElement("tr");
    tr.classList.add("text-gray-700", "text-sm", "hover:bg-gray-100");
    const cant = _setCant(elem);
    tr.appendChild(cant);
    const desc = _setDesc(elem);
    tr.appendChild(desc);
    const precio = _setPrecio(elem);
    tr.appendChild(precio);
    const subtot = _setSubt(elem);
    tr.appendChild(subtot);

    return tr;
}

function _setCant(data) {
    let td = document.createElement("td");
    td.classList.add("text-right", "pl-2");
    td.textContent = Number(data.cantidad).toFixed(0);

    return td;
}

function _setDesc(data) {
    let td = document.createElement("td");
    td.classList.add("text-left", "pl-2", "whitespace-normal");
    td.textContent = data.descripcion;

    return td;
}

function _setPrecio(data) {
    let td = document.createElement("td");
    td.classList.add("text-right", "pr-2");
    td.textContent = strToCurrency(data.precio_unitario);

    return td;
}

function _setSubt(data) {
    let td = document.createElement("td");
    td.classList.add("text-right", "pr-2");
    td.textContent = strToCurrency(data.subtotal);

    return td;
}


export { vaciarBodyDetalle };