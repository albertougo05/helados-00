
import MensajeAlerta from "../utils/MensajeAlerta";


const setInputBuscar = (filtro) => {
    const text = filtro[2].replace('%', '');
    document.querySelector('input#buscar').value = text;
}

const setSelect = (filtro, listado) => {
    const valor = filtro[2];
    const select = document.querySelector('select#' + filtro[0]);
    // Buscar en listado
    let idx = listado.findIndex(e => e.id == valor);
    // Selecciono en select de productos
    select.options[idx + 1].selected = true;
}


window.onload = function() {
    if (__sessionStatusMsj) {
        MensajeAlerta(__sessionStatusMsj, 'success');
    }

    const btnFiltrar = document.querySelector('button#btnFiltrar');
    btnFiltrar.addEventListener('click', function (e) {
        const prov = document.querySelector('select#proveedor_id').value;
        const tipo = document.querySelector('select#tipo_producto_id').value;
        const grup = document.querySelector('select#grupo_id').value;
        const busq = document.querySelector('input#buscar').value || '';
        let url = '/producto/filtrado?prov=' + prov + '&tipo=' + tipo;
        url += '&grupo=' + grup + '&buscar=' + busq;

        location.assign(url);
    });

    const btnBuscar = document.querySelector('button#btnBuscar');
    btnBuscar.addEventListener('click', e => {
        //console.log('Enviar buscar...')
        document.querySelector('form').submit();
    });

    if(__filtros) {
        const btnQuitar = document.querySelector('button#btnQuitarBusq');
        btnQuitar.addEventListener('click', function () {
            location.assign('/producto');
        });

        // setear input y selec
        for (const filtro of __filtros) {
            switch (filtro[0]) {
                case 'descripcion':
                    //console.log('filtro buscar');
                    setInputBuscar(filtro);
                    break;
                case 'proveedor_id':
                    setSelect(filtro, __proveedores);
                    break;
                case 'tipo_producto_id':
                    //console.log('filtro tipo');
                    //setSelect(filtro, tipos);
                    break;
                case 'grupo_id':
                    //console.log('filtro grupo');
                    //setSelect(filtro, grupos);
                    break;
            }
        }
    }
}
