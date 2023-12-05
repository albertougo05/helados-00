import { inputsConMask, eventosDelForm, disparaTabReceta, disparaTabPromo } from './init-form';
import modalProdReceta from './modalProdReceta';
import { initModalPromo } from './modalArtPromo';
import Swal from 'sweetalert2';


// Muestra mensaje de alerta, si viene de crear nuevo producto
if (_status_de_create) {
  Swal.fire({
    position: 'center',
    icon: 'success',
    title: _status_de_create,
    showConfirmButton: false,
    timer: 3000
    });
}

// Set eventos
eventosDelForm('edit');

// Init inputs
const inputs = inputsConMask;

// Valores para inputs
let inputPrecio1 = inputs.inputPrecio1;
inputPrecio1.typedValue = _producto.precio_lista_1;
let inputPrecio2 = inputs.inputPrecio2;
inputPrecio2.typedValue = _producto.precio_lista_2;
let inputPrecio3 = inputs.inputPrecio3;
inputPrecio3.typedValue = _producto.precio_lista_3;
let inputCostoUnid = inputs.inputCostoUnid;
inputCostoUnid.typedValue = _producto.costo_x_unidad;
let inputCostoBulto = inputs.inputCostoBulto;
inputCostoBulto.typedValue = _producto.costo_x_bulto;
let inputPesoMP = inputs.inputPesoMP;
inputPesoMP.typedValue = _producto.peso_materia_prima;

// Inicializo modal receta
modalProdReceta();

if (_producto.tipo_producto_id == 4) {
    // Inicializo modal promociones
    initModalPromo();
}


window.onload = function() {
  // Si producto_indiv_id es 0 o null
  if (_producto.articulo_indiv_id === null || _producto.articulo_indiv_id == "0") {
    document.querySelector('input#unidades_x_caja').setAttribute('readonly', true);
    document.querySelector('input#unidades_x_caja').value = '';
    document.querySelector('input#cajas_x_bulto').setAttribute('readonly', true);
    document.querySelector('input#cajas_x_bulto').value = '';
  }

  // Convierto en float importes de productos (viene de DB como string)
  _productos_receta = _productos_receta.map(el => {
    el.costo_x_unidad = parseFloat(el.costo_x_unidad);

    return el;
  });

  _productosReceta = _productosReceta.map(el => {
    el.cantidad = parseFloat(el.cantidad);
    el.costo = parseFloat(el.costo);

    return el;
  });

  const tabCompra = document.querySelector('#tabDatosCompra');
  const tabVenta = document.querySelector('#tabDatosVenta');
  const tabPromo = document.querySelector('#tabPromo');
  const tabReceta = document.querySelector('#tabReceta');

  // Si tipo_producto = elaborado, activa tab receta
  if (_producto.tipo_producto_id == 1) {
      // Desactivo tabs compras y promo
      tabCompra.classList.add('pointer-events-none');
      tabCompra.disabled = true;
      tabPromo.classList.add('pointer-events-none');
      tabPromo.disabled = true;
      disparaTabReceta();
  }

  if (_producto.tipo_producto_id == 2 || _producto.tipo_producto_id == 3) {
    tabReceta.disabled = true;    // Desactivo tabs receta y promo
    tabReceta.classList.add('pointer-events-none');
    tabPromo.disabled = true;
    tabPromo.classList.add('pointer-events-none');
  }

  // Si tipo_producto == Promocion, desactivo tabs venta y receta
  if (_producto.tipo_producto_id == 4) {
    tabReceta.classList.add('pointer-events-none');
    tabReceta.disabled = true;
    tabVenta.classList.add('pointer-events-none');
    tabVenta.setAttribute('aria-disabled', true);
    tabVenta.disabled = true;
    disparaTabPromo();
  }

  // Si tipo_producto = Materia Prima, desactivo tabs receta y promociones
  if (_producto.tipo_producto_id == 5) {
      tabReceta.classList.add('pointer-events-none');
      tabReceta.disabled = true;
      tabPromo.classList.add('pointer-events-none');
      tabPromo.disabled = true;
  }

};
