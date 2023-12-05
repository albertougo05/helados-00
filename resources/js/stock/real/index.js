import IMask from 'imask';
import Swal from 'sweetalert2';
import masks from "../../utils/masks";
import { fechaActual, horaActual } from '../../utils/transformFechas';
import GuardarPlanilla from './GuardarPlanilla';
import funcionesComunes from './funcionesComunes';


funcionesComunes.init();


//
// Despues de cargar la página...
//
window.onload = function() {
    // Inicializar form
    const inputHoraParcial = IMask(document.getElementById('hora_toma_stock'), masks.hora);
    inputHoraParcial.typedValue = horaActual();
    const inputHoraFinal = IMask(document.getElementById('hora_stock_final'), masks.hora);
    inputHoraFinal.typedValue = '00:00';

    document.getElementById('fecha_toma_stock').value = fechaActual('en');

    // Inicializar inputs de helados a tres decimales
    const inputsKilos = document.querySelectorAll("input[id^='kgs_abs_']");

    inputsKilos.forEach(elem => {
        _REAL.inputs_kilos_hela.push(IMask(elem, masks.tresDecimales));
    });

    const selTipoTomaSock = document.querySelector('#tipo_toma_stock');
    // Si la toma es final habilita los inputs
    selTipoTomaSock.addEventListener('change', (e) => {
        const valor = e.target.value;
        const inpFechaFinal = document.getElementById('fecha_stock_final');
        const inpHoraFinal = document.getElementById('hora_stock_final');

        if (valor === 'final') {
            inpFechaFinal.disabled = false;
            inpHoraFinal.disabled = false;
            inpFechaFinal.focus();
        } else {
            inpFechaFinal.disabled = true;
            inpHoraFinal.disabled = true;
        }
    }, false);

    //Click en boton Confirma
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', function (e) {
        if (_REAL.checkCargaProds()) {
            //Confirmar comprobante
            Swal.fire(_REAL.opcionesAlert).then((result) => {
                if (result.isConfirmed) {
                    const guardarPlanilla = new GuardarPlanilla();
                    guardarPlanilla.inicio();
                    e.target.disabled = true;   // Desabilito el boton de confirmar
                }
            });
        }
    });

}
