/**
 * Recoge los datos para filtrar los movimientos de caja
 */


/**
 * Devuelve objeto con datos para peticion GET
 * 
 * @returns objeto con datos
 */
function getData() {
    return {
        sucursal_id: document.querySelector('#sucursal_id').value,
        caja_nro: document.querySelector('#caja_nro').value,
        desde: document.querySelector('#desde').value,
        hasta: document.querySelector('#hasta').value,
        usuario_id: document.querySelector('#usuario_id').value,
        turno_id: document.querySelector('#turno_id').value,
    };
}

function getUrl(data) {
    let url = `${_pathMovsFiltrado}?suc_id=${data.sucursal_id}`;
    url += `&caja_nro=${data.caja_nro}&desde=${data.desde}`;
    url += `&hasta=${data.hasta}&usuario_id=${data.usuario_id}`;
    url += `&turno_id=${data.turno_id}`;

    return url;
}

function setCajasDeSuc(cajas) {
    let selSucs = document.querySelector('#caja_nro');
    selSucs.innerHTML = '';

    cajas.forEach(e => {
        let opt = document.createElement("option");
        opt.value = e.id;
        opt.text = e.texto;
        selSucs.add(opt);
    });

    return null;
}

export { getData, getUrl, setCajasDeSuc };
