//
// infoventasprod.js
//
import { strToCurrency } from "../utils/solo-numeros";



// Evento select al seleccionar producto
const _selProducto = document.querySelector('#selProducto');
_selProducto.addEventListener('change', (e) => {
    const idSelec = e.target.value;

    _buscarYmostrar(idSelec);
});

// Evento al cambiar fechas
const _fechaDesde = document.querySelector('#fecha_desde');
_fechaDesde.addEventListener('change', (e) => {
    const idSelec = document.querySelector('#selProducto').value;

    _buscarYmostrar(idSelec);
});

const _fechaHasta = document.querySelector('#fecha_hasta');
_fechaHasta.addEventListener('change', (e) => {
    const idSelec = document.querySelector('#selProducto').value;

    _buscarYmostrar(idSelec);
});

// Buscar y mostrar movimientos
const _buscarYmostrar = function (idSelec = 0) {

    if (idSelec == 0) {
        const bodyTable = document.querySelector('#bodyTablaMovs');
        bodyTable.innerHTML = '';
        return null;
    }

    const options = {
        params: {
            id: idSelec,
            desde: document.querySelector('#fecha_desde').value,
            hasta: document.querySelector('#fecha_hasta').value,
        }
    };

    axios.get("/ventas/ventasprod", options)
        .then(function (res) {

            //console.log('Listado', res.data.listado);

            _muestraMovimientos(res.data.listado);
        })
        .catch(function (e) {
            alert('No se pudo obtener datos: ' + e );
        });
};

const _muestraMovimientos = function (data) {
    let movs = [];
    const bodyTable = document.querySelector('#bodyTablaMovs');
    bodyTable.innerHTML = '';

    movs = data.map(el => {
        el.cantidad = parseFloat(el.cantidad);
        el.precio_unitario = parseFloat(el.precio_unitario);
        el.subtotal = parseFloat(el.subtotal);

        return el;
    });

    movs.forEach(function (elem) {
        const tr = document.createElement("tr");
        tr.classList.add("hover:bg-gray-100");

        const td1 = document.createElement("td");
        td1.classList.add("text-center");
        td1.textContent = _transformFecha(elem.fecha);
        tr.appendChild(td1);
        const td2 = document.createElement("td");
        td2.classList.add("text-center");
        td2.textContent = elem.hora.substring(0, 5);
        tr.appendChild(td2);
        const td3 = document.createElement("td");
        td3.classList.add("text-right", "pr-3");
        //td3.textContent = _creaNroComprob(elem.codigo_comprob, elem.nro_comprobante);
        td3.textContent = elem.nro_comprobante;
        tr.appendChild(td3);
        const td4 = document.createElement("td");
        td4.classList.add("text-center");
        td4.textContent = elem.nombre;
        tr.appendChild(td4);
        const td5 = document.createElement("td");
        td5.textContent = elem.descripcion;
        td5.classList.add("text-center", "pr-3");
        tr.appendChild(td5);
        const td6 = document.createElement("td");
        td6.textContent = _formatCant(elem.cantidad);
        td6.classList.add("text-right", "pr-3");
        tr.appendChild(td6);

        bodyTable.appendChild(tr);
    });
};

const _transformFecha = function (fecha = '') {
    const arrDias = fecha.split('-');
    return arrDias.reverse().join('/');
};

const _formatCant = cant => {
    if (Number.isInteger(cant)) {
        cant = parseInt(cant);
    } else {
        cant = strToCurrency(cant, false, 3);
    }

    return cant;
};

const _creaNroComprob = (codigo, numero) => {
    let nro = 'B ';
    nro += _padLeft(codigo, 4) + '-';
    nro += _padLeft(numero, 8);

    return nro;
};

function _padLeft(number, length, character) {
	if(character == null) {
		character = '0';
    }
	var result = String(number);
	for(var i = result.length; i < length; ++i) {
		result = character + result;
	}
	return result;
}