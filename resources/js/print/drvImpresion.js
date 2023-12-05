/**
 * 
 * Driver para impresiones en comandera
 * 
 */
const __getConfig = () => {
    return {
            method: 'GET',
            cache: 'no-cache',
            mode: 'no-cors',
            headers: {
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET,POST,DELETE,PUT,OPTIONS',
                'Access-Control-Allow-Headers': '*',
                'Content-Type': 'application/json',
                'Access-Control-Allow-Credentials': 'true'
        }
    }
}; 

// const _getConfigPost = (data) => {
//     return {
//             method: 'POST',
//             cache: 'no-cache',
//             body: JSON.stringify(data),     // Para POST
//             mode: 'no-cors',
//             headers: {
//                 'Access-Control-Allow-Origin': '*',
//                 'Access-Control-Allow-Methods': 'GET,POST,DELETE,PUT,OPTIONS',
//                 'Access-Control-Allow-Headers': '*',
//                 'Content-Type': 'application/json',
//                 'Access-Control-Allow-Credentials': 'true'
//         }
//     }
// }; 

const _retornarSO = function() {
    let so = '';
    const navInfo = window.navigator.userAgent.toLowerCase();

	if(navInfo.indexOf('win') != -1) {
		so = 'Windows';
	} else if(navInfo.indexOf('linux') != -1) {
		so = 'Linux';
	} else if(navInfo.indexOf('mac') != -1) {
		so = 'Mac';
	}

	return so;
}

const _urls = {
    ticket_vta: `http://localhost/printer/ticket.php`,
    cierre_caja: `http://localhost/printer/cierrecaja.php`,
    ingreso_stock: `http://localhost/printer/ingresostock.php`,
    info_diario: `http://localhost/printer/infodiario.php`,
    movim_caja: `http://localhost/printer/movimcaja.php`,
};

const __getParametros = (queImp, params) => {
    let parametros = '?';

    switch (queImp) {
        case 'ticket_vta':
            parametros += `idcomp=${params.idcomp}&idsuc=${params.idsuc}&iduser=${params.iduser}&`;
            parametros += `turnosuc=${params.turnosuc}&impres=${params.impres}&`;
            parametros += `sisop=${_retornarSO()}&env=${params.env}`;            
            break;
        case 'cierre_caja':
            parametros += `sucursal_id=${params.sucursal_id}&`;
            parametros += `cierre_id=${params.cierre_id}&impres=${params.impres}&`;
            parametros += `sisop=${_retornarSO()}&env=${params.env}`;
            break;
        case 'ingreso_stock':
            parametros += `sucursal_id=${params.sucursal_id}&comprob_id=${params.comprob_id}&`;
            parametros += `impres=${params.impres}&`;
            parametros += `sisop=${_retornarSO()}&env=${params.env}`;
            break;
        case 'info_diario':
            parametros += `sucursal_id=${params.sucursal_id}&fecha=${params.fecha}&`;
            parametros += `hora=${params.hora}&impres=${params.impres}&`;
            parametros += `sisop=${_retornarSO()}&env=${params.env}&sucursal=${params.sucursal}`;
            break;
        case 'movim_caja':
            parametros += `sucursal=${params.sucursal}&movnro=${params.movim_nro}&`;
            parametros += `fechahora=${params.fecha_hora}&usuario=${params.usuario}&`;
            parametros += `tipomovim=${params.tipo_movim}&turnonro=${params.turno_nro}&`;
            parametros += `concepto=${params.concepto}&importe=${params.importe}&`;
            parametros += `observac=${params.observac}&impres=${params.impres}&`;
            parametros += `sisop=${_retornarSO()}&env=${params.env}`;
            break;
    }

    return parametros;
};

const __getUrl = (queImp, params) => {
    const parametros = __getParametros(queImp, params);
    let url = '';

    switch (queImp) {
        case 'ticket_vta':
            url = _urls.ticket_vta;
            break;
        case 'cierre_caja':
            url = _urls.cierre_caja;
            break;
        case 'ingreso_stock':
            url = _urls.ingreso_stock;
            break;
        case 'info_diario':
            url = _urls.info_diario;
            break;
        case 'movim_caja':
            url = _urls.movim_caja;
    }

    return url + parametros;
};


/**
 * Driver para imprimir Ticket Venta. Llama al controlador local 
 * 
 * @param {*} queImprime
 * @param {*} params 
 * @returns 
 */
const _drvImpresion = async (queImprime, params) => {
    const url    = __getUrl(queImprime, params),
          config = __getConfig(),
          resp   = await fetch(url, config);

    return resp;
}


export { _drvImpresion };