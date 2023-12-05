import IMask from 'imask';
import masks from "../../utils/masks";
import funcionesEdit from './funcionesEdit';
import funcionesComunes from './funcionesComunes';
import Swal from 'sweetalert2';
import UpdatePlanilla from './UpdatePlanilla';


funcionesComunes.init();


//
// Despues de cargar la página (EDIT)...
//
window.onload = function() {
    // Inicializar form
    const selTipoTomaSock = document.querySelector('#tipo_toma_stock');
    funcionesEdit.setTipoTomaSock(_REAL.planilla, selTipoTomaSock);

    const inputHoraParcial = IMask(document.getElementById('hora_toma_stock'), masks.hora);
    inputHoraParcial.typedValue = _REAL.planilla.hora_toma_stock;
    const inputHoraFinal = IMask(document.getElementById('hora_stock_final'), masks.hora);
    inputHoraFinal.typedValue = _REAL.planilla.hora_periodo_stock_final;

    document.getElementById('fecha_toma_stock').value = _REAL.planilla.fecha_toma_stock;
    document.getElementById('detalle').value = _REAL.planilla.detalle;

    _REAL.articulosPlanilla = funcionesEdit.arreglaArrayArtPlanilla();
    funcionesEdit.cargaArticulosPlanilla();

    _REAL.heladosPlanilla = funcionesEdit.arreglaArrHeladosPlanilla();
    funcionesEdit.cargaHeladosPlanilla();

    // Inicializar inputs de helados a tres decimales
    const inputsKilos = document.querySelectorAll("input[id^='kgs_abs_']");
    inputsKilos.forEach(elem => {
        _REAL.inputs_kilos_hela.push(IMask(elem, masks.tresDecimales));
    });

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

    // Click en boton Confirma
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', function (e) {
        if (_REAL.checkCargaProds()) {
            //Confirmar comprobante
            Swal.fire(_REAL.opcionesAlert).then((result) => {
                if (result.isConfirmed) {
                    e.target.disabled = true;   // Desabilito el boton de confirmar
                    const updatePlanilla = new UpdatePlanilla();
                    updatePlanilla.update();
                }
            });
        }
    });

    // Muestra suma total planilla
    funcionesComunes.sumaTotalPlanilla();
}