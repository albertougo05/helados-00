/**
 * 
 * @param {array} data 
 */

export default function llenarTablaInfo(data) {
    // Vaciar body tabla
    document.querySelector('#tbody_tabla_info').innerHTML = '';

    //console.log('Data en llenarTablaInfo:', data, 'Tipo:', typeof data);

    if (data.length > 0) {
        // Recorrer data con map
        data.map(elem => {
            _uiLineaDetalleDesvio(elem);
        });
    }

    return null;
};

/**
 * (private) Dibuja linea en tabla datalle
 * 
 * @param {*} elem 
 */
function _uiLineaDetalleDesvio(elem) {
    const bodyDet = document.querySelector('#tbody_tabla_info');
    const tr = _setTr(elem);
    bodyDet.appendChild(tr);
    
    return null;
}

function _setTr(elem) {
    let tr = document.createElement("tr");
    tr.classList.add("text-gray-600", "text-xs", "hover:bg-gray-300");

    tr.appendChild(_setString(elem.grupo));
    tr.appendChild(_setString(elem.descripcion, true));
    tr.appendChild(_setFloat(elem.costo, 2));
    tr.appendChild(_setFloat(elem.stock_inicial, 2, false, 'font-bold'));
    tr.appendChild(_setFloat(elem.ingresos, 2));
    tr.appendChild(_setFloat(elem.salidas, 2));
    tr.appendChild(_setFloat(elem.stock_final, 2, false, 'font-bold'));
    tr.appendChild(_setFloat(elem.si_i_s_sf, 2, false, 'text-blue-700', 'font-bold'));
    tr.appendChild(_setFloat(elem.ventas_pos, 2, false, 'text-blue-700', 'font-bold'));
    tr.appendChild(_setFloat(elem.desvio_unid, 2, true));
    tr.appendChild(_setFloat(elem.desvio_kilos, 3, true));
    tr.appendChild(_setFloat(elem.desvio_pesos, 2, true));
    tr.appendChild(_setDesvioPorc(elem.porc_desvio_stissf, 2));
    tr.appendChild(_setDesvioPorc(elem.porc_desvio_vta_pos, 2));

    return tr;
}

function _setFloat(data, dec = 2, es_desv = false, class1 = '', class2 = '') {
    let td = document.createElement("td");
    td.classList.add("text-right", "pr-2");

    if (class1 !== '') {
        td.classList.add(class1);
    }
    if (class2 !== '') {
        td.classList.add(class2);
    }

    if (es_desv) {
        if (Number(data) < 0) {
            td.classList.add('text-green-700', 'font-extrabold');
        } else if (Number(data) > 0) {
            td.classList.add('text-red-700', 'font-extrabold');
        }
    }

    //if (data == 0) {
    //    td.textContent = '';
    //} else {
        td.textContent = Number(data).toFixed(dec);
    //}

    return td;
}

function _setString(data, es_desc = false) {
    let td = document.createElement("td");
    td.classList.add("text-left", "pl-2", "whitespace-normal");

    if (es_desc) {
        td.classList.add("font-bold");
    }

    td.textContent = data;

    return td;
}

function _setDesvioPorc(data, dec = 2, class1 = '', class2 = '') {
    let td = document.createElement("td");
    td.classList.add("text-right", "pr-2");

    if (class1 !== '') {
        td.classList.add(class1);
    }
    if (class2 !== '') {
        td.classList.add(class2);
    }

    //console.log('Porc desvio:', data, typeof data);

    // td.textContent = Number(data).toFixed(dec) + ' %';

    //if (data == 0) {
    //    td.textContent = '';
    //} else {
        td.textContent = Number(data).toFixed(dec) + ' %';
    //}

    return td;
}