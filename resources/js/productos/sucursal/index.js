//
//  /productos/sucursal/index.js
//

// Click boton actualizar
const btnActualiza = document.querySelector('#btnActualizar');
btnActualiza.addEventListener('click', function (e) {
    // Hacer visible spin...
    const spinGuardando = document.querySelector('#spinGuardando');
    spinGuardando.classList.remove("hidden");
    // Desactivo el boton Salir
    const btnSalir = document.querySelector('#btnSalir');
    btnSalir.href = 'javascript:void(0)';
    // Desactivo el boton que actualiza
    e.target.disabled = true;

    //console.log('Sucursal id:', _sucursal.id);
    const url = '/producto/sucursal/store?id=' + _sucursal.id;
    window.location.assign(url);

});