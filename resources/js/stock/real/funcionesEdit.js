/**
 * Funciones para EDITAR planilla de stock real
 */

import { strToCurrency } from '../../utils/solo-numeros';
import { _cambiaColorLineaArt } from './FuncionesArticulos';
import FuncionesHelados from './FuncionesHelados';


const funsHela = new FuncionesHelados();

const funcionesEdit = function () {
    // Variables privadas
    var setTipoTomaSock = (planilla, selTipo) => {
        selTipo.value = planilla.tipo_toma_stock;

        return null;
    },

    arreglaArrayArtPlanilla = () => {
        return _REAL.articulosPlanilla.map(el => {
            return {
                id: el.id,
                sucursal_id: el.sucursal_id,
                producto_id:  el.producto_id,
                codigo: el.codigo,
                planilla_id: el.planilla_id,
                descripcion: el.descripcion,
                cant_bultos: el.cant_bultos,
                cant_unid: el.cant_unid,
                total_unid: el.total_unid,
                unid_x_bulto: el.unid_x_bulto,
                costo_bulto: Number.parseFloat(el.costo_bulto),
                costo_unid: Number.parseFloat(el.costo_unid),
                total: Number.parseFloat(el.total)
            }
        });
    },

    cargaArticulosPlanilla = () => {
        _REAL.articulosPlanilla.map(elem => {
            const inpTotUnid = document.getElementById('total_unid_' + elem.producto_id),
                  inpPesosBulto = document.getElementById('pesos_x_bulto_' + elem.producto_id),
                  inpPesosTotal = document.getElementById('pesos_total_' + elem.producto_id);;

            _rellenaInputBulto.call(this, elem);
            _rellenaInputUnid.call(this, elem);

            inpTotUnid.innerHTML = elem.total_unid;
            inpPesosBulto.innerHTML = strToCurrency(elem.costo_bulto, false, 2);
            inpPesosTotal.innerHTML = strToCurrency(elem.total, false, 2);
      
            _REAL.articulosCargados.push(elem);    // Ingresar nuevo Articulo al array...
        });
    },

    arreglaArrHeladosPlanilla = () => {
        return _REAL.heladosPlanilla.map( el => {
            return {
                id: el.id,
                sucursal_id: el.sucursal_id,
                producto_id:  el.producto_id,
                codigo: el.codigo,
                planilla_id: el.planilla_id,
                descripcion: el.descripcion,
                costo_caja: Number.parseFloat(el.costo_caja),
                peso_caja: Number.parseFloat(el.peso_caja),
                peso_envase: Number.parseFloat(el.peso_envase),
                costo_kilo: Number.parseFloat(el.costo_kilo),
                latas_cerradas: el.latas_cerradas,
                kilos_latas_cerradas: Number.parseFloat(el.kilos_latas_cerradas),
                kilos_latas_abiertas: Number.parseFloat(el.kilos_latas_abiertas),
                kilos_latas_abiertas_sin_envase: Number.parseFloat(el.kilos_latas_abiertas_sin_envase),
                kilos_totales: Number.parseFloat(el.kilos_totales),
                total: Number.parseFloat(el.total)
            };
        });
    },

    cargaHeladosPlanilla = () => {
        _REAL.heladosPlanilla.map(elem => {
            const tdPesoEnv = document.getElementById('peso_env_' + elem.producto_id),
                  tdKgsNetos = document.getElementById('kgs_netos_' + elem.producto_id),
                  //tdPesosCaja = document.getElementById('kgs_netos_' + elem.producto_id),
                  tdPesosTotal = document.getElementById('pesos_total_' + elem.producto_id);

            _rellenaInputCajas.call(this, elem);
            _rellenaInputCajasAbiertas.call(this, elem);

            //console.log('Kilos totales:', elem.kilos_totales);

            tdPesoEnv.innerHTML = '- ' + strToCurrency(elem.peso_envase, false, 3);
            tdKgsNetos.innerHTML = strToCurrency(elem.kilos_totales, false, 3);
            //tdPesosCaja.innerHTML = strToCurrency(elem.costo_caja, false, 2);
            tdPesosTotal.innerHTML = strToCurrency(elem.total, false, 2);

            _REAL.heladosCargados.push(elem);    // Ingresar nuevo Articulo al array...
        });
    },

    _rellenaInputBulto = elem => {
        const inpBulto = document.getElementById('bultos_' + elem.producto_id);

        inpBulto.value = elem.cant_bultos ? elem.cant_bultos : '';
        inpBulto.dataset.id_art = elem.producto_id;
        inpBulto.dataset.codigo = elem.codigo;
        inpBulto.dataset.costobulto = elem.costo_bulto;
        inpBulto.dataset.unid_x_bulto = elem.unid_x_bulto;
        inpBulto.dataset.total_unidades = elem.total_unid;

        _cambiaColorLineaArt(inpBulto);
    },
    
    _rellenaInputUnid = elem => {
        const inpUnid = document.getElementById('unid_' + elem.producto_id);

        inpUnid.value = elem.cant_unid ? elem.cant_unid : '';
        inpUnid.dataset.id_art = elem.producto_id;
        inpUnid.dataset.codigo = elem.codigo;
        inpUnid.dataset.costounid = elem.costo_unid;
        inpUnid.dataset.descripcion = elem.descripcion;

        _cambiaColorLineaArt(inpUnid);
    },
    
    _rellenaInputCajas = elem => {      // Cajas === Latas cerradas
        const inpCajas = document.getElementById('cajas_' + elem.producto_id);

        inpCajas.value = elem.latas_cerradas ? elem.latas_cerradas : '';
        inpCajas.dataset.id_hela = elem.producto_id;
        inpCajas.dataset.codigo = elem.codigo;
        inpCajas.dataset.costo_caja = elem.costo_caja;
        inpCajas.dataset.peso_caja = elem.peso_caja;
        inpCajas.dataset.kilos_cajas = elem.kilos_latas_cerradas;
        inpCajas.dataset.descripcion = elem.descripcion;

        funsHela.setInputs(elem.producto_id);
        funsHela.cambiaColorLinea(inpCajas);
    },

    _rellenaInputCajasAbiertas = elem => {
        const inpCajaAb = document.getElementById('kgs_abs_' + elem.producto_id);

        //console.log('Kilos cajas abiertas:', elem.kilos_latas_abiertas, typeof elem.kilos_latas_abiertas);

        // strToCurrency(elem.costo_bulto, false, 2);
        if (elem.kilos_latas_abiertas > 0) {
            inpCajaAb.value = strToCurrency(elem.kilos_latas_abiertas, false, 2);
        } else {
            inpCajaAb.value = '';
        }
        inpCajaAb.dataset.id_hela = elem.producto_id;
        inpCajaAb.dataset.codigo = elem.codigo;
        inpCajaAb.dataset.costo_kilo = elem.costo_kilo;

        funsHela.setInputs(elem.producto_id);
        funsHela.cambiaColorLinea(inpCajaAb);
    };

    // Retorno funciones publicas
    return {
        setTipoTomaSock: setTipoTomaSock,
        arreglaArrayArtPlanilla: arreglaArrayArtPlanilla,
        cargaArticulosPlanilla: cargaArticulosPlanilla,
        arreglaArrHeladosPlanilla: arreglaArrHeladosPlanilla,
        cargaHeladosPlanilla: cargaHeladosPlanilla,

    };
}();

export default funcionesEdit;