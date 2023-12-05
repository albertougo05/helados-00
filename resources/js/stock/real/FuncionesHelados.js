/**
 * 
 * Funciones para listado de Helados
 * 
 */

import { _convierteEntero } from './FuncionesArticulos';
import { strToFloat, strToCurrency } from '../../utils/solo-numeros';
import _ from 'lodash';


// INGRESAR DATOS DE HELADOS A _REAL.modelProducto  (en index.blade.php)


const _convierteFloat = function (valor) {
    let float = Number.parseFloat(valor);

    if (Number.isNaN(float)) {
        float = 0;
    }

    float = _.round(float, 3);

    return float;
};

const _ingresoHeladoAlListado = data => {
    const index = _.findIndex(_REAL.heladosCargados, 
        function(o) { return o.producto_id == data.producto_id; }
    );

    if (index !== -1) {
        _REAL.heladosCargados[index] = data;

        return null;
    }

    _REAL.heladosCargados.push(data);    // Ingresar nuevo Helado al array...

    return null;
}

function _quitarHeladoDelListado(idProd) {
    const index = _.findIndex( _REAL.heladosCargados, 
        function(o) { return o.producto_id == idProd; } 
        );

    if (index > 0) {
        //_REAL.heladosCargados = _REAL.heladosCargados.filter(elem => elem.producto_id !== idProd);
        // Pone valores en cero (el registro en BD sigue existiendo)...
        _REAL.heladosCargados[index].costo_caja = 0;
        _REAL.heladosCargados[index].costo_kilo = 0;
        _REAL.heladosCargados[index].kilos_latas_abiertas = 0;
        _REAL.heladosCargados[index].kilos_latas_abiertas_sin_envase = 0;
        _REAL.heladosCargados[index].kilos_latas_cerradas = 0;
        _REAL.heladosCargados[index].kilos_totales = 0;
        _REAL.heladosCargados[index].latas_cerradas = 0;
        _REAL.heladosCargados[index].peso_caja = 0;
        _REAL.heladosCargados[index].peso_envase = 0;
        _REAL.heladosCargados[index].total = 0;
    }

    return null;
}


const FuncionesHelados = function () {
        this.inputCajas = null;
        this.inputKilos = null;
        this.tdKgsNetos = null;
        this.tdTotalPesos = null;
        this.pesoEnvase = _REAL.peso_envase;
};

FuncionesHelados.prototype = function () {
    var cambiaColorLinea = function (target) {
        let cajas = _convierteEntero(this.inputCajas.value);
        let kgsAbs = _convierteEntero(this.inputKilos.value);
        const td = target.parentNode;
        const tr = td.parentNode;

        if (cajas === 0 && kgsAbs === 0) {
            tr.classList.remove('lineaConDatoGreen');
        } else {
            if (tr.classList.contains('lineaConDatoGreen')) {
                return null;
            } else {
                tr.classList.add('lineaConDatoGreen');
            }
        }

        return null;
    },

    setInputs = function (id) {
        this.inputCajas = document.querySelector('#cajas_' + id);
        this.inputKilos = document.querySelector('#kgs_abs_' + id);
        this.tdKgsNetos = document.querySelector('#kgs_netos_' + id);
        this.tdTotalPesos = document.querySelector('#pesos_total_'+id);
    },

    setTotales = function (target) {
        _total_kilos.call(this, target);
        _total_Pesos.call(this, target);
        
        return null;
    },

    setData = function (dataClon, target) {
        this.dataHelado = _.cloneDeep(dataClon);
        const inpCajas = document.querySelector('#cajas_' + target.dataset.id_hela);

        if (_REAL.edita) {
            this.dataHelado.id = _getIdRegistroDB.call(this, target.dataset.id_hela);
        }
        this.dataHelado.producto_id = Number.parseInt(target.dataset.id_hela);
        this.dataHelado.codigo = target.dataset.codigo;
        this.dataHelado.descripcion = inpCajas.dataset.descripcion;

        return null;
    },

    _total_kilos = function (target) {
        const targetId = target.id.substring(0,5);
        let cantCajas = _convierteEntero(this.inputCajas.value),
            kgsCajas = 0,
            total = 0,
            pesoCaja = 0,
            kilosAbs = _.round(strToFloat(this.inputKilos.value), 3);

        if (targetId === 'cajas') {
            pesoCaja = _convierteFloat(this.inputCajas.dataset.peso_caja);
            //console.log('Target id:', targetId, 'Peso caja:', pesoCaja);
            pesoCaja = _.round(pesoCaja, 3);
            kgsCajas = _.round(cantCajas * pesoCaja, 3);
            this.inputCajas.dataset.kilos_cajas = kgsCajas;
        } else {
            kgsCajas = _convierteFloat(this.inputCajas.dataset.kilos_cajas);
        }

        if (kilosAbs > 0) {
            kilosAbs = _.round(kilosAbs - this.pesoEnvase, 3);     // Resto peso envase
        }

        total = _.round(kgsCajas + kilosAbs, 3);
        
        //console.log('cant cajas:', cantCajas, ' / kilos cajas:', kgsCajas, ' / kilos caja ab.:', kilosAbs, 'Total:', total);

        if (total > 0) {
            this.tdKgsNetos.innerHTML = strToCurrency(total, false, 3);

        } else {
            this.tdKgsNetos.innerHTML = '';
        }

        return null;
    },

    _total_Pesos = function (target) {
        const inpCajas = document.querySelector('#cajas_' + target.dataset.id_hela),
              kilosEnCajas = _convierteFloat(this.inputCajas.dataset.kilos_cajas),
              kilosCajasAbiertas = _.round(strToFloat(this.inputKilos.value), 3),
              costo_x_caja = _convierteFloat(this.inputCajas.dataset.costo_caja),
              peso_caja = _convierteFloat(this.inputCajas.dataset.peso_caja),
              costo_x_kilo = _.round((costo_x_caja / _.round((peso_caja - this.pesoEnvase), 2)), 2);
              //costo_x_kilo = _.round((costo_x_caja / peso_caja), 2);

        let totalPesos = 0,
            kilosAbs = 0;

        if (kilosCajasAbiertas > 0) {
            kilosAbs = _.round((strToFloat(this.inputKilos.value) - this.pesoEnvase), 2);
        }

        totalPesos = _.round(((kilosEnCajas + kilosAbs) * costo_x_kilo), 2);

        if (totalPesos > 0) {
            this.tdTotalPesos.innerHTML = strToCurrency(totalPesos, false, 2);
            // Ingreso helado al listado...
            this.dataHelado.costo_caja = costo_x_caja;
            this.dataHelado.peso_caja = _convierteFloat(inpCajas.dataset.peso_caja);
            this.dataHelado.costo_kilo = costo_x_kilo;
            this.dataHelado.latas_cerradas = _convierteFloat(inpCajas.value);
            this.dataHelado.kilos_latas_cerradas = kilosEnCajas;
            this.dataHelado.kilos_latas_abiertas = _.round(strToFloat(this.inputKilos.value), 3);
            this.dataHelado.kilos_latas_abiertas_sin_envase = kilosAbs;
            this.dataHelado.kilos_totales = _.round((kilosEnCajas + kilosAbs), 3);
            this.dataHelado.total = totalPesos;
            //console.log('Data helado:', this.dataHelado);
            _ingresoHeladoAlListado(this.dataHelado);

        } else {
            this.tdTotalPesos.innerHTML = '';
            _quitarHeladoDelListado(target.dataset.id_hela);   // Si existe...
        }

        return null;
    },

    _getIdRegistroDB = function(producto_id) {
        let idReg = 0;
        const index = _.findIndex(_REAL.heladosPlanilla, 
            function(o) { return o.producto_id == producto_id; }
        );

        if (index >= 0) {
            idReg = parseInt(_REAL.heladosPlanilla[index].id);
        }

        return idReg;
    }


    // public members
    return {
        cambiaColorLinea: cambiaColorLinea,
        setInputs: setInputs,
        setData: setData,
        setTotales: setTotales
    };
}();


export default FuncionesHelados;