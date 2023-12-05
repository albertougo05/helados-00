/**
 * Busca datos del cliente de cuenta corriente
 * 
 */

export default class BuscarCliente {
    constructor(idClienteSelec) {
        this.idClienteSelec = parseInt(idClienteSelec);
    }

    buscarMostrar() {
        const _mostrarDatos = data => {
            let dir = document.querySelector('p#dataDir');
            dir.textContent = "Dirección: " + data.direccion;
            let loc = document.querySelector('p#dataLoc');
            loc.textContent = "Localidad: " + data.localidad;

            this._actualizarDatosCli(data);
         };
        const options = {
            params: {
                cliente_id: this.idClienteSelec,
            }
        };

        axios.get(_VENTA.pathBuscarCliente, options)
            .then(data => {
                _mostrarDatos(data.data);
            })
            .catch(function (e) {
                alert('No se pudo obtener datos del cliente');
            });
        return null;
    }

    _actualizarDatosCli(data) {
        _VENTA.data_clie.id = this.idClienteSelec;
        _VENTA.data_clie.nombre = data.firma + " (" + data.id + ") ";
        _VENTA.data_clie.direccion = data.direccion;
        _VENTA.data_clie.localidad = data.localidad;
        _VENTA.data_clie.plan_cuenta_id = data.plan_cuenta_id;
    }
}