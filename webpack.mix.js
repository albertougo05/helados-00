const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/print/print.js', 'public/js/print')
    .js('resources/js/ctasctes/index.js', 'public/js/ctasctes')
    .js('resources/js/productos/index.js', 'public/js/productos')
    .js('resources/js/productos/create.js', 'public/js/productos')
    .js('resources/js/productos/edit.js', 'public/js/productos')
    .js('resources/js/productos/listaprecios/index.js', 'public/js/productos/listaprecios')
    .js('resources/js/productos/sucursal/index.js', 'public/js/productos/sucursal')
    .js('resources/js/stock/index.js', 'public/js/stock')
    .js('resources/js/stock/real/index.js', 'public/js/stock/real')
    .js('resources/js/stock/real/edit.js', 'public/js/stock/real')
    .js('resources/js/stock/infomovs/index.js', 'public/js/stock/infomovs')
    .js('resources/js/stock/infodiario/index.js', 'public/js/stock/infodiario')
    .js('resources/js/stock/infodesvio/index.js', 'public/js/stock/infodesvio')
    .js('resources/js/ventas/index.js', 'public/js/ventas')
    .js('resources/js/ventas/infoventasprod.js', 'public/js/ventas')
    .js('resources/js/ventas/infopedidos/index.js', 'public/js/ventas/infopedidos')
    .js('resources/js/ventas/mix/index.js', 'public/js/ventas/mix')
    .js('resources/js/firmas/index.js', 'public/js/firmas')
    .js('resources/js/turnos/create.js', 'public/js/turnos')
    .js('resources/js/turnos/edit.js', 'public/js/turnos')
    .js('resources/js/turnos/informe/index.js', 'public/js/turnos/informe')
    .js('resources/js/turnos/imprime/cierre.js', 'public/js/turnos/imprime')
    .js('resources/js/turnos/show.js', 'public/js/turnos')
    .js('resources/js/caja/index.js', 'public/js/caja')
    .js('resources/js/caja/create.js', 'public/js/caja')
    .js('resources/js/caja/edit.js', 'public/js/caja')
    .js('resources/js/caja/informe/index.js', 'public/js/caja/informe')
    .js('resources/js/parametros/index.js', 'public/js/parametros')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]);
