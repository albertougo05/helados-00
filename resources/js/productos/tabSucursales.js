//
// tabSucursales.js
//
import Swal from 'sweetalert2';


var tabSucursales = function () {
    // private members
    var _arr_sucs_prods = [],       // Lista final que se recoje al presionar boton salvar 

    // funciones publicadas en public members
    inicio = function(sucurs_producto) {
        if (sucurs_producto.length == 0) return null;
            _setCheckBoxes(sucurs_producto);
    },

    getSucursalSelec = () => {
        const checkboxes = document.querySelectorAll('input[name="sucursales[]"]');

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    return true;
                }
            }

        return false;       // NO HAY SUCURSAL SELECCIONADA
    },

    salvarDatos = async function() {
        const checkboxes = document.querySelectorAll('input[name="sucursales[]"]');
        let retorno = {estado: 'error'}, 
            configAxios = {};

        for (let i = 0; i < checkboxes.length; i++) {
            try {
                if (checkboxes[i].checked) {        // Si esta tildado...
                    if (checkboxes[i].dataset.idregsuc == '0') {
                        configAxios = _setConfigAxios('post', checkboxes[i].value);
//                        console.log('Store sucursal...', configAxios)
                        const response = await axios(configAxios);
                        retorno = response.data;
                    } 
                    // Si esta tildado y tiene registro, NADA HACE...
                } else {
                    if (checkboxes[i].dataset.idregsuc != '0') {    // Si NO está tildado y tiene id de registro, BORRAR...
                        configAxios = _setConfigAxios('delete', '0', checkboxes[i].dataset.idregsuc);
//                        console.log('Delete sucursal...', configAxios)
                        const response = await axios(configAxios);
                        retorno = response.data;
                    }
                }
                retorno = {estado: 'ok'};
            } catch (error) {
                _alerta('Error al guardar sucursales !!\n' + '(' + error + ')' , 'error');
            }
        }

        return retorno;
    },


    /**
     * 
     *  Funciones privadas
     * 
     **/ 
    _setCheckBoxes = sucs_prod => {
        const checkboxes = document.querySelectorAll('input[name="sucursales[]"]');

        for (let i = 0; i < checkboxes.length; i++) {        // Paso datos en DB a checkboxes
            const idx = sucs_prod.findIndex(el => el.sucursal_id === parseInt(checkboxes[i].value));

            if (idx >= 0) {
                checkboxes[i].checked = true;
                sucs_prod.checked = true;
                checkboxes[i].dataset.idregsuc = sucs_prod[idx].id;
            } else {
                sucs_prod.push({
                    id: 0, 
                    sucursal_id: checkboxes[i].value,
                    producto_id: _producto_id,
                    chequed: false,
                });
            }
        }

        return sucs_prod;
    },

    _setConfigAxios = (metodo, sucId, idReg = '0') => {
        let configAxios = {};

        switch (metodo) {
            case 'post':
                configAxios = {
                    method: 'POST',
                    url: _pathSalvarSucursal,
                    data: { producto_id: _producto_codigo, sucursal_id: sucId}
                };
                break;
        
            case 'delete':
                configAxios = {
                    method: 'GET',
                    url: _pathBorrarSucursal.replace('/0', `/${idReg}`)
                };
                break;
        }

        return configAxios;
    },

    _alerta = (mensaje, icon) => {
        Swal.fire({
            position: 'center',
            icon: icon,     // success, error, warning, info, question
            title: mensaje,
            showConfirmButton: false,
            timer: 5000
            });
    }

    ;   // Fin funciones públicas y privadas

    // public members
    return {
        inicio: inicio,
        getSucursalSelec: getSucursalSelec,
        salvarDatos: salvarDatos,
    };
}();


export default tabSucursales;