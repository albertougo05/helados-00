/**
 * Clases guardar comprobante
 */

import MensajeAlerta from "../../utils/MensajeAlerta";


function _muestraSpinGuardando(show) {
   const spinGuardando = document.querySelector('#spinGuardando');
    if (show) {
        spinGuardando.classList.remove('hidden');
    } else {
        spinGuardando.classList.add('hidden');
    }

    return null;
};


class UpdatePlanilla {
    constructor() {
        this.datosPlanilla = {
            id: _REAL.planilla.id,
            sucursal_id: _REAL.sucursal_id,
            usuario_id: _REAL.usuario_id,
            tipo_toma_stock: document.querySelector('#tipo_toma_stock').value,     // final / parcial
            fecha_toma_stock: document.querySelector('#fecha_toma_stock').value,
            hora_toma_stock: document.querySelector('#hora_toma_stock').value,
            fecha_periodo_stock_final: document.querySelector('#fecha_stock_final').value,
            hora_periodo_stock_final: document.querySelector('#hora_stock_final').value,
            detalle: document.querySelector('#detalle').value
        };
    }

    /**
     * Funciones públicas
    */
    update() {
        _muestraSpinGuardando(true);

        Promise.all([ this._updateDatosPlanilla(), 
                      this._updateDatosArticulos(),
                      this._updateDatosHelados() ])
            .then(function (results) {
                //const planilla = results[0],
                //      articulos = results[1],
                //      helados = results[2];
                //console.log('Resultados:', planilla.data, articulos.data, helados.data);
                _muestraSpinGuardando(false);
                MensajeAlerta('Planilla actualizada con exito !', 'success');
                // A los 5 segundos recarga la pagina
                setTimeout(function () {
                    location.assign(_REAL.pathStockReal);
                }, 5000);
            })
            .catch(function (error) {
                //console.log('Error:', error);
                MensajeAlerta('Error salvando datos planilla !', 'error');
            });

        return null;
    }

    /** 
     * Funciones privadas
     */
    _updateDatosPlanilla() {
        return axios.post(_REAL.pathUpdateDatosPlanilla, this.datosPlanilla);
    }

    _updateDatosArticulos() {
        return axios.post(_REAL.pathUpdateDatosArticulos, _REAL.articulosCargados);
    }

    _updateDatosHelados() {
        return axios.post(_REAL.pathUpdateDatosHelados, _REAL.heladosCargados);
    }
}

export default UpdatePlanilla;