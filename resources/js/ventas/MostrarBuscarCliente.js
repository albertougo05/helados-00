/**
 * Manejo Modal para buscar cliente
 * 
 */

// Global para eventos close
var _modal2  = document.querySelector('.modal-2');

export default class MostrarBuscarCliente {
    constructor() {
        this.closeModal2 = document.querySelectorAll('.close-modal-2');
    }

    inicio() {
        this._setBtnsCloseModal();      // Activa botones de cerrar el modal
        _modal2.classList.remove('hidden');      // Muestra el modal
    }

    _setBtnsCloseModal() {  // Evento click en boton Cancela y X
        this.closeModal2.forEach(close => {
            close.addEventListener('click', function() {
                _modal2.classList.add('hidden');
            });
        });
    }
}