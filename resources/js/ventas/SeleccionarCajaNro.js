/**
 * 
 * Muestra ventana para seleccionar número de caja
 * para usuarios administradores
 * 
 */

import Swal from "sweetalert2";


class SeleccionarCajaNro {
    constructor(cajasSuc) {
        this.cajasSuc = cajasSuc;
    }

    muestraAlert() {
      if (this.cajasSuc.length === 2) {
        this._dosCajas();
      } else if (this.cajasSuc.length === 3) {
        this._tresCajas();
      }

     // console.log(this.cajasSuc);
      this.muestraEnPant(_VENTA.caja_nro);

      return null;
    }

    muestraEnPant(caja_nro) {
      const newText = _VENTA.sucursal_nombre + ' - Caja Nro. ' + caja_nro;
      const pTexto = document.querySelector('#suc_cajaNro')
      pTexto.innerHTML = newText;

      return null;
    }

    async _dosCajas() {
      await Swal.fire({
        title: 'Seleccione una caja !',
        //text: "You won't be able to revert this!",
        icon: 'question',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#60a9ef',
        confirmButtonText: 'Caja 1',
        cancelButtonText: 'Caja 2',
      }).then((result) => {
        if (result.isConfirmed) {
          _VENTA.caja_nro = 1;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          _VENTA.caja_nro = 2;
        }

        const newText = _VENTA.sucursal_nombre + ' - Caja Nro. ' + _VENTA.caja_nro;
        const pTexto = document.querySelector('#suc_cajaNro')
        pTexto.innerHTML = newText;
      })
    }

    _tresCajas() {}
}

export default SeleccionarCajaNro;