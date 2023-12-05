<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Models\Firma;
use App\Models\Producto;
use App\Models\ProductoGrupo;
use App\Models\ProductoTipo;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateProductoRequest;
use App\Models\ProductoPromo;
use App\Models\ProductoPromoOpciones;
use App\Models\ProductosReceta;
use App\Models\ProductoSucursal;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Productos\ListasParaProducto;


class ProductoController extends Controller
{
    protected $tasasIva = [0, 2.5, 5, 10.5, 13, 21, 27];
    protected $_cant_por_pagina = 15;

    /**
     * Display a listing of the resource.
     * Name: producto
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filtroBuscar = false;
        $proveedores = ListasParaProducto::listaProveedores();
        $tipos = ProductoTipo::orderBy('descripcion')->get();
        $grupos = ProductoGrupo::get();
        $filtros = [];
        $productos = Producto::orderBy('descripcion')->paginate($this->_cant_por_pagina);

        $data = compact(
            'productos', 
            'filtroBuscar', 
            'proveedores', 
            'filtros',
            'grupos',
            'tipos',
        );

        return view('productos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * Name: producto.create
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fechaActual = date('d/m/Y');
        $proveedores = Firma::select('id', 'firma')
                            ->where('proveedor', 1)
                            ->get();
        $tasasIva    = $this->tasasIva;    //  Solo disponible para Administradores
        $ivaDefault  = 21;
        $grupos      = ProductoGrupo::get();
        $tipos       = ProductoTipo::orderBy('descripcion')->get();
        $productos   = ListasParaProducto::listaProductoIndividual(); // Para lista de producto individual
        $producto    = Producto::latest()->first();

        // AQUI EL codigo disponible es el ID. Que despues arma el codigo !!
        // $codDisponible = $producto->id + 1;
        $codDisponible = $this->_generarCodigo($producto->id + 1);

        $sucursales = Sucursal::select('id', 'nombre', 'direccion', 'localidad')
            ->orderBy('nombre')
            ->get();
        $isEdit = false;

        $data = compact(
            'fechaActual', 
            'proveedores',
            'tasasIva',
            'ivaDefault',
            'productos', 
            'sucursales',
            'codDisponible',
            'grupos',
            'tipos',
            'isEdit',
        );

        return view('productos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * Name: producto.store (POST)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request)
    public function store(ValidateProductoRequest $request)
    {
        $request->validated();
        $productoSano = $this->_sanitizarProducto($request);
        $producto = Producto::create($productoSano);

        if ($request->input('carga_receta') === 'si') {
            return redirect(route('producto.edit', $producto->id))->with('status', 'Nuevo producto creado !');
        }

        return redirect(route('producto.index'))->with('status', 'Nuevo producto creado !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        session(['paginaIndexProductos' => $_SERVER['HTTP_REFERER']]);
        $proveedores = ListasParaProducto::listaProveedores();
        $fechaActual = date('d/m/Y');
        $tasasIva = $this->tasasIva;
        $receta = ProductosReceta::where('producto_id', $producto->codigo)
            ->orderBy('descripcion')
            ->get();
        $productos = ListasParaProducto::listaProductoIndividual();  // Para lista de producto individual
        $productosReceta = ListasParaProducto::listaProductosReceta();
        $productoSucursales = ProductoSucursal::select('id', 'sucursal_id', 'producto_id')
            ->where('producto_id', $producto->codigo)
            ->get()
            ->toArray();
        $grupos = ProductoGrupo::get();     // Al seleccionar un grupo se llena combo tipos
        $tipos = ProductoTipo::get();
        $articulosPromo = ListasParaProducto::listaArticulosPromo();    // Lista articulos para generar promocion

        $promocionProducto = $this->_buscarPromo($producto->codigo);  // Buscar promocion del producto
        $promocionOpciones = $this->_buscarOpcionesPromo($producto->codigo);    // Buscar opciones del al promocion

        $sucursales = Sucursal::select('id', 'nombre', 'direccion', 'localidad')
            ->orderBy('nombre')
            ->get();
        $isEdit = true;

        $data = compact(
            'producto', 
            'proveedores', 
            'tasasIva', 
            'fechaActual', 
            'productos', 
            'productosReceta',
            'articulosPromo',
            'promocionProducto',
            'promocionOpciones',
            'productoSucursales',
            'sucursales',
            'receta', 
            'grupos',
            'tipos',
            'isEdit'
        );
        return view('productos.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateProductoRequest $request, Producto $producto)
    {
        $request->validated();
        $productoSano = $this->_sanitizarProducto($request);

        $producto->where('id', $request->input('id'))
                 ->lockForUpdate()
                 ->update($productoSano);

        $rutaIndex = session('paginaIndexProductos');

        //return redirect(route('producto.index'))->with('status', 'Producto actualizado !');
        return redirect($rutaIndex)->with('status', 'Producto actualizado !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }

   /**
     * Actualiza el campo 'con_receta'     // YA NO LO ESTOY USANDO !!
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        $respuesta = ['estado' => 'ok'];
        $status = 200;

        $prod = Producto::where('id', $request->input('id'))
            ->update(['con_receta' => $request->input('con_receta')]);

        return response()->json($respuesta, $status);
    }

    /**
     * Lista de productos filtrados
     * Name: producto.filtrado
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function filtrado(Request $request)
    {
        $filtroBuscar = $request->input('buscar');
        $proveedores = ListasParaProducto::listaProveedores();
        $filtros = $this->_setFiltros($request);

        $productos = Producto::where($filtros)
            ->orderBy('descripcion')
            ->paginate($this->_cant_por_pagina);
        $tipos = ProductoTipo::orderBy('descripcion')
            ->get();
        $grupos = ProductoGrupo::get();        

        $data = compact(
            'productos', 
            'filtroBuscar', 
            'proveedores', 
            'filtros',
            'grupos',
            'tipos',
        );

        return view('productos.index', $data);
    }

    /** 
     * Generar el codigo de producto
     * 
     */
    private function _generarCodigo(int $codigo = 1)
    {
        if (Auth::user()->perfil_id === 1) {    // Administrador de sistema
            $genCod = "01-" . $codigo;
        } else {        // Administrador de franquisia
            $genCod = "02-" . $codigo;
        }

        return $genCod;
    }

    /**
     * Convierte fecha de input a fecha mysql
     *
     * @return string $fecha
     */
    protected function _convertirFecha(string $fechaInput)
    {
        $arrTemp = explode('/', $fechaInput);

        return $arrTemp[2] . "-" . $arrTemp[1] . "-" . $arrTemp[0];
    }

    /** 
     * Sanatiza los datos de Request y otras conversiones
     * 
     * @param Request $req
     * @return array $producto
     */
    protected function _sanitizarProducto(Request $req)
    {
        if ($req->has('imagen')) {
            $nombreImagen = $req->imagen->getClientOriginalName();
            $req->imagen->move(public_path('imagenes'), $nombreImagen);
        } else {
            if ($req->has('nombre_imagen')) {
                $nombreImagen = $req->input('nombre_imagen');
            } else {
                $nombreImagen = "";
            }
        }

        $producto = [
            'codigo' => $req->input('codigo'),
            'proveedor_id' => $req->input('proveedor_id'),
            'tipo_producto_id' => $req->input('tipo_producto_id'),
            'grupo_id' => $req->input('grupo_id'),
            'descripcion' => $req->input('descripcion'),
            'descripcion_ticket' => $req->input('descripcion_ticket'),
            'generico' => $req->boolean('generico'),
            'venta_publico' => $req->boolean('venta_publico'),
            'mide_desvio' => $req->boolean('mide_desvio'),
            'apto_receta' => $req->boolean('apto_receta'),  // Si es grupo 2, tipo 2 
            'con_receta' => $req->boolean('con_receta'),
            'tasa_iva' => $req->input('tasa_iva'),
            'costo_x_unidad_sin_iva' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('costo_x_unidad_sin_iva')),
            'costo_x_unidad' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('costo_x_unidad')),
            'costo_x_bulto_sin_iva' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('costo_x_bulto_sin_iva')),
            'costo_x_bulto' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('costo_x_bulto')),
            'precio_lista_1' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('precio_lista_1')),
            'precio_lista_2' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('precio_lista_2')),
            'precio_lista_3' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('precio_lista_3')),
            'unidades_x_caja' => (integer) preg_replace(['/\./', '/,/'],['', '.'], $req->input('unidades_x_caja')),
            'cajas_x_bulto' => (integer) preg_replace(['/\./', '/,/'],['', '.'], $req->input('cajas_x_bulto')),
            'peso_materia_prima' => (float) preg_replace(['/\./', '/,/'],['', '.'], $req->input('peso_materia_prima')),
            'ultima_actualizacion' => date('Y-m-d'),
            'unidades_x_bulto' => (integer) preg_replace(['/\./', '/,/'],['', '.'], $req->input('unidades_x_bulto')),
            'articulo_indiv_id' => $req->input('articulo_indiv_id'),
            'imagen' => $nombreImagen,
            'estado' => $req->input('estado'),
        ];

        if (!empty($producto['articulo_indiv_id'])) {    // Actualizo precio de costo del articulo individial, si existe
            $productoInd = Producto::where('codigo', $producto['articulo_indiv_id'])
                ->first();
            $productoInd->costo_x_unidad = $producto['costo_x_unidad'];
            $productoInd->save();
        }

        if ($producto['grupo_id'] == 7 || $producto['grupo_id'] == 8) {     // Producto individual NO puede tener estos campos
            $producto['unidades_x_caja'] = 0;
            $producto['cajas_x_bulto'] = 0;
            $producto['unidades_x_bulto'] = 0;
            $producto['articulo_indiv_id' ] = '';
        }

        return $producto;
    }

    /**
     * Devuelve array con filtros seteados (incluye busqueda de descripcion)
     * 
     * @param Request $req
     * @return Array $filtros
     */
    private function _setFiltros(Request $req)
    {
        $filtros = [];

        if ($req->input('buscar') != null || $req->input('buscar') != '') {
            $likeDesc = $req->input('buscar') . "%";
            array_push($filtros, ['descripcion', 'like', $likeDesc]);
        }

        if ($req->has('prov')) {
            if ($req->input('prov') !== '0') {
                array_push($filtros, ['proveedor_id', '=', $req->input('prov')]);
            }
        }

        if ($req->has('tipo')) {
            if ($req->input('tipo') !== '0') {
                array_push($filtros, ['tipo_producto_id', '=', $req->input('tipo')]);
            }
        }

        if ($req->has('grupo')) {
            if ($req->input('grupo') !== '0') {
                array_push($filtros, ['grupo_id', '=', $req->input('grupo')]);
            }
        }

        return $filtros;
    }

    private function _buscarPromo($codigo)
    {
        $promo = ProductoPromo::where('producto_codigo', $codigo)
                    ->where('estado', 1)
                    ->get()
                    ->toArray();

        if ($promo) {
            $promo = $promo[0];
            array_pop($promo);  //  Elimina created_at
            array_pop($promo);  //  Elimina updated_at

            return $promo;
        } else {

            return false;
        }
    }

    function _buscarOpcionesPromo(string $codigo)
    {
        $opciones = ProductoPromoOpciones::where('producto_codigo', $codigo)
                    ->where('estado', 1)
                    ->get()
                    ->toArray();

        if ($opciones) {
            foreach ($opciones as &$valor) {
                $valor['cantidad'] = (float) $valor['cantidad'];
                $valor['costo'] = (float) $valor['costo'];
                $valor['precio'] = (float) $valor['precio'];
                $valor['subtotal'] = (float) $valor['subtotal'];
                array_pop($valor);  //  Elimina created_at
                array_pop($valor);  //  Elimina updated_at
            }

            return $opciones;
        } else {

            return false;
        }
    }
}
