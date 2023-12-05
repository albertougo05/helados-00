//
// guargaComprobante.js
//
import { forEach } from 'lodash';
import { fechaActual, horaActual } from '../utils/transformFechas';
import MensajeAlerta from '../utils/MensajeAlerta';
import muestraImpresionYSale from './muestraImpresionYSale';


const _getDatosComprobante = () => {
    const fechaHora = fechaActual('en') + ' ' + horaActual();

    return {
        usuario_id: _STOCK.usuario_id,
        fecha_hora: _STOCK._esEdit ? _STOCK.comprobante.fecha_hora : fechaHora,
        fecha: _STOCK._esEdit ? _STOCK.comprobante.fecha : fechaActual('en'),
        hora: _STOCK._esEdit ? _STOCK.comprobante.hora : horaActual(),
        sucursal_id: _STOCK.sucursal_id,
        turno_id: _STOCK.turno_id,
        turno_sucursal: _STOCK.turno_sucursal,
        tipo_movimiento_id: parseInt(document.querySelector('#selTipoMovim').value),
        nro_comprobante: _STOCK.comprobante_id,
        importe: 0,
        observaciones: document.querySelector('#observaciones').value,
        estado: _STOCK._esEdit ? 2 : 1,
    };
};

const _getCantidadTotalUnid = (prod_a_stock) => {
    if (_STOCK.grupos_helado.includes(prod_a_stock.grupo_id)) {
        return 0;       // Si es de grupo helado, retorna 0. (Calcula por peso)
    }
    if (prod_a_stock.unid_medida === 'UN') {
        return parseInt(prod_a_stock.cantidad);
    } else if (prod_a_stock.unid_medida === 'BU') {
        return prod_a_stock.unidades_x_bulto * prod_a_stock.cantidad;
    }
};

const _getPesoNetoTotal = (prod_a_stock) => {
    if (!_STOCK.grupos_helado.includes(prod_a_stock.grupo_id)) {
        return 0;       // Si el grupo,NO ES helado, retona 0.
    }
    let peso = 0;
    switch (prod_a_stock.unid_medida) {
        case 'CA':
            peso = prod_a_stock.cantidad * prod_a_stock.peso_materia_prima;
            break;
        case 'KI':
            peso = prod_a_stock.cantidad;
            break;
    }

    return peso;
};

const _getCostoUnitario = (prod_a_stock) => {
    let costo = 0;

    switch (prod_a_stock.unid_medida) {
        case 'UN':
            costo = prod_a_stock.costo_x_unidad;
            break;
        case 'BU':
                costo = prod_a_stock.costo_x_unidad;
                break;
        case 'KI':
            costo = prod_a_stock.costo_x_bulto / prod_a_stock.peso_materia_prima;
            break;
        case 'CA':
            costo = prod_a_stock.costo_x_bulto;
            break;
    }

    return Math.round(costo * 100) / 100;
};

const _getCostoTotal = (prod, cant_unid, costo_unit, peso_neto) => {
    let costo = 0;

    if (_STOCK.grupos_helado.includes(prod.grupo_id)) {       // Si es del grupo helados...
        if (prod.unid_medida === 'CA') {
            costo = prod.cantidad * costo_unit;
        } else {
            costo = peso_neto * costo_unit;
        }
    } else {
        if (prod.unid_medida === 'UN') {
            costo = costo_unit * cant_unid;
        } else if (prod.unid_medida === 'BU') {
            costo = costo_unit * cant_unid;       //prod.cantidad;
        }
    }

    return Math.round(costo * 100) / 100;
};

const _getDetalleComprob = () => {
    let detalle = [],
        cant_total_unid = 0,
        peso_neto_total = 0,
        costo_unitario = 0;

    forEach(_STOCK.productos_a_stock, function (prod) {
        cant_total_unid = _getCantidadTotalUnid(prod),
        peso_neto_total = _getPesoNetoTotal(prod);
        costo_unitario = _getCostoUnitario(prod);

        const lineaProd = {
            id: _STOCK._esEdit ? prod.id : 0,
            stock_comprob_id: _STOCK.comprobante_id,
            tipo_movim_id: parseInt(document.querySelector('#selTipoMovim').value),
            producto_id: prod.producto_id,
            codigo: prod.codigo,
            grupo_id: prod.grupo_id,
            descripcion: prod.descripcion,
            articulo_indiv_id: prod.articulo_indiv_id,
            sucursal_id: _STOCK.sucursal_id,
            unidad_medida: prod.unid_medida,
            cantidad: prod.cantidad,
            cant_total_unid: cant_total_unid,
            peso_neto_total: peso_neto_total,
            costo_unitario: costo_unitario,
            costo_total: _getCostoTotal(prod, cant_total_unid, costo_unitario, peso_neto_total),
            observaciones: prod.observaciones,
        };
        detalle.push(lineaProd);
    });

    return {
        comprob_id: _STOCK.comprobante_id,
        detalle: detalle
    };
};

const _muestraSpinGuardando = (show) => {
    const spinGuardando = document.querySelector('#spinGuardando');
    if (show) {
        spinGuardando.classList.remove('hidden');
    } else {
        spinGuardando.classList.add('hidden');
    }

    return null;
};

const _saveDataComprob = () => {
    if (_STOCK._esEdit) {       // Si EDITA hace PUT y actualiza solo observaciones
        const url = _STOCK.pathUpdateComprob.slice(0, -1) + _STOCK.comprobante_id;

        return axios.put(url, { 
            id: _STOCK.comprobante_id, 
            observaciones: document.querySelector('#observaciones').value }
        );
    }

    const dataComprob = _getDatosComprobante();
    const url = _STOCK.pathGuardarComprob;

    return axios.post(url, dataComprob);
};

const _saveDetalleArticulos = () => {
    const detalle = _getDetalleComprob();
    let url = _STOCK.pathGuardarDetalle;

    if (_STOCK._esEdit) {
        url = _STOCK.pathUpdateDetalle.slice(0, -1) + _STOCK.comprobante_id;

        console.log('Detalle:', detalle)

        return axios.put(url, detalle);
    }

    return axios.post(url, detalle);
};


const guardaComprobante = () => {
    _muestraSpinGuardando(true);

    Promise.all([ _saveDataComprob(), 
                  _saveDetalleArticulos() ])
        .then(function (results) {
            const comprob = results[0],
                  articulos = results[1];
            setTimeout(function () {    // A los 2 segundos muestra impresion o imprime
                _muestraSpinGuardando(false);
                MensajeAlerta('Movimiento stock guardado !!', 'success');
                muestraImpresionYSale();
            }, 2000);
        })
        .catch(function (error) {
            //console.log('Error:', error);
            MensajeAlerta('Error salvando datos stock !', 'error');
        });

    return null;
};


export default guardaComprobante;