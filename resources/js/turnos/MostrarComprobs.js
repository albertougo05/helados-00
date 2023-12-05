/**
 * 
 * Muestra comprobantes de ventas
 * 
 */

import BuscarComprobantes from './BuscarComprobantes';


var _modal  = document.querySelector('.modal');

export default class MostrarComprobs {
    constructor() {
        this.comprobantes = [];
        this.turnoId = 0;
        this.closeModal = document.querySelectorAll('.close-modal');
        this._setBtnsCloseModal();      // Activa botones de cerrar el modal
    }

    setTurnoId(id) {
        this.turnoId = id;
    }

    mostrarModal () {
        // Clase que busca los datos...
        const buscarComprobs = new BuscarComprobantes();
        buscarComprobs.setTurnoId(this.turnoId);
        buscarComprobs.getComprobs();

        _modal.classList.remove('hidden');
    }

    _setBtnsCloseModal() {
        // Evento click en boton Salir y X
        this.closeModal.forEach(close => {
            close.addEventListener('click', function(e) {
                _modal.classList.add('hidden');
            });
        });
    }

}
