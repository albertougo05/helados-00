/**
 * 
 * Imprimir en http://localhost/printer/print.php...
 * 
 */


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


const btnImprimir = document.querySelector('#btnImprimir');
btnImprimir.addEventListener('click', function (e) {
    const nombreImp = document.querySelector('#nombreImp').value;    // EPSON_TM-T20III
         
    // _enviarImpresion(nombreImp)
    //     .then(resp => {
    //         console.log('Respuesta:', resp.ok);
    //     });

    _imprimeParzi(nombreImp)
        .then(resp => {
            console.log('Respuesta:', resp);
    });

    // _imprimeAbra(nombreImp)
    // .then(resp => {
    //     console.log('Respuesta:', resp);
    // });

});


const _enviarImpresion = async nombreImp => {
    const data = { 
        so: _retornarSO(),
        name_prn: nombreImp,
        datos: [ { id: 1, name: 'Alberto' },
                 { id: 2, name: 'Sonia' } ]
    };

    const config = {
        method: 'POST',
        cache: 'no-cache',
        body: JSON.stringify(data),
        mode: 'no-cors',
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET,POST,DELETE,PUT,OPTIONS',
            'Access-Control-Allow-Headers': '*',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Credentials': 'true'
        },
    }; 
    //so = _retornarSO(),
    //url = `http://localhost/printer/print.php?so=${so}&name_prn=${nombreImp}`;
    const url = `http://localhost/printer/print.php`;

    console.log('Imprimir en', nombreImp, 'Url:', url);

    const resp = await fetch(url, config);

    return resp;
};

const _imprimeParzi = async impresora => {
    const conector = new ConectorPluginV3();
    const respuesta = await conector
        .Iniciar()
        //.DeshabilitarElModoDeCaracteresChinos()
        .Corte(1)
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
        .Feed(1)
        .EscribirTexto("Prueba de impresión\n")
        .EscribirTexto("____________________\n")
        .EscribirTexto("Fecha y hora: " + (new Intl.DateTimeFormat("es-AR").format(new Date())))
        .Feed(1)
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA)
        .EscribirTexto("____________________\n")
        .TextoSegunPaginaDeCodigos(2, "cp850", "Venta de plugin para impresoras versión 3\n")
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_DERECHA)
        .EscribirTexto("____________________\n")
        .EscribirTexto("TOTAL: $25\n")
        .EscribirTexto("____________________\n")
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
        .EscribirTexto("Alineado centro\n")
        .EstablecerEnfatizado(true)
        .EstablecerTamañoFuente(1, 1)
        .EscribirTexto("Tamaño 1.1\n")
        .Feed(1)
        .EstablecerTamañoFuente(2, 1)
        .EscribirTexto("Texto 2.1\n")
        .EstablecerTamañoFuente(2, 2)
        .EscribirTexto("Texto 2.2\n")
        .Feed(3)
        .Corte(1)
        .Pulso(48, 60, 120)
        .imprimirEn(impresora);
    if (respuesta === true) {
        alert("Impreso correctamente");
    } else {
        alert("Error: " + respuesta);
    }

    return respuesta;
};

async function _imprimeAbra(nombreImpresora){
    const api_key = "12345";

    const conector = new connetor_plugin();

    conector.fontsize("2");
    conector.textaling("center");
    conector.text("Ferreteria Angel");
    conector.fontsize("1");
    conector.text("Calle de las margaritas #45854");
    conector.text("PEC7855452SCC");
    conector.feed("3");
    conector.textaling("left");
    conector.text("Fecha: Miercoles 8 de ABRIL 2022 13:50");
    conector.text("Cant.     Descripcion    Importe");
    conector.text("--------------------------------");
    conector.text("  1   Barrote 2x4x8     $ 110.00");
    conector.text("  3   Esquinero Vinil   $ 165.00");
    conector.feed("1");
    conector.fontsize("2");
    conector.textaling("center");
    conector.text("Total: $275");
    conector.feed("5");
    conector.cut("0");

    const resp = await conector.imprimir(nombreImpresora, api_key);
    if (resp === true) {              
        return 'ok';
    } else {
        console.log("Problema al imprimir: "+resp)                    
    }
};