/**
 * 
 * Funciones para listado de Artículos
 * 
 */

import { strToCurrency } from '../../utils/solo-numeros';


const _convierteEntero = function (valor) {
    let entero = Number.parseInt(valor);

    if (Number.isNaN(entero)) {
        entero = 0;
    }

    return entero;
};

// BUSCAR REFERENCIAS A producto_id !!

function _ingresoArticuloAlListado(dataArt) {
    const index = _.findIndex(_REAL.articulosCargados, function(o) { return o.producto_id == dataArt.producto_id; });

    if (index !== -1) {
        _REAL.articulosCargados[index] = dataArt;

        return null;
    }

//    console.log('No existe en listado (crear e ingresar)...')

    _REAL.articulosCargados.push(dataArt);    // Ingresar nuevo Articulo al array...

    return null;
}

function _quitarArticuloDelListado(idProd) {
    const index = _.findIndex( _REAL.articulosCargados, 
        function(o) { return o.producto_id == idProd; } );

    if (index > 0) {
        //_REAL.articulosCargados = _REAL.articulosCargados.filter(elem => elem.producto_id !== idProd);
        _REAL.articulosCargados[index].cant_bultos = 0;
        _REAL.articulosCargados[index].cant_unid = 0;
        _REAL.articulosCargados[index].costo_bulto = 0;
        _REAL.articulosCargados[index].costo_unid = 0;
        _REAL.articulosCargados[index].total = 0;
        _REAL.articulosCargados[index].total_unid = 0;
        _REAL.articulosCargados[index].unid_x_bulto = 0;
    }

    return null;
}

const _cambiaColorLineaArt = function (target) {
    const inputBultos = document.querySelector('#bultos_' + target.dataset.id_art);
    const inputUnid = document.querySelector('#unid_' + target.dataset.id_art);
    let bultos = _convierteEntero(inputBultos.value);
    let unidades = _convierteEntero(inputUnid.value);
    const td = target.parentNode;
    const tr = td.parentNode;

    if (bultos === 0 && unidades === 0) {
        tr.classList.remove('lineaConDatoGreen');
    } else {
        if (tr.classList.contains('lineaConDatoGreen')) {
            return null;
        } else {
            tr.classList.add('lineaConDatoGreen');
        }
    }

    return null;
};

const __total_Unidades = function (target) {
    const tdTotal    = document.querySelector('#total_unid_' + target.dataset.id_art),
          tdBultos   = document.querySelector('#bultos_' + target.dataset.id_art),
          cantBultos = _convierteEntero(tdBultos.value),
          unidXBulto = _convierteEntero(tdBultos.dataset.unid_x_bulto),
          cantUnidEnBultos = cantBultos * unidXBulto,
          unidades   = _convierteEntero(document.querySelector('#unid_' + target.dataset.id_art).value),
          total      = unidades + cantUnidEnBultos;

    tdBultos.dataset.total_unidades = cantUnidEnBultos;

    if (total > 0) {
        tdTotal.innerHTML = total;
    } else {
        tdTotal.innerHTML = '';
    }

    return null;
};

const __total_Pesos = function (target) {
    const tdTotalUnid = document.querySelector('#pesos_total_' + target.dataset.id_art);
    const inputBultos = document.querySelector('#bultos_' + target.dataset.id_art);
    const unidEnBultos = _convierteEntero(inputBultos.dataset.total_unidades);
    const inpCantUnid = document.querySelector('#unid_' + target.dataset.id_art);
    const dataArtic = _.cloneDeep(_REAL.modelProducto);  // Copio objeto sin referencia (https://www.freecodecamp.org/news/clone-an-object-in-javascript/)
    let cantUnid = _convierteEntero(inpCantUnid.value);
    let costoUnid = Number.parseFloat(inpCantUnid.dataset.costounid);
    let totalPesos = 0;

    if (Number.isNaN(costoUnid)) {
        costoUnid = 0;
    }

    if (_REAL.edita) {
        dataArtic.id = __getIdRegistroDB(target.dataset.id_art);
    }

//console.log('Unidades:', cantUnid, 'Unid. en bultos:', unidEnBultos, 'Costo unid:', costoUnid, 'Cant bultos:', inputBultos.value);

    totalPesos = _.round((cantUnid + unidEnBultos) * costoUnid, 2);

    if (totalPesos > 0) {
        tdTotalUnid.innerHTML = strToCurrency(totalPesos, false, 2);
        // Datos del Articulo
        dataArtic.producto_id = Number.parseInt(target.dataset.id_art);
        dataArtic.codigo = target.dataset.codigo;
        dataArtic.descripcion = inpCantUnid.dataset.descripcion;
        dataArtic.cant_bultos = _convierteEntero(inputBultos.value);
        dataArtic.cant_unid = cantUnid;
        dataArtic.total_unid = unidEnBultos + cantUnid;
        dataArtic.unid_x_bulto = _convierteEntero(inputBultos.dataset.unid_x_bulto);
        dataArtic.costo_bulto = Number.parseFloat(inputBultos.dataset.costobulto);
        dataArtic.costo_unid = costoUnid;
        dataArtic.total = totalPesos;

        _ingresoArticuloAlListado(dataArtic);

    } else {
        tdTotalUnid.innerHTML = '';
        _quitarArticuloDelListado(target.dataset.id_art);   // Si existe...
    }

    return null;
};

const _setTotalesArt = function (target) {
    __total_Unidades(target);
    __total_Pesos(target);
    
    return null;
};

const __getIdRegistroDB = function (producto_id) {
    let idReg = 0;
    const index = _.findIndex(_REAL.articulosPlanilla, 
        function(o) { return o.producto_id == producto_id; }
    );

    if (index >= 0) {
        idReg = parseInt(_REAL.articulosPlanilla[index].id);
    }
    return idReg;
}


export { _convierteEntero, _cambiaColorLineaArt, _setTotalesArt };