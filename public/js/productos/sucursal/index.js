/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************************!*\
  !*** ./resources/js/productos/sucursal/index.js ***!
  \**************************************************/
//
//  /productos/sucursal/index.js
//
// Click boton actualizar
var btnActualiza = document.querySelector('#btnActualizar');
btnActualiza.addEventListener('click', function (e) {
  // Hacer visible spin...
  var spinGuardando = document.querySelector('#spinGuardando');
  spinGuardando.classList.remove("hidden"); // Desactivo el boton Salir

  var btnSalir = document.querySelector('#btnSalir');
  btnSalir.href = 'javascript:void(0)'; // Desactivo el boton que actualiza

  e.target.disabled = true; //console.log('Sucursal id:', _sucursal.id);

  var url = '/producto/sucursal/store?id=' + _sucursal.id;
  window.location.assign(url);
});
/******/ })()
;