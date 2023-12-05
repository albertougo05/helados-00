/**
 * index.js (/ventas/index)
 */

import SeleccionarCajaNro from './SeleccionarCajaNro';
import PagoVueltoTotal from "./PagoVueltoTotal";
import ModalFinalVenta from "./ModalFinalVenta";
import UiListaProds from './UiListaProds';
import IMask from "imask";
import masks from "../utils/masks";
import BuscarCliente from "./BuscarCliente";
import ConfirmaSelecCliente from "./ConfirmaSelecCliente";
import FormasPago from "./FormasPago";
import ConfirmaVenta, { controlConfirmaVenta } from "./ConfirmaVenta";
import _accionesBtnBorrarCli from './accionesBtnBorrarCli';
import _setBotonesDeModales from './setBotonesDeModales';
import { _confirmaPromoSeleccionada } from './confirmaPromoSeleccionada';
import detalleDeCaja from './detalleDeCaja';
import { fechaActual, englishToSpanish } from '../utils/transformFechas';


// Convierto en float importes de productos (viene de DB como string)
_VENTA.productos = _VENTA.productos.map(el => {
    el.precio_vta = parseFloat(el.precio_vta);
    el.costo_x_unidad = parseFloat(el.costo_x_unidad);
    el.peso_materia_prima = parseFloat(el.peso_materia_prima);

    return el;
});

// Agrego datos a var global
_VENTA.detalle = [];
_VENTA.total = 0;
_VENTA.hora = "";
_VENTA.cliente_id = 0;
_VENTA.data_clie = {
    id: 0,
    nombre: '',
    direccion: '',
    localidad: '',
    plan_cuenta_id: 0
};
_VENTA.forma_pago = 0;
_VENTA.comprobante_id_guardado = parseInt(_VENTA.nro_comprobante) - 100;
_VENTA.detalle_caja = detalleDeCaja;

_VENTA.cambiaColoresBtns = (target, grupoBtns) => {
    // Cambia color al boton actual
    target.classList.add('btnSelected2');
    // Boton desactivo btn anterior
    grupoBtns.forEach(el => {
        if (el.id == _VENTA.btnGrupo_activo) {
            el.classList.remove('btnSelected2');
        }
    });
    // Set nuevo boton activo
    _VENTA.btnGrupo_activo = target.id;
};


window.onload = function() {
    _VENTA.fecha = fechaActual('en');
    document.querySelector('#fecha_comprobante').value = englishToSpanish(_VENTA.fecha);
    document.querySelector('#cliente').value = 'Consumidor final';    // Set input cliente
    _VENTA.inputPagoContado = IMask(document.getElementById('pagoContado'), masks.currency);
    _VENTA.inputPagoContado.typedValue = '';
    // Set vuelto en 0
    const _inputVuelto = document.querySelector('#vuelto');
    _inputVuelto.value = '';
    // Set select Forma de pago
    document.querySelector('#forma_pago_id').value = 0;

    // Set botones de modales
    _setBotonesDeModales();

    if (_VENTA.caja_nro === 0) {
        const selCaja = new SeleccionarCajaNro(_VENTA.cajas_suc);

        if (_VENTA.cajas_suc.length === 1) {
            _VENTA.caja_nro = 1;
            selCaja.muestraEnPant(_VENTA.caja_nro)
        } else {
            selCaja.muestraAlert();
        }
    }

    // Determinar primer elemento de grupos de productos
    _VENTA.btnGrupo_activo = 'ID_' + _VENTA.grupos[0].id;

    // Evento click en botones de grupos
    const _btnsGroups = document.querySelectorAll('.btnGrupos');
    _btnsGroups.forEach(elem => {
        const uiListaProds = new UiListaProds(_VENTA.productos);
        // Activo si es el primer elemento de grupos
        if (elem.id == _VENTA.btnGrupo_activo) {
            elem.classList.add('btnSelected2');
            uiListaProds.productosALinea(elem.id);
        }

        elem.addEventListener('click', function(e) {
            const id = e.target.id;
            _VENTA.cambiaColoresBtns(e.target, _btnsGroups);
            uiListaProds.productosALinea(elem.id);
        });
    });

    // Set evento keyup input pago contado
    const inputPagoContado = document.querySelector('#pagoContado');
    inputPagoContado.addEventListener('keyup', (e) => {
        const valor = e.target.value;
        const pagoVueltoTotal = new PagoVueltoTotal();

        pagoVueltoTotal.ingresoPago(valor);
    });

    // Click en boton Confirma Venta
    const btnConfVenta = document.querySelector('#btnConfirmaVenta');
    btnConfVenta.addEventListener('click', (e) => {

        if (controlConfirmaVenta()) {
            // Muestra modal para confirmar
            const modalFinalVenta = new ModalFinalVenta();
            modalFinalVenta.setDatos();
            modalFinalVenta.muestraModal();
        }
    });

    // Click en boton imprime ticket de venta
    const btnConfirmaTicket = document.querySelector('#btnConfirmaTicketVenta');
    btnConfirmaTicket.addEventListener('click', function (e) {
        const confirmaVenta = new ConfirmaVenta();
        confirmaVenta.escondeModal();
        confirmaVenta.guardarDatosVenta();
    });

    // Click en boton Buscar cliente
    const btnBuscarCliente = document.querySelector('#btnBuscarCliente');
    btnBuscarCliente.addEventListener('click', function (e) {
        e.preventDefault();
        //const mostrarBuscarCliente = new MostrarBuscarCliente();
        //mostrarBuscarCliente.inicio();

        document.querySelector('.modal-2').classList.remove('hidden');      // Muestra el modal
    });

    // Evento change select moda cliente
    const selectModalCliente = document.querySelector('#selectModalCliente');
    selectModalCliente.addEventListener('change', e => {
        const idClieSel = e.target.value;
        const buscarCli = new BuscarCliente(idClieSel);   // Muestra datos del cliente en modal
        buscarCli.buscarMostrar();
    });

    // Click boton Confirma selección de cliente
    const btnConfirmaClie = document.querySelector('#btnConfirmaModalClie');
    btnConfirmaClie.addEventListener('click', function (e) {
        e.preventDefault();
        const confirmaSelecClie = new ConfirmaSelecCliente(_VENTA.inputPagoContado);
        confirmaSelecClie.inicio();
    });

    // Change combo Forma de pago
    const selFormaPago = document.querySelector('select#forma_pago_id');
    selFormaPago.addEventListener('change', function (e) {
        const pagoSelec = e.target.value;
        const formasPago = new FormasPago(pagoSelec, _VENTA.inputPagoContado, _inputVuelto);
        formasPago.inicio();
    });

    // Click boton borrar cliente
    const btnBorrarCli = document.getElementById('btnBorrarClie');
    btnBorrarCli.addEventListener('click', function (e) {
        e.preventDefault();

        _accionesBtnBorrarCli();
    });

    // Click boton confirmar promocion
    const btnConfirmaPromo = document.getElementById('btnConfirmModalPromo');
    btnConfirmaPromo.addEventListener('click', function (e) {
        e.preventDefault();

        _confirmaPromoSeleccionada();
    });

}


