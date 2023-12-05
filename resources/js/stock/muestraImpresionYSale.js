import { _drvImpresion } from '../print/drvImpresion';


const _getParamsIngresoStk = () => {
    return {
        comprob_id: _STOCK.comprobante_id,
        sucursal_id: _STOCK.sucursal_id,
        impres: _STOCK._nombreImpresora,
        env: _STOCK._env,
    };
};

const muestraImpresionYSale = () => {
    let url = _STOCK.pathImprime + '/' + _STOCK.comprobante_id;

    if (_STOCK._nombreImpresora) {
        _drvImpresion('ingreso_stock', _getParamsIngresoStk())
            .then(function (response) {
                return null;
            })
            .catch(function (error) {   // handle error
                alert('Error al imprimir ! \n' + error);
                return null;
            })
            .finally(() =>{
                setTimeout(function () {
                    location.reload();
                }, 3000);
        });
    } else {
        window.open(url, '_blank'); // Abre pestaña del ticket
        setTimeout(function () {
            location.reload();
        }, 3000);
    }

    return null;
};


export default muestraImpresionYSale;