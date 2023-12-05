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


class GuardarPlanilla {
    constructor() {
        this.datosPlanilla = {
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
    inicio() {
        _muestraSpinGuardando(true);

        Promise.all([ this._guardarDatosPlanilla(), 
                      this._guardarDatosArticulos(),
                      this._guardarDatosHelados() ])
            .then(function (results) {
                const planilla = results[0],
                      articulos = results[1],
                      helados = results[2];

                //console.log('Resultados:', planilla.data, articulos.data, helados.data);

                _muestraSpinGuardando(false);
                MensajeAlerta('Planilla guardada con exito !', 'success');
                // A los 3 segundos recarga la pagina
                setTimeout(function () {
                    location.assign('/stock/real/planilla');
                }, 3000);
            })
            .catch(function (error) {
                console.log('Error:', error);
                MensajeAlerta('Error salvando datos planilla !', 'error');
            });

        return null;
    }


    /** 
     * Funciones privadas
     */
    _guardarDatosPlanilla() {
        return axios.post(_REAL.pathDatosPlanilla, this.datosPlanilla);
    }

    _guardarDatosArticulos() {
        return axios.post(_REAL.pathDatosArticulos, _REAL.articulosCargados);
    }

    _guardarDatosHelados() {
        return axios.post(_REAL.pathDatosHelados, _REAL.heladosCargados);
    }
}

export default GuardarPlanilla;