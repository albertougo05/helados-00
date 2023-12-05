/** 
 * Acciones del modal Producto para receta
 */

import resetModal from './resetModal';
import Receta from "./Receta";
import MensajeAlerta from "../utils/MensajeAlerta";
import Swal from "sweetalert2";


// Clase Receta. 
let receta = new Receta();
receta.cargaProductos(_productosReceta);


var modalProdReceta = function () {
    // Modal ingreso Productos a Receta
    let modal = document.querySelector('.modalReceta'),
        showModal = document.querySelector('#btnAgregarProducto'),
        closeModal = document.querySelectorAll('.close-modal');

    // Evento receta: boton agregar producto (muestra modal)
    showModal.addEventListener('click', function() {
        resetModal(_inputModalCosto, _inputModalCantidad);
        modal.classList.remove('hidden');
    });

    // Evento modal: boton Cancela y X
    closeModal.forEach(close => {
        close.addEventListener('click', function() {
            modal.classList.add('hidden');
            _editaProducto = false;
        });
    });

    // Evento modal: Select producto (change)
    const selProdReceta = document.querySelector('#selProductoReceta');
    selProdReceta.addEventListener('change', function(e) {
        const codigo = e.target.value;

        if (receta.checkProdEnLista(codigo)) {
            MensajeAlerta('El producto ya está en la lista!');
            e.target.value = 0;
            e.target.focus();

            return null;
        }

        let costo = 0;
        // Buscar en _productos_receta (aptos receta)
        const idx = _productos_receta.findIndex(e => e.codigo == codigo);
        costo = _productos_receta[idx].costo_x_unidad;
        _inputModalCosto.typedValue = costo;   // Ingreso costo al input
        document.querySelector('input#modalCantidad').focus();
    });

    // Evento modal: boton confirma producto seleccionado
    const confirmaModal = document.querySelector('#btnConfirmaModal');
    confirmaModal.addEventListener('click', function(e) {
        const codigo = document.querySelector('#selProductoReceta').value;
        const cantidad = document.querySelector('#modalCantidad').value;
        const costo = document.querySelector('#modalCosto').value;

        if (!_validaCamposReceta(codigo, cantidad, costo)) {
            return null;
        }

        if (_editaProducto) {
            receta.setProductoEdit(codigo, costo, cantidad);
            receta.redibujaTabla();
            _editaProducto = false;
        } else {
            receta.setNuevoProd(codigo, cantidad, costo);
            receta.nuevoProductoATabla();        // Producto a tabla en pantalla
        }
        // Close modal
        document.querySelector('.modalReceta').classList.add('hidden');
    });

    // Evento 'click' boton "Elimina" del modal productos Receta
    const btnElimina = document.querySelector('button#btnEliminaProdReceta');
    btnElimina.addEventListener('click', function (e) {
        const selProdReceta = document.querySelector('#selProductoReceta');
        const desc = selProdReceta.options[selProdReceta.selectedIndex].textContent;

        // La eliminacion sucede en esta función...
        alertaElimina(desc.trim(), _idProdEditado);
    });

    // Evento 'click' boton "Confirma Receta" (para nuevo receta !)
    const btnConfirmaReceta = document.querySelector('#btnConfirmaReceta');
    btnConfirmaReceta.addEventListener('click', async function(e) {
        const productosReceta = receta.getListaProductosToStore();
        const inputConReceta = document.querySelector('input#con_receta');
        const configAxios = {
            method: 'POST',
            url: '/producto_receta',
            data: productosReceta
        };

        //console.log('Confirma productos recta:', productosReceta, 'Valor input con Receta:', inputConReceta.value)

        try {
            const response = await axios(configAxios);
            MensajeAlerta('Receta guardada con éxito !', 'success');

            setTimeout(() => {
                const form = document.querySelector('form');
                form.submit();
            }, 4000);

            // if (inputConReceta.value === "0") {
            //     inputConReceta.value = 1;
            //     const configAxios2 = {
            //         method: 'GET',
            //         url: '/producto/actualizar',
            //         data: {id: _producto_id, con_receta: 1}
            //     };

            //     try {
            //         const response2 = await axios(configAxios2);
   
            //     } catch (error) {
            //         MensajeAlerta('Error, no se pudo actualizar producto! \n\r' + error);
            //         return null;
            //     }
            // }

        } catch (error) {
            MensajeAlerta('Error, no se pudo guardar receta! \n\r' + error);
        }

        return null;
    });

    /** 
     * Mensaje de alerta por eliminar sub-producto de receta 
     * (y elimina el producto si confirma)
     * 
     * @param object producto
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
                const modal = document.querySelector('.modalReceta');
                const prod = receta.getProductoEnLista(id);

                if (prod.id > 0) {  // Si tiene id, eliminar de DB...
                    receta.eliminaProdDeDB(prod.id)
                        .then(data => {
                            //console.log('Status:', data.data.status);
                        });
                }
                // Eliminar de _productosReceta
                receta.eliminaProductoDeReceta(id);
                if (_productosReceta.length === 0) {
                    document.querySelector('#con_receta').value = 0;
                }
                // Recargar la Receta en tabla
                receta.redibujaTabla();
                // Cerrar el modal
                modal.classList.add('hidden');
                _editaProducto = false;
            }
        })
    }

};

const _validaCamposReceta = (codigo, cantidad, costo) => {
    let campo = '';

    if (codigo == 0) {
        campo = 'producto';
        e.target.focus();
    } else if (cantidad == 0) {
        campo = 'cantidad';
        document.querySelector('#modalCantidad').focus();
    } else if (costo == 0) {
            campo = 'costo';
            document.querySelector('#modalCosto').focus();
    }

    if (campo !== '') {
        MensajeAlerta('Error, debe ingresar ' + campo);
        return false;
    }

    return true;
}

export default modalProdReceta;