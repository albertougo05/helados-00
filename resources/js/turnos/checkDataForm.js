var checkDataForm = (hora, saldo) => {
    let data = {
        result: false,
        message: '',
        input: '',
    }

    if (hora === '__:__') {
        data.message = 'Ingrese una hora !';
        data.input = '#apertura_hora';
    } else if (saldo === '') {
        data.message = 'Ingrese importe saldo inicial !';
        data.input = '#saldo_inicio'
    } else {
        data.result = true;
    }

    return data;
}

export default checkDataForm;