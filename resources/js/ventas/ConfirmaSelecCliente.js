/**
 * Acciones boton confirma selección de cliente
 * 
 */

export default class ConfirmaSelecCliente {
    constructor(inputPagoContado) {
        this.modal2 = document.querySelector('.modal-2');
        this.inputClie =  document.querySelector('input#cliente');
        this.inputPago = inputPagoContado;
        this.lblFormaPago = document.querySelector('label#lblFormaPago');
    }

    inicio() {
        //let clienteSel = _VENTA.data_clie.nombre + " " + _VENTA.data_clie.direccion;
        //clienteSel += " - " + _VENTA.data_clie.localidad;
        let clienteSel = _VENTA.data_clie.nombre + " - " + _VENTA.data_clie.localidad;
        this.inputClie.value = clienteSel
        this._limpiarDatosModal();
        this._cambiarFormaPago();
        this._desHablitarPago();
        this._totalCompEsTotalPago();
        this._mostrarBotonBorrarClie();
        _VENTA.cliente_id = _VENTA.data_clie.id;

        this.modal2.classList.add('hidden');      // Cerrar el modal
    }

    _limpiarDatosModal() {
        const dir = document.querySelector('p#dataDir');
        dir.textContent = "Dirección:";
        const loc = document.querySelector('p#dataLoc');
        loc.textContent = "Localidad:";
        const select = document.querySelector('#selectModalCliente');
        select.value = 0;
    }

    _cambiarFormaPago() {
        const select = document.querySelector('#forma_pago_id');
        select.value = 4;
        select.disabled = true;
        _VENTA.forma_pago = 4;
    }

    _desHablitarPago() {
        const inputPago = document.querySelector('input#pagoContado');
        inputPago.setAttribute("disabled", "");
        this.lblFormaPago.innerText = 'A Cta. Cte.';
        const divVuelto = document.querySelector('#div-vuelto');
        divVuelto.classList.add('hidden');

    }

    _mostrarBotonBorrarClie() {
        const btnBuscarCli = document.querySelector('#btnBuscarCliente');
        btnBuscarCli.classList.add('hidden');
        const divBtnBorrarClie = document.querySelector('#divBtnBorrarClie');
        divBtnBorrarClie.classList.remove('hidden');
    }

    _totalCompEsTotalPago() {
        if (_VENTA.total > 0) {
            this.inputPago.typedValue = _VENTA.total;
            const inputVuelto = document.querySelector('#vuelto');
            inputVuelto.value = '';
        }
    }
}