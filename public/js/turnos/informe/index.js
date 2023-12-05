/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/js/turnos/informe/index.js ***!
  \**********************************************/
/** 
 * 
 * Turnos/Informe/index.js
 * 
 */
// Redirige a la pagina filtrada
var enviarFiltro = function enviarFiltro(datos) {
  var url = "/turnos/informe/filtrado?usuario_id=".concat(datos.usuario_id);
  url += "&caja_nro=".concat(datos.caja_nro, "&desde=").concat(datos.desde);
  url += "&hasta=".concat(datos.hasta);
  location.assign(url);
};

window.onload = function () {
  // Click en boton filtrar turnos
  var btnFiltrar = document.querySelector('#btnFiltrar');
  btnFiltrar.addEventListener('click', function (e) {
    var datos = {
      usuario_id: document.querySelector('#selUsuario').value,
      caja_nro: document.querySelector('#caja_nro').value,
      desde: document.querySelector('#fecha_desde').value,
      hasta: document.querySelector('#fecha_hasta').value
    };
    enviarFiltro(datos);
  });
};
/******/ })()
;