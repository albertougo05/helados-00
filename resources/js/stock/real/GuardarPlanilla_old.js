/**
 * Clases guardar comprobante
 */

import MensajeAlerta from "../../utils/MensajeAlerta";


class GuardarProductos {
    constructor() {
        this.listaArticulos = _REAL.articulosCargados;
    }

    inicio() {
        return this._guardarDatosProductos();
    }

    checkHayArticulos() {
        return _REAL.articulosCargados.length > 0;
    }

    async _guardarDatosProductos() {
        const sendDatos = await axios.post(_REAL.pathDatosArticulos, this.listaArticulos);
        console.log('Guardando productos...')
        
        return sendDatos.data;
    }
}

class GuardarHelados {
    constructor() {
        this.listaHelados = _REAL.heladosCargados;
    }

    inicio() {
        return this._guardarDatosHelados();
    }

    checkHayHelados() {
        return _REAL.heladosCargados.length > 0;
    }

    async _guardarDatosHelados() {
        const sendDatos = await axios.post(_REAL.pathDatosHelados, this.listaHelados);
        console.log('Guardando helados...')
        
        return sendDatos.data;
    }
}



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
   inicio(muestraSpinGuardando) {

        this._guardarDatosPlanilla()
            .then(data => {

                if (data.estado === 'ok') {
                    // Salvar datos de productos
                    const salvarProds = new GuardarProductos();
                    if (salvarProds.checkHayArticulos()) {      // Verifica que haya articulos
                        salvarProds.inicio()
                            .then(data => {
                                console.log('Resultado salvar articulos', data);
    
                                if (data.estado === 'ok') {
                                    // Salvar datos helados
                                    const salvarHelas = new GuardarHelados();
                                    //if ()
                                    salvarHelas.inicio()
                                        .then(data => {
                                            console.log('Resultado salvar helados', data);
                                            
                                            if (data.estado === 'error') {
                                                console.log('Error salvando helados:', data.estado);
                                                MensajeAlerta('Error salvando helados !', 'error');
                                            } else {
                                                muestraSpinGuardando(false);
                                                MensajeAlerta('Planilla guardada con exito !', 'success');
                                            }
                                        });
    
                                 } else {
                                    console.log('Error salvando artículos:', data.estado);
                                    MensajeAlerta('Error salvando articulos !', 'error');
                                }
                            });
                    } else {    // si no hay articulos, salvo los helados
                        // Salvar datos helados
                        const salvarHelas = new GuardarHelados();

                        if (salvarHelas.checkHayHelados()) {
                            salvarHelas.inicio()
                                .then(data => {
                                    console.log('Resultado salvar helados', data);
                                    
                                    if (data.estado === 'error') {
                                        console.log('Error salvando helados:', data.estado);
                                        MensajeAlerta('Error salvando helados !', 'error');
                                    } else {
                                        muestraSpinGuardando(false);
                                        MensajeAlerta('Planilla guardada con exito !', 'success');
                                    }
                                });
                        }
                    }

                } else {
                    console.log('Error salvando planilla:', data.estado);
                    MensajeAlerta('Error salvando planilla !', 'error');
                }
            });

        return null;
    }

    /** 
     * Funciones privadas
     */
    async _guardarDatosPlanilla() {
        const sendDatos = await axios.post(_REAL.pathDatosPlanilla, this.datosPlanilla);

        return sendDatos.data;
    }

}

export default GuardarPlanilla;
