/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/utils/solo-numeros.js":
/*!********************************************!*\
  !*** ./resources/js/utils/solo-numeros.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "_redondeoExacto": () => (/* binding */ _redondeoExacto),
/* harmony export */   "_roundToTwo": () => (/* binding */ _roundToTwo),
/* harmony export */   "soloNumeros": () => (/* binding */ soloNumeros),
/* harmony export */   "strToCurrency": () => (/* binding */ strToCurrency),
/* harmony export */   "strToFloat": () => (/* binding */ strToFloat)
/* harmony export */ });
/**
 * Utilidades Varias con números
 */
// Cuenta caracteres de una cadena (.) puntos
String.prototype.count = function (c) {
  var result = 0,
      i = 0;

  for (i; i < this.length; i++) {
    if (this[i] == c) result++;
  }

  return result;
}; // Mascara de solo ingreso de número en input


var soloNumeros = function soloNumeros(e) {
  var valor = e.target.value;

  if (/\D/g.test(valor)) {
    // Filter non-digits from input value.
    e.target.value = e.target.value.replace(/\D/g, '');
  }
}; // Convierte string '1.250.000,25' A (float) 1250000.25


var strToFloat = function strToFloat(num) {
  if (num === "" || num === null) return 0;

  if (typeof num === 'string') {
    num = num.replaceAll('.', '');
    num = num.replace(',', '.');
  }

  num = parseFloat(num);
  return num;
};

var strToCurrency = function strToCurrency(num) {
  var returnSpace = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var dec = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 2;

  if (num === "" || num === null || num === '0.00' || num === '0,00') {
    if (returnSpace) {
      return "";
    } else return num;
  }

  if (typeof num === 'string') {
    num = num.replaceAll('.', '');
    num = num.replace(',', '.');
  }

  num = parseFloat(num);
  return num.toFixed(dec) // decimal digits
  .replace('.', ',') // replace decimal point character with ,
  .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
};
/**
 * Redondea a dos decimales
 * Fuente: https://www.delftstack.com/es/howto/javascript/javascript-round-to-2-decimal-places/
 * 
 * @param {*} num 
 * @returns decimal
 */


function _roundToTwo(num) {
  return +(Math.round(num + "e+2") + "e-2");
}
/**
 * Redondea a cantidad x de decimales
 * Fuente: https://medium.com/swlh/how-to-round-to-a-certain-number-of-decimal-places-in-javascript-ed74c471c1b8
 * 
 * @param {number} num 
 * @param {integer} decPlaces
 * @returns {number}
 */


var _redondeoExacto = function _redondeoExacto(num, decPlaces) {
  return Number(Math.round(num + "e" + decPlaces) + "e-" + decPlaces);
};



/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!***********************************************!*\
  !*** ./resources/js/ventas/infoventasprod.js ***!
  \***********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_solo_numeros__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/solo-numeros */ "./resources/js/utils/solo-numeros.js");
//
// infoventasprod.js
//
 // Evento select al seleccionar producto

var _selProducto = document.querySelector('#selProducto');

_selProducto.addEventListener('change', function (e) {
  var idSelec = e.target.value;

  _buscarYmostrar(idSelec);
}); // Evento al cambiar fechas


var _fechaDesde = document.querySelector('#fecha_desde');

_fechaDesde.addEventListener('change', function (e) {
  var idSelec = document.querySelector('#selProducto').value;

  _buscarYmostrar(idSelec);
});

var _fechaHasta = document.querySelector('#fecha_hasta');

_fechaHasta.addEventListener('change', function (e) {
  var idSelec = document.querySelector('#selProducto').value;

  _buscarYmostrar(idSelec);
}); // Buscar y mostrar movimientos


var _buscarYmostrar = function _buscarYmostrar() {
  var idSelec = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;

  if (idSelec == 0) {
    var bodyTable = document.querySelector('#bodyTablaMovs');
    bodyTable.innerHTML = '';
    return null;
  }

  var options = {
    params: {
      id: idSelec,
      desde: document.querySelector('#fecha_desde').value,
      hasta: document.querySelector('#fecha_hasta').value
    }
  };
  axios.get("/ventas/ventasprod", options).then(function (res) {
    //console.log('Listado', res.data.listado);
    _muestraMovimientos(res.data.listado);
  })["catch"](function (e) {
    alert('No se pudo obtener datos: ' + e);
  });
};

var _muestraMovimientos = function _muestraMovimientos(data) {
  var movs = [];
  var bodyTable = document.querySelector('#bodyTablaMovs');
  bodyTable.innerHTML = '';
  movs = data.map(function (el) {
    el.cantidad = parseFloat(el.cantidad);
    el.precio_unitario = parseFloat(el.precio_unitario);
    el.subtotal = parseFloat(el.subtotal);
    return el;
  });
  movs.forEach(function (elem) {
    var tr = document.createElement("tr");
    tr.classList.add("hover:bg-gray-100");
    var td1 = document.createElement("td");
    td1.classList.add("text-center");
    td1.textContent = _transformFecha(elem.fecha);
    tr.appendChild(td1);
    var td2 = document.createElement("td");
    td2.classList.add("text-center");
    td2.textContent = elem.hora.substring(0, 5);
    tr.appendChild(td2);
    var td3 = document.createElement("td");
    td3.classList.add("text-right", "pr-3"); //td3.textContent = _creaNroComprob(elem.codigo_comprob, elem.nro_comprobante);

    td3.textContent = elem.nro_comprobante;
    tr.appendChild(td3);
    var td4 = document.createElement("td");
    td4.classList.add("text-center");
    td4.textContent = elem.nombre;
    tr.appendChild(td4);
    var td5 = document.createElement("td");
    td5.textContent = elem.descripcion;
    td5.classList.add("text-center", "pr-3");
    tr.appendChild(td5);
    var td6 = document.createElement("td");
    td6.textContent = _formatCant(elem.cantidad);
    td6.classList.add("text-right", "pr-3");
    tr.appendChild(td6);
    bodyTable.appendChild(tr);
  });
};

var _transformFecha = function _transformFecha() {
  var fecha = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
  var arrDias = fecha.split('-');
  return arrDias.reverse().join('/');
};

var _formatCant = function _formatCant(cant) {
  if (Number.isInteger(cant)) {
    cant = parseInt(cant);
  } else {
    cant = (0,_utils_solo_numeros__WEBPACK_IMPORTED_MODULE_0__.strToCurrency)(cant, false, 3);
  }

  return cant;
};

var _creaNroComprob = function _creaNroComprob(codigo, numero) {
  var nro = 'B ';
  nro += _padLeft(codigo, 4) + '-';
  nro += _padLeft(numero, 8);
  return nro;
};

function _padLeft(number, length, character) {
  if (character == null) {
    character = '0';
  }

  var result = String(number);

  for (var i = result.length; i < length; ++i) {
    result = character + result;
  }

  return result;
}
})();

/******/ })()
;