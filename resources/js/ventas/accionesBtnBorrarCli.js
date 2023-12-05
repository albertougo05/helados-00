const _accionesBtnBorrarCli = () => {
    _VENTA.cliente_id = 0;
    const inputCliente = document.querySelector('input#cliente');
    inputCliente.value = "Consumidor final";
    document.querySelector('div#divBtnBorrarClie').classList.add('hidden');
    document.querySelector('#btnBuscarCliente').classList.remove('hidden');
    document.querySelector('input#pagoContado').removeAttribute('disabled');
    const selFormasPago = document.querySelector('#forma_pago_id');
    selFormasPago.removeAttribute('disabled');
    selFormasPago.options[0].selected;
    selFormasPago.selectedIndex = 0;
    _VENTA.inputPagoContado.typedValue = '';
    document.querySelector('label#lblFormaPago').innerText = 'Pago efectivo';
    document.querySelector('#div-vuelto').removeAttribute('disabled');
    document.querySelector('#div-vuelto').classList.remove('hidden');
};

export default _accionesBtnBorrarCli;