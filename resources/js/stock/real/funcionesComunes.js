/**
 * Funciones comunes a creat y edit
 */

import { _convierteEntero, _cambiaColorLineaArt, _setTotalesArt } from './FuncionesArticulos';
import FuncionesHelados from './FuncionesHelados';
import MensajeAlerta from "../../utils/MensajeAlerta";
import { strToCurrency } from '../../utils/solo-numeros';


const funsHela = new FuncionesHelados();


const funcionesComunes = function () {
    const init = () => {
        _REAL.inputs_kilos_hela = [];  // Para guardar inpust iMask

        _REAL.keydown = function (key) {
            // Admite solo números, tab, flechas, delete y backspace
            return (key >= '0' && key <= '9') ||
                ['Tab','ArrowLeft','ArrowRight','Delete','Backspace'].includes(key);
        };

        _REAL.bultos_onblur = function (e) {
            const cantBultos = _convierteEntero(e.target.value);

            if (cantBultos) {
                const unidXbulto = _convierteEntero(e.target.dataset.unid_x_bulto);
                e.target.dataset.total_unidades = unidXbulto * cantBultos;
            } else {
                e.target.dataset.total_unidades = 0;
            }

            _cambiaColorLineaArt(e.target);
            _setTotalesArt(e.target);
            sumaTotalPlanilla();

            return null;
        };
        
        _REAL.unid_onblur = function (e) {
            _cambiaColorLineaArt(e.target);
            _setTotalesArt(e.target);
            sumaTotalPlanilla();

            return null;
        };

        _REAL.hela_onblur = function (e) {
            funsHela.setInputs(e.target.dataset.id_hela);
            funsHela.setData(_REAL.modelHelado, e.target);
            funsHela.cambiaColorLinea(e.target);
            funsHela.setTotales(e.target);

            return null;
        }

        _REAL.articulosCargados = [];
        _REAL.heladosCargados = [];

        _REAL.modelProducto = {
            id: 0,
            planilla_id: _REAL.id,
            sucursal_id: _REAL.sucursal_id,
            producto_id: '',
            codigo: '',
            descripcion: '',
            cant_bultos: 0,
            cant_unid: 0,
            total_unid: 0,
            unid_x_bulto: 0,
            costo_bulto: 0,
            costo_unid: 0,
            total: 0 };

        _REAL.modelHelado = {
            id: 0,
            planilla_id: _REAL.id,
            sucursal_id: _REAL.sucursal_id,
            producto_id: '',
            codigo: '',
            descripcion: '',
            costo_caja: 0,
            peso_caja: 0,
            costo_kilo: 0,
            peso_envase: _REAL.peso_envase,
            latas_cerradas: 0,
            kilos_latas_cerradas: 0,
            kilos_latas_abiertas: 0,
            kilos_latas_abiertas_sin_envase: 0,
            kilos_totales: 0,
            total: 0 };

        _REAL.opcionesAlert = {
            title: '¿ Seguro confirma planilla ?',
            text: "Seleccione opción!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#38a169 ',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, confirma!',
            cancelButtonText: 'Cancela'
        };

        _REAL.checkCargaProds = function () {
            const cantArts = _REAL.articulosCargados.length;
            const cantHela = _REAL.heladosCargados.length;

            if (cantArts === 0 && cantHela === 0) {
                MensajeAlerta('No hay productos cargados !!', 'error');

                return false;
            }

            return true;
        };

    }, 

    sumaTotalPlanilla = () => {
        const sumaArticulos = _REAL.articulosCargados.reduce((n, {total}) => n + total, 0),
              sumaHelados   = _REAL.heladosCargados.reduce((n, {total}) => n + total, 0),
              totalPlanilla = sumaArticulos + sumaHelados;
    
        document.querySelector('#totalPlanilla').value = strToCurrency(totalPlanilla);
    
        return null;
    };

    return {
        init: init,
        sumaTotalPlanilla: sumaTotalPlanilla
    };

} ();


export default funcionesComunes;