import IMask from 'imask';
import masks from "../utils/masks";
import checkDataForm from './checkDataForm';
import formatMinutos from './formatMinutos';
import MensajeAlerta from '../utils/MensajeAlerta';
import { fechaActual } from '../utils/transformFechas';
//import axios from 'axios';



// Inicializar form
const inputHoraApertura = document.getElementById('apertura_hora');
const inputSaldoInic = IMask(document.getElementById('saldo_inicio'), masks.currency);
document.getElementById('apertura_fecha').value = fechaActual('es');
document.querySelector('#empleado').value = TURNO._usuario_nombre;
inputSaldoInic.typedValue = TURNO._saldo_inicio;

const _hora = new Date();
inputHoraApertura.value = formatMinutos(_hora.getHours()) + ':' + formatMinutos(_hora.getMinutes());

// Change en select caja número
const selectCajaNro = document.querySelector('#caja_nro');
selectCajaNro.addEventListener('change', async function (e) {
    const cajaSel = e.target.value;
    let url = `${TURNO._pathBuscarArqueo}?suc_id=${TURNO._sucursal_id}`;
    url += '&caja_nro=' + cajaSel;

    // Buscar el arqueo del número de caja seleccionada
    try {
        const res = await axios.get(url);
        inputSaldoInic.typedValue = res.data.arqueo;
    } catch (error) {
        MensajeAlerta('No se encontró arqueo caja anterior !');
    }
});


// Click en botón Confirma
const btnConfirma = document.querySelector('#btnConfirma');
btnConfirma.addEventListener('click', function (e){
    e.preventDefault();

    const checkData = checkDataForm(inputHoraApertura.value, inputSaldoInic.value);

    if (checkData.result) {
        const hora = document.querySelector('#apertura_hora').value;
        document.querySelector('#apertura_fecha_hora').value = fechaActual('en') + ' ' + hora;
        document.querySelector('#caja').value = document.querySelector('#saldo_inicio').value;
        e.target.disabled = true;
        document.querySelector('#formTurno').submit();
    } else {
        MensajeAlerta(checkData.message);
        document.querySelector(checkData.input).focus();
    }
});
