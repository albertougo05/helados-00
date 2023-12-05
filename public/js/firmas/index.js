/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/js/firmas/index.js ***!
  \**************************************/
//
// ../firmas/index.js
//
var setInputBuscar = function setInputBuscar(filtro) {
  // const text = filtro[2].replace('%', '');
  document.querySelector('input#buscar').value = text;
}; // const setSelect = (filtro, listado) => {
//     const valor = filtro[2];
//     const select = document.querySelector('select#' + filtro[0]);
//     // Buscar en listado
//     let idx = listado.findIndex(e => e.id == valor);
//     // Selecciono en select de productos
//     select.options[idx + 1].selected = true;
// }
// @if($filtros)
//     const filtros = @json($filtros);
//     const btnQuitar = document.querySelector('button#btnQuitarBusq');
//     btnQuitar.addEventListener('click', function () {
//         location.assign('/producto');
//     });
//     // setear input y selec
//     for (const filtro of filtros) {
//         switch (filtro[0]) {
//             case 'firma':
//                 console.log('filtro buscar');
//                 setInputBuscar(filtro);
//                 break;
//             case 'proveedor_id':
//                 setSelect(filtro, proveedores);
//                 break;
//             case 'tipo_producto_id':
//                 console.log('filtro tipo');
//                 //setSelect(filtro, tipos);
//                 break;
//             case 'grupo_id':
//                 console.log('filtro grupo');
//                 //setSelect(filtro, grupos);
//                 break;
//         }
//     }
// @endif


var btnFiltrar = document.querySelector('button#btnFiltrar');
btnFiltrar.addEventListener('click', function (e) {
  var tipo = document.querySelector('select#tipo_firma').value;
  var busq = document.querySelector('input#buscar').value || '';
  var url = _FIRMA.pathFiltrado + '?tipo=' + tipo + '&buscar=' + busq;
  console.log('Url:', url); //location.assign(url);
});
/******/ })()
;