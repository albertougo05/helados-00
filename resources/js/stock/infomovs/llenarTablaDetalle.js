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

    const un_med = _setUniMed(elem);
    tr.appendChild(un_med);

    const desc = _setDesc(elem);
    tr.appendChild(desc);

    const total_unid = _setTotalUnid(elem);
    tr.appendChild(total_unid);

    return tr;
}

function _setCant(data) {
    let td = document.createElement("td");
    td.classList.add("text-right");
    td.textContent = Number(data.cantidad).toFixed(2);

    return td;
}

function _setDesc(data) {
    let td = document.createElement("td");
    td.classList.add("text-left", "pl-2", "whitespace-normal");
    td.textContent = data.descripcion;

    return td;
}

function _setUniMed(data) {
    let td = document.createElement("td");
    td.classList.add("text-center");
    td.textContent = data.unidad_medida;

    return td;
}

function _setTotalUnid(data) {
    let td = document.createElement("td");
    td.classList.add("text-right", "pr-3");
    td.textContent = Number(data.cant_total_unid).toFixed(2);

    return td;
}

export { vaciarBodyDetalle };