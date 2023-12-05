/**
 * Busca datos de comprobantes
 * 
 */
import DibujarTablaComprobs from './DibujarTablaComprobs';


export default class BuscaComprobantes {
    constructor() {
        this.turno_cierre_id = 0;
        this.suc_id = TURNO._sucursal_id;
        this.caja_nro = TURNO._caja_nro;
        this.dibujarTabla = new DibujarTablaComprobs();
    }

    setTurnoId(id) {
        this.turno_cierre_id = id;
    }

    getComprobs() {
        //let _comprobantes = [];
        let este = this;
        const options = {
            params: {
                suc_id: this.suc_id,
                caja_nro: this.caja_nro,
                turno_cierre_id: this.turno_cierre_id,
            }
        };

        this._spinEspera();

        axios.get(TURNO._pathBuscarComprobs, options)
            .then(response => {
                //console.log('Comprobantes:', response.data);
                if (response.data[0] !== 'Sin datos') {
                    this.dibujarTabla.iniciar(response.data);
                } 
                este._spinEspera();
            })
            .catch(function (e) {
                este._spinEspera();
                alert('No se pudo obtener datos');
            });
    }

    _spinEspera() {
        const modalSpin = document.querySelector('.modal-espera');
        modalSpin.classList.toggle('hidden');
    }
}