/**
 * Inicializar formularios de producto (create and edit)
 */
import { mostrarImgCreate } from "./mostrarImagen";
import IMask from 'imask';
import masks from "../utils/masks";
import { soloNumeros, _roundToTwo, strToFloat } from '../utils/solo-numeros';
import tabSucursales from './tabSucursales';
import MensajeAlerta from "../utils/MensajeAlerta";
import { englishToSpanish, _validaFecha } from '../utils/transformFechas';


//
// Mascaras de inputs (export)
//
const inputsConMask = {
    inputPrecio1: IMask(document.getElementById('precio_lista_1'), masks.currency),
    inputPrecio2: IMask(document.getElementById('precio_lista_2'), masks.currency),
    inputPrecio3: IMask(document.getElementById('precio_lista_3'), masks.currency),
    inputCostoUnid: IMask(document.getElementById('costo_x_unidad'), masks.currency),
    inputCostoBulto: IMask(document.getElementById('costo_x_bulto'), masks.currency),
    inputPesoMP:  IMask(document.getElementById('peso_materia_prima'), masks.tresDecimales),
}

//
// Dispara evento si selecciona receta (export)
//
const disparaTabReceta = () => {
    _activaTabCompra(false);
    _activaTabPromo(false);

    const tabReceta = document.querySelector('#tabReceta');
    const clickEvent = new Event('click');
    tabReceta.dispatchEvent(clickEvent);

    return null;
}


//
// Eventos del formulario (export)
//
const eventosDelForm = (form) => {

    if (form === 'edit') {
        const inputUltAct = document.getElementById('ultima_actualizacion');
        inputUltAct.value = englishToSpanish(_producto.ultima_actualizacion);

        _inputModalCosto = IMask(document.getElementById('modalCosto'), masks.currency);
        _inputModalCantidad = IMask(document.getElementById('modalCantidad'), masks.tresDecimales);

        _PROMO._inputPrecioPromo = IMask(document.getElementById('precioPromo'), masks.currency);
        _PROMO._inputModalPromoPrecio = IMask(document.getElementById('precioArtPromo'), masks.currency);
        _PROMO._inputModalPromoCantidad = IMask(document.getElementById('cantArtPromo'), masks.tresDecimales);

        // Al salir input costo promocion ...
        const inpCostoPromo = document.querySelector('#precioPromo');
        inpCostoPromo.addEventListener('blur', function (e) {
            const costoPromo = strToFloat(e.target.value);
            // Actualizar inputs de precios de lista
            inputsConMask.inputPrecio1.typedValue = costoPromo;
            inputsConMask.inputPrecio2.typedValue = costoPromo;
            inputsConMask.inputPrecio3.typedValue = costoPromo;

            return null;
        });

    }

    // Evento change de input 'imagen', para mostrar imagen
    document.getElementById('imagen').onchange = mostrarImgCreate;

    // Seleccionar tipo Elaborado, selecciona Receta, y pone readonly opciones Apto receta y Genérico
    const selTipoProd = document.querySelector('#tipo_producto_id');
    selTipoProd.addEventListener('change', function (e) {
        const value = e.target.value;

        _eventoChangeSelecTipoProducto(value);
    });

    // Seleccionar Grupo PROMO
    const selGrupoProd = document.querySelector('#grupo_id');
    selGrupoProd.addEventListener('change', function (e) {
        const value = e.target.value;

        _eventoChangeSelectGrupo(value);
    });

    // Carga los datos de sucursales en Edición
    tabSucursales.inicio(_producto_sucurs);

    // Evento CLICK boton Confirma form
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', function (e) {
        const form = document.querySelector('form');
        e.preventDefault();
        // Ver si están datos mínimos...
        if (!_checkDatosObligatorios()) {
            return null;
        }

        _muestraSpinGuardando();    // Mostrar spin de guardar datos

        if (tabSucursales.getSucursalSelec) {    // Si hay sucursales selecionada...
            // Salvar datos sucs
            tabSucursales.salvarDatos()
                .then(data => {
                    if (data.estado === 'ok') {
                        form.submit();
                    }
            });
        } else {
            form.submit();      // Si no hay sucursales marcadas...
        }

        return null;
    });

    // Click btnBorrarImg
    const btnBorrarImg = document.querySelector('#btnBorrarImg');
    btnBorrarImg.addEventListener('click', function (e) {
        e.preventDefault();
        const divImg = document.getElementById('divImagen');
        divImg.classList.add('hidden');
        const divInputImg = document.querySelector('#divInputImagen');
        divInputImg.classList.remove('hidden');
        document.querySelector('input#imagen').value = null;
        //document.querySelector('input#nombre_imagen').value = '';
    });

    // Mascara de solo ingreso de número en input 
    document.getElementById('unidades_x_caja').onkeyup = soloNumeros;
    document.getElementById('cajas_x_bulto').onkeyup = soloNumeros;
    document.getElementById('unidades_x_bulto').onkeyup = soloNumeros;

    // Evento 'change' del select de producto de articulo individual
    document.getElementById('selArticuloIndiv').onchange = _onchangeSelectArticuloIndiv;

    // Al salir de descripcion completa input ticket  - ALFAJOR  BLANCO CAJA
    const inputDescrip = document.querySelector("input#descripcion");
    inputDescrip.addEventListener("blur", function (e) {
        let desc = e.target.value;
        const ticket = document.querySelector('input#descripcion_ticket');
        desc = desc.slice(0, 20);
        ticket.value = desc;
    });

    // Al salir de costo por bulto, ...
    const inpCostoBulto = document.querySelector('#costo_x_bulto');
    inpCostoBulto.addEventListener('blur', function (e) {
        const costoBulto = strToFloat(e.target.value);
        const unidPorBulto = strToFloat(document.getElementById('unidades_x_bulto').value);

        if (unidPorBulto !== 0) {
            inputsConMask.inputCostoUnid.typedValue = _roundToTwo(costoBulto / unidPorBulto);
        }

        return null;
    });

    // Al salir (blur) de inputs unid. por caja y cajas por bulto
    const inpUnidPorCaja = document.querySelector('#unidades_x_caja');
    const inpCajasPorBulto = document.querySelector('#cajas_x_bulto');
    inpUnidPorCaja.addEventListener('blur', _blurUnidCajaUnidBulto);
    inpCajasPorBulto.addEventListener('blur', _blurUnidCajaUnidBulto);

    // Al salir de input unidades por bulto...
    const inpUnidPorBulto = document.querySelector('#unidades_x_bulto');
    inpUnidPorBulto.addEventListener('blur', function (e) {
        const unidPorBulto = strToFloat(e.target.value);
        const costoPorBulto = strToFloat(document.getElementById('costo_x_bulto').value);

        if (costoPorBulto !== 0) {
            inputsConMask.inputCostoUnid.typedValue = _roundToTwo(costoPorBulto / unidPorBulto);
        }

        return null;
    });

    // Click en checkbox Promo todos
    const ckbTodos = document.querySelector("input#promo_todos");
    ckbTodos.addEventListener("click", function (e) {
        const valor = e.target.value;
        //const checkboxes = document.querySelectorAll('#dias_promo input[type="checkbox"]');
        const checkboxes = document.querySelectorAll('input[name="dias_promo"]');

        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = e.target.checked;
        }
    });

    // Click en checkbox Promo todos
    const ckbTodasSucs = document.querySelector("input#sucursalesTodas");
    ckbTodasSucs.addEventListener("click", function (e) {
        const valor = e.target.value;
        const checkboxes = document.querySelectorAll('input[name="sucursales[]"]');

        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = e.target.checked;
        }
    });

    // Evento on blur de combos fecha (validar fecha)
    const cboFechaDesde = document.querySelector("input#promo_desde");
    cboFechaDesde.addEventListener('blur', e => {

        if (form === 'create') {
            MensajeAlerta('Debe confirmar producto, para cargar promoción !! !', 'error');
            return null;
        }

        if (!_validaFecha(englishToSpanish(e.target.value))) {
            MensajeAlerta('Ingrese fecha válida !', 'error');
            e.target.focus();
        }
    });

    const cboFechaHasta = document.querySelector("input#promo_hasta");
    cboFechaHasta.addEventListener('blur', e => {

        if (form === 'create') {
            MensajeAlerta('Debe confirmar producto, para cargar promoción !! !', 'error');
            return null;
        }

        if (!_validaFecha(englishToSpanish(e.target.value))) {
            MensajeAlerta('Ingrese fecha válida !', 'error');
            e.target.focus();
        }
    });

}   // end Eventos del form


/**
 * 
 * FUNCIONES LOCALES
 * 
 */

// Evento 'change' del select de producto de articulo individual
const _onchangeSelectArticuloIndiv = e => {
    const selec = e.target.value;
    document.querySelector('input#articulo_indiv_id').value = selec;

    if (selec == "0") {
        document.querySelector('input#unidades_x_caja').setAttribute('readonly', true);
        document.querySelector('input#unidades_x_caja').value = '';
        document.querySelector('input#cajas_x_bulto').setAttribute('readonly', true);
        document.querySelector('input#cajas_x_bulto').value = '';
    } else {
        document.querySelector('input#unidades_x_caja').removeAttribute('readonly');
        document.querySelector('input#cajas_x_bulto').removeAttribute('readonly');
    }
};

// Muestra spin de guardado de datos
const _muestraSpinGuardando = () => {
    const spinGuardando = document.querySelector('#spinGuardando');
    spinGuardando.classList.remove('hidden');

    return null;
};

// Disable/Enable div compra
const _activaTabCompra = (estado) => {
    const tabCompra = document.querySelector('#tabDatosCompra');

    if (estado) {
        tabCompra.setAttribute('aria-disabled', 'false');   // Desactiva tab compra
        tabCompra.classList.remove('isDisabled');
    } else {
        tabCompra.setAttribute('aria-disabled', 'true');   // Desactiva tab compra
        tabCompra.classList.add('isDisabled');
    }
};

// Disable/Enable div Receta
const _activaTabReceta = (estado) => {
    const tabReceta = document.querySelector('#tabReceta');

    if (estado) {
        tabReceta.setAttribute('aria-disabled', 'false');   // Desactiva tab Receta
        tabReceta.classList.remove('isDisabled');
    } else {
        tabReceta.setAttribute('aria-disabled', 'true');   // Desactiva tab Receta
        tabReceta.classList.add('isDisabled');
    }
};

// Disable/Enable div Promociones
const _activaTabPromo = (estado) => {
    const tabPromo = document.querySelector('#tabPromo');

    if (estado) {
        tabPromo.setAttribute('aria-disabled', 'false');   // activa tab promo
        tabPromo.classList.remove('isDisabled');
        const divPrecioPromo = document.querySelector('#divPrecioPromo');
        divPrecioPromo.classList.remove('hidden');
    } else {
        tabPromo.setAttribute('aria-disabled', 'true');   // Desactiva tab promo
        tabPromo.classList.add('isDisabled');
    }
};

// Chequear datos obligatorios
const _checkDatosObligatorios = () => {
    let resultado = false;
    const inpDescrip  = document.querySelector('#descripcion'),
          selTipoProd = document.querySelector('#tipo_producto_id'),
          selGrupo    = document.querySelector('#grupo_id'),
          selProveed  = document.querySelector('#proveedor_id');

    if (inpDescrip.value.replace(/\s/g,"") == "") {
        MensajeAlerta('Ingrese una Descripción !', 'error');
        inpDescrip.focus();
    } else if (selTipoProd.value == 0) {
        MensajeAlerta('Ingrese Tipo de Producto !', 'error');
        selTipoProd.focus();
    } else if (selTipoProd.value == 0) {
        MensajeAlerta('Ingrese Grupo de Producto !', 'error');
        selGrupo.focus();
    } else if (selProveed.value == 0 && (selTipoProd.value != 1 && selTipoProd.value != 4)) {
        MensajeAlerta('Ingrese un Proveedor !', 'error');
        selProveed.focus();

    } else resultado = true;

    return resultado;
};

const _blurUnidCajaUnidBulto = function (elem) {
    let unidCaja = Number.parseInt(document.querySelector('input#unidades_x_caja').value),
        cajasBulto = Number.parseInt(document.querySelector('input#cajas_x_bulto').value),
        unidPorBulto = 0;

    if (Number.isNaN(unidCaja)) unidCaja = 0;
    if (Number.isNaN(cajasBulto)) cajasBulto = 0;

    if (unidCaja === 0 && cajasBulto === 0) return null;

    if (unidCaja === 0 && cajasBulto > 0) {
        unidCaja = 1;
    } else if (unidCaja > 0 && cajasBulto === 0) {
        cajasBulto = 1;
    }

    unidPorBulto = unidCaja * cajasBulto;
    document.querySelector('#unidades_x_bulto').value = unidPorBulto;

    //console.log('unid caja:', unidCaja, 'cajas por bulto:', cajasBulto, 'unid por bulto:', unidPorBulto);

    if (unidPorBulto !== 0) {
        const costoBulto = strToFloat(document.querySelector('#costo_x_bulto').value);
        inputsConMask.inputCostoUnid.typedValue = _roundToTwo(costoBulto / unidPorBulto);
    }

    return null;

};

const _eventoChangeSelecTipoProducto = value => {
    if (value == 1) {
        disparaTabReceta();
        document.querySelector('#apto_receta').disabled = true;
        document.querySelector('#generico').disabled = true;
    } else if (value == 2 || value == 3 || value == 5) {
        _disparaTabCompra();
        document.querySelector('#tabDatosCompra').href = '#tabs';
        document.querySelector('#apto_receta').disabled = false;
        document.querySelector('#generico').disabled = false;
    } else if (value == 4) {        // PROMOS
        disparaTabPromo();
        const selectGrupo = document.querySelector('#grupo_id');
        selectGrupo.value = 17;
        document.querySelector('#tabDatosCompra').href = '#tabs';
        document.querySelector('#apto_receta').disabled = false;
        document.querySelector('#generico').disabled = false;
    }

    return null;
};

const _eventoChangeSelectGrupo = value => {
    if (value == 17) {  // Selecciona PROMO
        disparaTabPromo();
        const selectTipo = document.querySelector('#tipo_producto_id');
        selectTipo.value = 4;
        document.querySelector('#tabDatosCompra').href = '#tabs';
        document.querySelector('#apto_receta').disabled = false;
        document.querySelector('#generico').disabled = false;
    } else {
        _activaTabCompra(true);
    }

    return null;
};

const _disparaTabCompra = () => {
    _activaTabCompra(true);
    const tabCompra = document.querySelector('#tabDatosCompra');
    const clickEvent = new Event('click');
    tabCompra.dispatchEvent(clickEvent);
}

const disparaTabPromo = () => {
    const tabPromo = document.querySelector('#tabPromo');
    const clickEvent = new Event('click');

    _activaTabCompra(false);
    _activaTabReceta(false);
    tabPromo.dispatchEvent(clickEvent);

    return null;
}


export { eventosDelForm, inputsConMask, disparaTabReceta, disparaTabPromo };