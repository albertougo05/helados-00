/**
 * Lista de precios (actualiza)
 */
import IMask from 'imask';
import masks from "../../utils/masks";
import _getInputsModif from './getInputsModif';
import _getProdsActualizar from './getProdsActualizar';
import _actualizarProds from './actualizarProds';
import _blurCostoBulto from './blurCostoBulto';
import _blurInputs from './blurInputs';
import MensajeAlerta from "../../utils/MensajeAlerta";


window.onload = function() {
    
    _LISTA_PREC._blurCostoBulto = _blurCostoBulto;
    _LISTA_PREC._blurInputs = _blurInputs;

    // Enter y flecha abajo baja en inputs - Flecha arriba, sube foco en inputs numericos
    document.addEventListener('keydown', function (ev) {
        if ((ev.key === "Enter" || ev.key === "ArrowDown" || ev.key === "ArrowUp") && ev.target.nodeName === 'INPUT') {
            const focusableElementsString = 'input:not([readonly])';
            let ol = document.querySelectorAll(focusableElementsString);
            let o = {};

            for (let i=0; i<ol.length; i++) {
                if (ol[i] === ev.target) {
                    if (ev.key === "ArrowUp") {
                        //let o = i < ol.length - 1 ? ol[i-1] : ol[0];
                        o = i < ol.length - 1 ? ol[i-1] : ol[i-1];    // Sube
                    } else {
                        o = i < ol.length - 1 ? ol[i+1] : ol[0];    // Baja
                    }
                    o.focus(); 
                    break;
                }
            }
            ev.preventDefault();
        }
    });

    // Asigno IMask a cada input
    const _inputs = document.querySelectorAll('input:not([readonly])');
    for (let i = 0; i < _inputs.length; i++) {
        _LISTA_PREC._inputsIMask.push( IMask(_inputs[i], masks.currency) );
    }

    // Evento click boton Confirma
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', function (e) {

        _muestraSpinGuardando();
        const inputsModif = _getInputsModif(_inputs);

        if (inputsModif.length > 0) {
            const prodsActualizar = _getProdsActualizar( inputsModif );
            _actualizarProds(prodsActualizar);
            e.target.disabled = true;
        } else {
            MensajeAlerta('No hay precios modificados !', 'error');
        }
    });

};

const _muestraSpinGuardando = () => {
    const spinGuardando = document.querySelector('#spinGuardando');
    spinGuardando.classList.remove('invisible');

    return null;
};
