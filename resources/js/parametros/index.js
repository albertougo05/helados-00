/**
 * 
 * ./js/parametros/index.js
 * 
 */

import IMask from 'imask';
import MensajeAlerta from '../utils/MensajeAlerta';
import masks from "../utils/masks";
import Swal from 'sweetalert2';


const _showSpin = siNo => {
    const spinGuardando = document.querySelector('#spinGuardando');

    if (siNo) {
        spinGuardando.classList.remove('invisible');
    } else {
        spinGuardando.classList.add('invisible');
    }

    return null;
};

const _showOk = (siNo, texto = '') => {
    const divGuardadoOk = document.querySelector('#guardadoOk');

    if (siNo) {
        divGuardadoOk.classList.remove('invisible');
    } else {
        divGuardadoOk.classList.add('invisible');
    }

    if (texto === 'borra_impresora') {
        document.querySelector('#textoGuardadoOk').innerHTML = 'Impresora eliminada';
    } else if (texto === 'save_email') {
        document.querySelector('#textoGuardadoOk').innerHTML = 'Emails salvados';
    }

    return null;
};

const _getDatosImpresoras = () => {
    const allIputsImpresoras = document.querySelectorAll('.inputs_impresoras input');
    let arrDatos = [];

    allIputsImpresoras.forEach(e => {
        if (e.value != '') {        // Si el input no está vacio...
            let objDatos = {
                sucursal_id: PARAMS._sucursal_id,
                caja_nro: e.dataset.caja,
                nombre: e.value,
                sist_operativo: PARAMS._sistOperativo,
            };

            arrDatos.push(objDatos);
        }
    });

    return arrDatos;
};

const _saveImpresoras = async () => {
    const data = _getDatosImpresoras();
    const url = PARAMS._pathStoreImpresoras;
    const response = await axios.post(url, data);
    
    return response.data;
};

const _sendDataEnv = async(data) => {
    const url = PARAMS._pathStorePesoEnvases;
    const response = await axios.post(url, data);
    
    return { respuesta: response.data };
};

const _eliminarImpresora = async (idReg, idCaja) => {
    const data = {
        id_impres: idReg,   //  Id de registro impresora a eliminar
        sucursal_id: PARAMS._sucursal_id,
        caja_nro: idCaja,
    };
    _showSpin(true);
    document.querySelector('#btnEliminaImp-' + idCaja).disabled = true;
    document.querySelector('#btnConfirmaImpresora').disabled = true;
    const response = await axios.post(PARAMS._pathEliminarImpresora, data);

    return response.data;
};

const _savePesoEnv = async valor => {
    const data = {
        sucursal_id: PARAMS._sucursal_id,
        peso: parseFloat(valor),
    };
    const resp = await _sendDataEnv(data);

    return resp;
};

const _createInput = () => {        // divinput-{{ $key }}" class="flex"
    const objDiv = document.createElement("DIV");
    objDiv.id = `divinput-${PARAMS._idxInputsEmails}`;
    objDiv.classList.add("flex","mt-1");

    const objInput = document.createElement("INPUT");
    objInput.setAttribute("type", "email");
    objInput.id = "inp_email_" + PARAMS._idxInputsEmails;
    objInput.dataset.emailid = PARAMS._idxInputsEmails;
    objInput.dataset.tienebtn = "false";
    objInput.classList.add("block", "mt-0", "w-3/4", "h-8", "rounded", "shadow-sm", "border-gray-300", "focus:border-indigo-300", "focus:ring", "focus:ring-indigo-200", "focus:ring-opacity-50");
    objInput.placeholder = "Ingrese email...";
    objInput.onblur = PARAMS._onblurCheckEmail;

    objDiv.appendChild(objInput);

    return objDiv;
};

const _createBtnElimEmail = email_id => {
    const objBoton = document.createElement("BUTTON");
    objBoton.id = "btnEliminaEmail-" +  email_id;
    objBoton.dataset.emailid = email_id;
    objBoton.classList.add("btnElimEmail", "bg-red-600", "ml-4", "mt-0", "px-2", "py-1", "text-red-100", "font-bold", "rounded", "disabled:bg-red-400");
    objBoton.innerHTML = `<svg class="btnElimEmail" xmlns='http://www.w3.org/2000/svg' data-emailid=${email_id} width='24' height='24' viewBox='0 0 24 24' style='fill: rgba(255, 255, 255, 1);transform: ;msFilter:;'><path class="btnElimEmail" data-emailid="${email_id}" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path class="btnElimEmail" data-emailid="${email_id}" d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg>`;
    objBoton.onclick = PARAMS._clickBtnEliminarEmail;
    
    return objBoton;
};

PARAMS._clickBtnEliminarEmail = function (btn) {
    const divElem = document.querySelector('#' + `divinput-${btn.target.dataset.emailid}`)
    // Elimino div con input
    divElem?.remove(); 

    return null;    
};

PARAMS._onblurCheckEmail = function (elem) {
    if (elem.target.value.length > 0) {
        if (_validateEmail(elem.target)) {
            if (elem.target.dataset.tienebtn === "false") {
                const idxBtn = elem.target.dataset.emailid;
                const botonElim = _createBtnElimEmail(idxBtn);
                const divInput = document.querySelector('#divinput-' + idxBtn);

                divInput.appendChild(botonElim);
                elem.target.dataset.tienebtn = "true";
            }
        } else {
            MensajeAlerta('Dirección de email incorrecta !');
        }
    }
};

PARAMS._clickBtnAgregarEmail = () => {
    // Agregar input
    const divInputs = document.querySelector('.inputs_emails');
    const elemInput = _createInput();

    divInputs.appendChild(elemInput);
    PARAMS._idxInputsEmails += 1;

    return null;
};

function _validateEmail(input) {
    const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let result = false;

        if (input.value.match(validRegex)) {
            result = true;
        } else {
            result = false;
        }

    return result;
};

const _getValidsEmails = () => {
    let elemInput = undefined,
        validEmails = [];
    // Por cada input de email
    for (let index = 0; index < PARAMS._idxInputsEmails; index++) {
        elemInput = document.getElementById(`inp_email_${index}`);
        if (elemInput) {
            if (elemInput.value) {
                validEmails.push(elemInput.value);
            }
        }
    }

    return validEmails;
};

const _saveEmails = async () => {
    const url = PARAMS._pathStoreEmails;
    const data = {
        sucursal_id: PARAMS._sucursal_id,
        emails: _getValidsEmails()
    };
    //console.log('datos:', data, 'Url:', url)
    const response = await axios.post(url, data);
    
    return response.data;
};

const configSwal = {
        title: 'Está seguro ?',
        text: "Quiere eliminar impresora ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminar!'
    };


//
// Despues de cargar la página...
//
window.onload = function() {
    // Convierte a integer cant inputs
    PARAMS._idxInputsEmails = parseInt(PARAMS._idxInputsEmails);
    // Agrega evento blur a inputs de emails
    const allInputsEmails = document.querySelectorAll('input.inputEmail');
    allInputsEmails.forEach(btn => {
        btn.addEventListener('blur', PARAMS._onblurCheckEmail);
    });    

    // Agrega evento click a botones de borrar email 
    const allBtnsElimEmail = document.querySelectorAll('.btnElimEmail');
    allBtnsElimEmail.forEach(btn => {
        btn.addEventListener('click', PARAMS._clickBtnEliminarEmail);
    });

    // Click boton confirmar impresora
    const btnConfirmaImp = document.querySelector('#btnConfirmaImpresora');
    btnConfirmaImp.addEventListener('click', function (e) {
        e.preventDefault();

        e.target.disabled = true;
        _showSpin(true);
        _saveImpresoras()
            .then(data => {
                _showSpin(false);
                //console.log(data);
                _showOk(true);
            })
            .catch(error => {
                //console.log('Error:', error);
                _showSpin(false);
                MensajeAlerta('No se pudo guardar impresoras...');
            });
    });

    const allBtnsElimImp = document.querySelectorAll('button.btnElim');
    // Click boton eliminar impresora
    allBtnsElimImp.forEach(btn => {
        btn.addEventListener('click', function (e) {
            Swal.fire(configSwal).then((result) => {
                if (result.isConfirmed) {
                    _eliminarImpresora(e.target.dataset.impid, e.target.dataset.cajaid)
                        .then(data => {
                            _showSpin(false);
                            //console.log(data.data);
                            if (data.estado == 'ok') {
                                document.querySelector('#imp_caja_' + e.target.dataset.cajaid).value = '';
                                _showOk(true, 'borra_impresora');
                            } else {
                                MensajeAlerta('No se pudo eliminar impresora...');
                            }
                        })
                        .catch(error => {
                            _showSpin(false);
                            MensajeAlerta('No se pudo eliminar impresora... (' + error + ')');
                    });
                }
            });
        });
    });

    // Set input peso envase
    const _inputPesoEnv = IMask(document.getElementById('peso_envase'), masks.tresDecimales);

    // Click boton confirma peso envase
    const btnConfirmaEnv = document.querySelector('#btnConfirmaEnv');
    btnConfirmaEnv.addEventListener('click', function (e) {
        e.preventDefault();

        _showSpin(true);
        _savePesoEnv(_inputPesoEnv._unmaskedValue)
            .then(data => {
                _showSpin(false);
                 //console.log(data);
                _showOk(true);
            })
            .catch(error => {
                //console.log(error);
                _showSpin(false);
                MensajeAlerta('No se pudo guardar peso envase...')
        });
    });

    // Click boton confirma emails
    const btnConfirmaEmail = document.querySelector('#btnConfirmaEmail');
    btnConfirmaEmail.addEventListener('click', e => {
        _showSpin(true);
        _saveEmails()
            .then(data => {
                _showSpin(false);
                _showOk(true, 'save_email');
            })
            .catch(error => {
                _showSpin(false);
                MensajeAlerta('No se pudo guardar emails...')
        });
    });
}