<?php

use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\URL;


//if (env('APP_ENV') === 'production') {
//    URL::forceSchema('https');
//}


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


// Acceso a Laravel UI
// Route::get('/welcome', function () {
//     return view('welcome');
// });

Route::prefix('/toprint')->group(function () {
    Route::get('/ticketventa', [App\Http\Controllers\Printer\TicketVentaController::class, 'enviar']);
    Route::get('/cierrecaja', [App\Http\Controllers\Printer\CierreCajaController::class, 'enviar']);
    Route::get('/infodiario', [App\Http\Controllers\Printer\InfoDiarioController::class, 'enviar']);
    Route::get('/ingresostock', [App\Http\Controllers\Printer\IngresoStockController::class, 'enviar']);
});

#
## PRUEBA DE IMPRESION EN WINDOWS  ##
#
Route::get('/windows/print', [App\Http\Controllers\PrintController::class, 'print']);

Route::get('/ventas/imprimir/muestras', [\App\Http\Controllers\Ventas\Imprimir\ImprimeMuestrasController::class, 'muestras'])
    ->name('ventas.imprimir/muestras');


Route::group(['middleware' => 'auth'], function() {

    //Route::view('inicio', 'dashboard')->name('dashboard');

    Route::get('/inicio', function () {
        session(['sucursal_id' => env('SUCURSAL_ID')]);       // ID de la Sucursal

        return view('dashboard');
    })->name('dashboard');

    #
    ## EMAIL  ##
    #
    Route::get('/email/enviar/{id}', [App\Http\Controllers\Mails\CierreTurnoController::class, 'enviar'])
        ->name('email.enviar');

    #
    ## CUENTAS CORRIENTES  ##
    #
    Route::get('/ctasctes', [App\Http\Controllers\Ctasctes\CtasctesController::class, 'index'])
        ->name('ctasctes.index');
    Route::get('/ctasctes/getdata', [App\Http\Controllers\Ctasctes\CtasctesController::class, 'getData'])
        ->name('ctasctes.getdata');

    #
    ## USUARIOS ##
    #
    Route::get('/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])
        ->name('usuarios');
    Route::get('/usuarios/{usuario}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])
        ->name('usuario.edit');
    Route::get('/usuario/create', [App\Http\Controllers\UsuarioController::class, 'create'])
        ->name('usuario.create');
    Route::post('/usuario', [App\Http\Controllers\UsuarioController::class, 'store'])
        ->name('usuario.store');
    Route::put('/usuario/{usuario}', [App\Http\Controllers\UsuarioController::class, 'update'])
        ->name('usuario.update');
    Route::get('/usuario/changepassw', [App\Http\Controllers\UsuarioController::class, 'changepassw'])
        ->name('usuario.changepassw');
    Route::put('/usuario/putchangepassw/{id}', [App\Http\Controllers\UsuarioController::class, 'putChangePassw'])
        ->name('usuario.putchangepassw');

    #
    ## USER PROFILE 
    #
    Route::view('profile', 'usuarios.profile')->name('profile');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    #
    ## EMPLEADOS
    #
    Route::resource('empleado', \App\Http\Controllers\Empleados\EmpleadoController::class)
        ->except(['destroy']);

    #
    ## PRODUCTOS
    #
    Route::resource('producto', \App\Http\Controllers\Productos\ProductoController::class)
        ->except(['destroy', 'show']);
    Route::get('/producto/filtrado', [\App\Http\Controllers\Productos\ProductoController::class, 'filtrado'])
        ->name('producto.filtrado');
    Route::get('/producto/buscar', \App\Http\Controllers\Productos\BuscarProducto::class)
        ->name('producto.buscar');
    Route::get('/producto/actualizar', [\App\Http\Controllers\Productos\ProductoController::class, 'actualizar'])
        ->name('producto.actualizar');

    #
    ## DETALLE_PRODUCTOS (RECETA)
    #
    Route::get('/producto_receta/elimina/{id}', [\App\Http\Controllers\Productos\ProductosRecetaController::class, 'elimina'])
        ->name('producto_receta.elimina');
    Route::resource('producto_receta', \App\Http\Controllers\Productos\ProductosRecetaController::class);

    #
    ## PRODUCTO_GRUPOS 
    #
    Route::resource('producto_grupo', \App\Http\Controllers\Productos\ProductoGrupoController::class)
        ->except('destroy');

    #
    ## PRODUCTO_TIPOS
    #
    Route::resource('producto_tipo', \App\Http\Controllers\Productos\ProductoTipoController::class)
        ->except('destroy');

    #
    ## PRODUCTOS SUCURSAL
    #
    Route::resource('producto_sucursal', \App\Http\Controllers\Productos\ProductoSucursalController::class);
    Route::get('/producto_sucursal/elimina/{id}', [\App\Http\Controllers\Productos\ProductoSucursalController::class, 'elimina'])
        ->name('producto_sucursal.elimina');

    #
    ## PRODUCTOS PROMOCIONES
    #
    Route::post('/producto/promo/salvar_producto', [\App\Http\Controllers\Productos\ProductoPromoController::class, 'salvarProducto'])
        ->name('producto.promo.salvar_producto');
    Route::post('/producto/promo/salvar_opciones', [\App\Http\Controllers\Productos\ProductoPromoController::class, 'salvarOpciones'])
        ->name('producto.promo.salvar_opciones');

    #
    ## PRODUCTOS ACTUALIZAR LISTA PRECIOS SUCURSAL
    #
    Route::get('/producto/sucursal/actualiza', [\App\Http\Controllers\Productos\ActualizaSucursalController::class, 'index'])
        ->name('producto.sucursal.actualiza');
    Route::get('/producto/sucursal/store', [\App\Http\Controllers\Productos\ActualizaSucursalController::class, 'store'])
        ->name('producto.sucursal.store');

    #
    ## PRODUCTOS LISTA PRECIOS PARA ACTUALIZAR
    #
    Route::get('/producto/listaprecios', [\App\Http\Controllers\Productos\ListaPreciosController::class, 'index'])
        ->name('producto.listaprecios');
    Route::post('/producto/listaprecios/actualiza', [\App\Http\Controllers\Productos\ListaPreciosController::class, 'actualiza'])
        ->name('producto.listaprecios.actualiza');

    #
    ## SUCURSAL
    #
    Route::resource('sucursal', \App\Http\Controllers\Sucursal\SucursalController::class);

    #
    ## PUNTO DE VENTA EN SUCURSAL
    #
    Route::resource('puntos_venta', \App\Http\Controllers\Sucursal\PuntoVentaController::class);

    #
    ## SELECCIONA SUCURSAL (Para admin)
    #
    Route::get('/selecciona/sucursal', [\App\Http\Controllers\Sucursal\SeleccionaSucursalController::class, 'index'])
        ->name('selecciona.sucursal');
    Route::post('/selecciona/sucursal', [\App\Http\Controllers\Sucursal\SeleccionaSucursalController::class, 'update'])
        ->name('selecciona.sucursal');

    #
    ## CAJA MOVIMIENTOS ##
    #
    Route::resource('caja_movimiento', \App\Http\Controllers\Caja\CajaMovimientoController::class)
        ->except('destroy');
    Route::get('/caja_movimiento/anulamovim/{nro}', [\App\Http\Controllers\Caja\CajaMovimientoController::class, 'anulamovim' ])
        ->name('caja_movimiento.anulamovim');
    Route::get('/movimientos_caja/filtrado', [\App\Http\Controllers\Caja\MovimientosCajaFiltrado::class, 'index'])
        ->name('movimientos_caja.filtrado');
    Route::get('/movimientos_caja/cajas_sucursal', \App\Http\Controllers\Caja\CajasSucursal::class)
        ->name('movimientos_caja.cajas_sucursal');
    Route::get('/movimientos_caja/informe', [\App\Http\Controllers\Caja\InformeController::class, 'index'])
        ->name('movimientos_caja.informe');
    Route::get('/movimientos_caja/informe/show', [\App\Http\Controllers\Caja\InformeController::class, 'show'])
        ->name('movimientos_caja.informe.show');
    Route::get('/movimientos_caja/informe/excel', [\App\Http\Controllers\Caja\InformeController::class, 'excel'])
        ->name('movimientos_caja.informe.excel');

    #
    ## CAJA_TIPOS_MOVIM
    #
    Route::resource('caja_tipos_movim', \App\Http\Controllers\Caja\TiposMovimientoController::class)
        ->except('destroy');

    #
    ## TURNOS ##
    #
    Route::resource('turno', \App\Http\Controllers\Turnos\TurnoController::class)
        ->except('destroy');
    Route::get('/turnos/informe', [\App\Http\Controllers\Turnos\InformeController::class, 'index'])
        ->name('turnos.informe');
    Route::get('/turnos/informe/filtrado', [\App\Http\Controllers\Turnos\InfoFiltradoController::class, 'index'])
        ->name('turnos.informe.filtrado');
    Route::get('/turno/buscararqueo', \App\Http\Controllers\Turnos\BuscarArqueoCajaAnt::class)
        ->name('turno.buscararqueo');
    Route::get('/turno/cierraturnocaja', [\App\Http\Controllers\Turnos\CierraTurnoCajaController::class, 'cierra'])
        ->name('turno.cierraturnocaja');
    Route::get('/turno/comprobantesvtas', \App\Http\Controllers\Turnos\ComprobantesVtasController::class)
        ->name('turno.comprobantesvtas');
    Route::get('/abreturno', [\App\Http\Controllers\Turnos\AbreTurnoController::class, 'index'])
        ->name('abreturno');
    Route::get('/noabreturno', [\App\Http\Controllers\Turnos\NoAbreTurnoController::class, 'index'])
        ->name('noabreturno');
    Route::get('/selecptovta', [\App\Http\Controllers\Turnos\SelecPtoVtaController::class, 'index'])
        ->name('selecptovta');

    Route::get('/turno/imprime/cierre', [\App\Http\Controllers\Turnos\ImprimeCierreController::class, 'index'])
        ->name('turno.imprime.cierre');
    Route::get('/turno/imprime/exit', [\App\Http\Controllers\Turnos\ImprimeCierreController::class, 'exit'])
        ->name('turno.imprime.exit');

    #
    ## STOCKS (MOVIMIENTOS)
    #
    Route::resource('comprobante_stock', \App\Http\Controllers\Stock\ComprobanteStockController::class)
        ->except('destroy');
    Route::get('/comprobante_stock/imprime/{id}', \App\Http\Controllers\Stock\ImprimeComprobanteController::class)
        ->name('comprobante_stock.imprime');
    Route::get('/comprobante_stock/anula/{id}', [\App\Http\Controllers\Stock\ComprobanteStockController::class, 'anula'])
        ->name('comprobante_stock.anula');

    #
    ## STOCKS (TIPOS DE MOVIMIENTOS)
    #
    Route::resource('stock_tipos_movim', \App\Http\Controllers\Stock\TiposMovimientoController::class)
        ->except('destroy');


    #
    ## DETALLE PRODUCTOS COMPROBANTE STOCK ##
    #
    Route::resource('detalle_comprobante_stock', \App\Http\Controllers\Stock\DetalleComprobanteStockController::class)
        ->except('destroy');

    #
    ## STOCKS (INFORME DESVIO)
    #
    Route::get('/stock/infodesvio', [\App\Http\Controllers\Stock\InfoDesvioController::class, 'index'])
        ->name('stock.infodesvio');
    Route::get('/stock/infodesvio/data', [\App\Http\Controllers\Stock\InfoDesvioController::class, 'data'])
        ->name('stock.infodesvio.data');

    #
    ## STOCK (INFORME DIARIO)
    #
    Route::get('/stock/informediario', [\App\Http\Controllers\Stock\InformeDiarioController::class, 'index'])
        ->name('stock.informediario');

    #
    ## VENTAS
    #
    Route::get('/ventas/comprobante', [\App\Http\Controllers\Ventas\ComprobanteController::class, 'index'])
        ->name('ventas.comprobante');
    Route::post('/ventas/comprobante', [\App\Http\Controllers\Ventas\ComprobanteController::class, 'store'])
        ->name('ventas.comprobante');
    Route::get('/ventas/comprobante/anular/{id}', [\App\Http\Controllers\Ventas\ComprobanteController::class, 'anular'])
        ->name('ventas.comprobante.anular');
    Route::get('/ventas/comprobante/getnumero', [\App\Http\Controllers\Ventas\ComprobanteController::class, 'getNumero'])
        ->name('ventas.comprobante.getnumero');
    Route::get('/ventas/infoventasprod', [\App\Http\Controllers\Ventas\InfoVentasProdController::class, 'index'])
        ->name('ventas.infoventasprod');
    Route::get('/ventas/ventasprod', [\App\Http\Controllers\Ventas\InfoVentasProdController::class, 'ventasprod'])
        ->name('ventas.ventasprod');
    Route::get('/ventas/infopedido', [\App\Http\Controllers\Ventas\InfoPedidoController::class, 'index'])
        ->name('ventas.infopedido');

    Route::get('/ventas/comprobante/imprimir', [\App\Http\Controllers\Ventas\Imprimir\ImprimirController::class, 'imprimir'])
        ->name('ventas.comprobante.imprimir');
    Route::get('/ventas/comprobante/imprimir_com', [\App\Http\Controllers\Ventas\Imprimir\ImprimirController::class, 'imprimir_com'])
        ->name('ventas.comprobante.imprimir_com');

    #
    ## DETALLE COMPROBANTE VENTAS
    #
    Route::resource('detalle_comprob_venta', \App\Http\Controllers\Ventas\DetalleComprobVentaController::class)
        ->except('destroy');
    Route::get('/ventas/detallecomprobante/{id}', [\App\Http\Controllers\Ventas\DetalleComprobVentaController::class, 'detalle'])
        ->name('ventas.detallecomprobante');
    Route::get('/ventas/detalle_comprob_venta/anular/{id}', [\App\Http\Controllers\Ventas\DetalleComprobVentaController::class, 'anular'])
        ->name('ventas.detallecomprobventa.anular');

    #
    ## DETALLE COMPROBANTE RECETA
    #
    Route::resource('detalle_comprob_receta', \App\Http\Controllers\Ventas\DetalleComprobRecetaController::class)
        ->except('destroy');
    Route::get('/ventas/detalle_comprob_receta/anular/{id}', [\App\Http\Controllers\Ventas\DetalleComprobRecetaController::class, 'anular'])
        ->name('ventas.detallecomprobreceta.anular');

    #
    ## MIX DE VENTAS
    #
    Route::get('/ventas/mix', [\App\Http\Controllers\Ventas\MixController::class, 'index'])
        ->name('ventas.mix');
    Route::get('/ventas/mix/data', [\App\Http\Controllers\Ventas\MixController::class, 'data'])
        ->name('ventas.mix.data');

    #
    ## INFORME MOVIMIENTOS STOCK
    #
    Route::get('/stock/infomovimientos', [\App\Http\Controllers\Stock\InfoMovimientosController::class, 'index'])
        ->name('stock.infomovimientos');
    Route::get('/stock/infomovimientos/getdetalle/{id}', [\App\Http\Controllers\Stock\InfoMovimientosController::class, 'getDetalle'])
        ->name('stock.infomovimientos.getdetalle');


    #
    ## PLANILLA STOCK REAL
    #
    Route::get('/stock/real/planilla', [\App\Http\Controllers\Stock\RealPlanillaController::class, 'index'])
        ->name('stock.real.planilla');
    Route::get('/stock/real/planilla/create', [\App\Http\Controllers\Stock\RealPlanillaController::class, 'create'])
        ->name('stock.real.planilla.create');
    Route::post('/stock/real/planilla/store', [\App\Http\Controllers\Stock\RealPlanillaController::class, 'store'])
        ->name('stock.real.planilla.store');
    Route::post('/stock/real/planilla/store_articulos', [\App\Http\Controllers\Stock\RealPlanillaController::class, 'storeArticulos'])
        ->name('stock.real.planilla.store_articulos');
    Route::post('/stock/real/planilla/store_helados', [\App\Http\Controllers\Stock\RealPlanillaController::class, 'storeHelados'])
        ->name('stock.real.planilla.store_helados');

    Route::get('/stock/real/planilla/edit/{id}', [\App\Http\Controllers\Stock\RealPlanillaEditController::class, 'edit'])
        ->name('stock.real.planilla.edit');
    Route::post('/stock/real/planilla/update/{id}', [\App\Http\Controllers\Stock\RealPlanillaEditController::class, 'update'])
        ->name('stock.real.planilla.update');
    Route::post('/stock/real/planilla/updateArticulos/{id}', [\App\Http\Controllers\Stock\RealPlanillaEditController::class, 'updateArticulos'])
        ->name('stock.real.planilla.updateArticulos');
    Route::post('/stock/real/planilla/updateHelados/{id}', [\App\Http\Controllers\Stock\RealPlanillaEditController::class, 'updateHelados'])
        ->name('stock.real.planilla.updateHelados');

    #
    ## FIRMAS ##
    #
    Route::get('/firma/getcliente', [\App\Http\Controllers\Firmas\FirmaController::class, 'getCliente'])
        ->name('firma.getcliente');
    Route::resource('firma', \App\Http\Controllers\Firmas\FirmaController::class)
        ->except('destroy');
    Route::get('/firma/filtrado', [\App\Http\Controllers\Firmas\FirmaController::class, 'filtrado'])
        ->name('firma.filtrado');

    #
    ## PARAMETROS GENERALES ##
    #
    Route::get('/parametros', [\App\Http\Controllers\Parametros\ParametroController::class, 'index'])
        ->name('parametros');
    Route::post('/parametros/store_impresora', [\App\Http\Controllers\Parametros\ParametroController::class, 'store_impresora'])
        ->name('parametros.store_impresora');
    Route::post('/parametros/store_peso_env', [\App\Http\Controllers\Parametros\ParametroController::class, 'store_peso_env'])
        ->name('parametros.store_peso_env');
    Route::post('/parametros/eliminar_impres', [\App\Http\Controllers\Parametros\ParametroController::class, 'eliminar_impres'])
        ->name('parametros.eliminar_impres');
    Route::post('/parametros/store_email', [\App\Http\Controllers\Parametros\ParametroController::class, 'store_email'])
        ->name('parametros.store_email');

});  // End routes with middleware Auth

require __DIR__.'/auth.php';
