/** 
 * Acciones del modal articulos para Promociones
 */
import resetModalPromo from './resetModalPromo';
import Swal from 'sweetalert2';
import MensajeAlerta from "../utils/MensajeAlerta";
import { strToFloat } from '../utils/solo-numeros';
import Promocion from "./Promocion";


// Clase Promocion. 
let promocion = new Promocion();
promocion.cargaArticOpciones();


// Inicializa modal ingreso articulos de promocion
const initModalPromo = () => {

    if (_promocion_producto) {
        _PROMO._producto_promocion = _promocion_producto;
        _PROMO._opciones = _promocion_opciones;
        _cargarInputsPromocion();
        promocion.redibujaTabla();  // Producto a tabla en pantalla
    }

    const modalPromo = document.querySelector('#modalArticuloPromo'),
          btnShowModalPromo = document.querySelector('#btnAgregarArticulo'),
          closeModalPromo = document.querySelectorAll('.close-modal-promo'),
          divPromo = document.querySelector('#divPromo');
          
    // Evento boton agregar producto (muestra modal)
    btnShowModalPromo.addEventListener('click', function() {
        resetModalPromo(_PROMO._inputModalPromoPrecio, _PROMO._inputModalPromoCantidad);
        promocion.cambiarClaseColor(divPromo, 1);  // Pone color combo 1
        modalPromo.classList.remove('hidden');
    });
 
    // Evento modal: boton Cancela y X
    closeModalPromo.forEach(close => {
        close.addEventListener('click', function() {
           modalPromo.classList.add('hidden');
        });
    });

    // Evento modal: Select producto (change)
    const selArticPromo = document.querySelector('#selArtPromo');
    selArticPromo.addEventListener('change', function(e) {
        const codigo = e.target.value;

        // Buscar en _productos_receta (aptos receta)
        const idx = _articulos_promo.findIndex(e => e.codigo == codigo);
        //inputModalCosto.typedValue = _articulos_promo[idx].costo_x_unidad;
        _PROMO._inputModalPromoPrecio.typedValue = _articulos_promo[idx].precio_lista_1;
        _PROMO._inputModalPromoCantidad.typedValue = 1;
        document.querySelector('input#cantArtPromo').select();
        document.querySelector('input#cantArtPromo').focus();
    });

    // Evento change select combo promo
    const selCboPromo = document.querySelector('#selComboPromo');
    selCboPromo.addEventListener('change', function (e) {
        const valor = e.target.value;
        //const divPromo = document.querySelector('#divPromo');

        promocion.cambiarClaseColor(divPromo, valor);
    });

    // Evento modal: boton confirma producto seleccionado
    const confirmaArticulo = document.querySelector('#btnConfirmaArtPromo');
    confirmaArticulo.addEventListener('click', function(e) {
        const combo = parseInt(document.querySelector('#selComboPromo').value),
              codigo = document.querySelector('#selArtPromo').value,
              cantidad = strToFloat(document.querySelector('#cantArtPromo').value),
              //costo = strToFloat(document.querySelector('#costoArtPromo').value),
              precio = strToFloat(document.querySelector('#precioArtPromo').value);

        if (!_validarArticulo(codigo, cantidad, precio)) {
            return null;
        }

        if (_editaArtPromo) {
            promocion.setProductoEditado(combo, codigo, precio, cantidad);
            _editaArtPromo = false;
        } else {
            promocion.setNuevoArtic(combo, codigo, precio, cantidad);
        }

        promocion.redibujaTabla();                  // Producto a tabla en pantalla
        modalPromo.classList.add('hidden');        // Close modal
    });

    // Evento 'click' boton "Elimina" del modal articulos promo
    const btnEliminaArt = document.querySelector('button#btnEliminaArtPromo');
    btnEliminaArt.addEventListener('click', function (e) {
        const selArtPromo = document.querySelector('#selArtPromo');
        const desc = selArtPromo.options[selArtPromo.selectedIndex].textContent;
        // La eliminacion sucede en esta función...
        alertaElimina(desc.trim(), _idArtEditado);
    });

    // Evento 'click' boton "Confirma Promo" (para nuevo receta !)
    const btnConfirmaPromo = document.querySelector('#btnConfirmaPromo');
    btnConfirmaPromo.addEventListener('click', function(e) {
        if (_estanTodosLosCampos()) {
            promocion.salvarDatos();
         }
       //console.log('No estan los campos de promo completos');
    });

    /** 
     * Mensaje de alerta por eliminar articulo de promocion
     * (y elimina el articulo si confirma)
     * 
     * @param object prod
     * @param integer id (del producto a eliminar)
     * */ 
    const alertaElimina = (prod, id) => {
        Swal.fire({
            title: '¿ Está seguro ?',
            text: "Elimina: " + prod + " ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancela',
            confirmButtonText: 'Si, eliminar !'
        }).then((result) => {
            if (result.isConfirmed) {
                const modal = document.querySelector('#modalArticuloPromo');
                const prod = promocion.getArticuloEnLista(id);
                // Eliminar de _productosReceta
                promocion.eliminaArticuloDePromo(id);
                // Recargar la Receta en tabla
                promocion.redibujaTabla();
                // Cerrar el modal
                modal.classList.add('hidden');
            }
        })
    }



}

const _validarArticulo = (codigo, cantidad, precio, ) => {
    let campo = '';

    if (codigo == 0) {
        campo = 'producto';
        document.querySelector('#selArtPromo').focus();
    } else if (cantidad == 0) {
        campo = 'cantidad';
        document.querySelector('#cantArtPromo').focus();
    } else if (precio == 0) {
        campo = 'precio';
        document.querySelector('#precioArtPromo').focus();
    }

    if (campo !== '') {
        MensajeAlerta('Error, debe ingresar ' + campo, 'error');
        return false;
    }

    return true;
};

const _estanTodosLosCampos = () => {
    let estado = true,
        diasPromo = [],
        mjeError = '';
    const desde = document.querySelector('#promo_desde').value,
          hasta = document.querySelector('#promo_hasta').value,
          dias_promo = document.getElementsByName('dias_promo'),
          precioPromo = strToFloat(document.querySelector('#precioPromo').value);

    for (let dia of dias_promo) {
        if (dia.checked) {
            diasPromo.push(dia.value);
        }
    }

    if (!desde) {
        mjeError = 'Error, ingrese fecha desde';
    } else if (!hasta) {
        mjeError = 'Error, ingrese fecha hasta';
    } else if (diasPromo.length === 0) {
        mjeError = 'Error, ingrese días de semana';
    } else if (precioPromo === 0) {
        mjeError = 'Error, ingrese precio promoción';
    }

    if (mjeError) {
        MensajeAlerta(mjeError, 'error');
        estado = false;
    }

    return estado;
}

const _cargarInputsPromocion = () => {
    document.querySelector('#promo_desde').value = _getFechaHora('desde');
    document.querySelector('#promo_hasta').value = _getFechaHora('hasta');
    _PROMO._inputPrecioPromo.typedValue = _PROMO._producto_promocion.precio_vta;
    _setDiasSemana();

    return null;
}

const _getFechaHora = (pide) => {
    let dato = '';
    const arrDesde = _PROMO._producto_promocion.desde_fecha_hora.split(' '),
          arrHasta = _PROMO._producto_promocion.hasta_fecha_hora.split(' ');

    switch (pide) {
        case 'desde':
            dato = arrDesde[0];
            break;
        case 'hasta':
            dato = arrHasta[0];
            break;
        case 'inicio':
            dato = arrDesde[1];
            break;
        case 'fin':
            dato = arrHasta[1];
            break;
    }

    return dato;
}

const _setDiasSemana = () => {
    const dias = _PROMO._producto_promocion.dias_semana.split(' ');
    const checkboxes = document.querySelectorAll('input[name="dias_promo"]');

    for (let i = 0; i < checkboxes.length; i++) {
        if (dias.includes(checkboxes[i].value)) {
            checkboxes[i].checked = true;
        }
    }

    return null;
}


export { initModalPromo }; 