// 
// index.js (Ingreso stock)
//
import IMask from "imask";
import masks from "../utils/masks";
import MensajeAlerta from '../utils/MensajeAlerta';
import { ProductSelecToList, ProductToEdit } from "./ProductSelecToList";
import UiListaProdStock from './UiListaProdStock';
import guardaComprobante from './guardaComprobante';
import Swal from 'sweetalert2';


_STOCK.cambiaColoresBtns = (target, grupoBtns) => {
    // Cambia color al boton actual
    target.classList.add('btnSelected2');
    // Boton desactivo btn anterior
    grupoBtns.forEach(el => {
        if (el.id == _STOCK.btnGrupo_activo) {
            el.classList.remove('btnSelected2');
        }
    });
    // Set nuevo boton activo
    _STOCK.btnGrupo_activo = target.id;
};

_STOCK.setSelectUnMed = (id, unid, caja) => {
    const $selectUnMed = document.querySelector('#unid_medida');
    const $options = Array.from($selectUnMed.options);

    if (id === 'ID_2') {
        $options[0].value = 'CA';
        $options[0].textContent = 'Cajas/Latas';
        $options[1].value = 'KI';
        $options[1].textContent = 'Kilos';
    } else {
        $options[0].value = 'BU';
        $options[0].textContent = 'Bultos';
        $options[1].value = 'UN';
        $options[1].textContent = 'Unidades';
    }

    // SELECCIONAR SEGUN SI EL GRUPO ES UNIDAD O BULTO
    if (unid == 1) {
        $selectUnMed.options[1].selected = true;
    } else if (caja == 1) {
        $selectUnMed.options[0].selected = true;
    }else {
        $selectUnMed.options[0].selected = true;
    }

    return null;
};

const _configSwalFire = {
    title: 'Está seguro de guardar ?',
    text: "Seleccione opción!",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, confirma!',
    cancelButtonText: 'Cancela'
};


//
// Despues de cargar la página...
//
window.onload = function() {
    //
    // SI USUARIO NO TIENE TURNO ABIERTO, NO PUEDE INGRESAR STOCK !!
    //
    if (_STOCK.turno_sucursal === 0) {
        MensajeAlerta('No tiene turno abierto !!');
        setTimeout(() => {
            location.assign('/inicio');
        }, 5000);
    }

    _STOCK.inputCantidad = IMask(document.getElementById('cantidad'), masks.tresDecimales);
    _STOCK.inputCantidad.typedValue = 0;
    // Determina primer elemento de grupos de productos
    _STOCK.btnGrupo_activo = 'ID_' + _STOCK.grupos[0].id;
    // Instacia la clase...
    const prodALista = new ProductSelecToList(_STOCK.inputCantidad);

    if (_STOCK._esEdit) {
        // Cargas productos a editar a lista _STOCK.productos_a_stock
        const tipo_mov = _STOCK.comprobante.tipo_movimiento_id;
        const prodsToEdit = new ProductToEdit();
        prodsToEdit.cargarDetalle();
        prodsToEdit.detalleAPantalla()
        document.querySelector('#selTipoMovim').options[tipo_mov].selected = true;
        document.querySelector('#observaciones').value = _STOCK.comprobante.observaciones;
    } else {
        // SELECCIONO TIPO DE MOVIMIENTO A ALTA POR COMPRA COMO DEFAULT
        document.querySelector('#selTipoMovim').options[2].selected = true;
    }

    /**
     * Click en Boton Confirma ingreso stock
     */
    const btnConfirma = document.querySelector('#btnConfirma');
    btnConfirma.addEventListener('click', e => {
        const seleccTipoDeMovimiento = (document.querySelector('#selTipoMovim').value > 0);

        if (!seleccTipoDeMovimiento) {
            MensajeAlerta("Seleccione tipo de movimiento !", "warning");
            return null;
        }

        if (_STOCK.productos_a_stock.length > 0) {
            Swal.fire(_configSwalFire)
                .then((result) => {
                    if (result.isConfirmed) {
                        e.target.setAttribute('disabled', true);
                        guardaComprobante();
                    }
                });
        }
    });

    /**
     * Evento click en botones de grupos
     */
    const _btnsGroups = document.querySelectorAll('.btnGrupos');
    const uiListaProds = new UiListaProdStock();
    _btnsGroups.forEach(elem => {
        // Activo si es el primer elemento de grupos
        if (elem.id == _STOCK.btnGrupo_activo) {
            elem.classList.add('btnSelected2');
            // Carga lista productos grupo 1
            uiListaProds.productosALinea(elem.id, _STOCK.productos);
            _STOCK.setSelectUnMed(elem.id, _STOCK.grupos[0].unidad, _STOCK.grupos[0].caja);
        }

        elem.addEventListener('click', function(e) {
            const id = e.target.id;
            const unid = e.target.dataset.unid;
            const caja = e.target.dataset.caja;
            _STOCK.cambiaColoresBtns(e.target, _btnsGroups);
            // Carga lista de productos
            uiListaProds.productosALinea(elem.id);
            _STOCK.setSelectUnMed(id, unid, caja);
        });
    });

    // Ingreso de cantidad habilita botón ingreso producto
    const _inputCantidad = document.getElementById('cantidad');

    _inputCantidad.addEventListener('keydown', function(e) {
        document.querySelector('#btnIngresaProd').disabled = false;
        if (e.key === "Enter" && e.target.nodeName === 'INPUT') {
            document.querySelector('#btnIngresaProd').focus();
        }
    });

    // Click en boton Ingreso producto
    const btnIngresoProducto = document.querySelector('#btnIngresaProd');
    btnIngresoProducto.addEventListener('click', function (e) {
        // producto seleccionado a lista
        prodALista.acciones(_STOCK.producto_selec);
        // Deseleccionar lista de productos
        uiListaProds.ponerListaProdEnBlanco();
        document.querySelector('#unid_medida').options[0].selected = true;
        document.querySelector('#tablaProductos').focus();
    });
}
