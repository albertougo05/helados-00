/** 
 * 
 * Turnos/Informe/index.js
 * 
 */


// Redirige a la pagina filtrada
const enviarFiltro = datos => {
    let url = `/turnos/informe/filtrado?usuario_id=${datos.usuario_id}`;
    url += `&caja_nro=${datos.caja_nro}&desde=${datos.desde}`;
    url += `&hasta=${datos.hasta}`;
    location.assign(url);
}


window.onload = function() {
    // Click en boton filtrar turnos
    const btnFiltrar = document.querySelector('#btnFiltrar');
    btnFiltrar.addEventListener('click', function (e) {
        const datos = {
            usuario_id: document.querySelector('#selUsuario').value,
            caja_nro: document.querySelector('#caja_nro').value,
            desde: document.querySelector('#fecha_desde').value,
            hasta: document.querySelector('#fecha_hasta').value,
        };

        enviarFiltro(datos);
    });
};