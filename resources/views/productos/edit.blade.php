<x-app-layout>

    <x-slot name="title">Heladerías - Producto</x-slot>

    @push('otherCss')
        <style>
            .isDisabled {
                cursor: not-allowed;
                opacity: 0.5;
            }
            a[aria-disabled="true"] {
                color: currentColor;
                display: inline-block;  /* For IE11/ MS Edge bug */
                pointer-events: none;
                text-decoration: none;
            }
        </style>
    @endpush

    <div class="flex mt-3 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Producto
            </div>
            @if (session('status'))
                <x-alert-success titulo="Perfecto !" >
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <form method="POST" 
                  action="{{ route('producto.update', $producto->id) }}" 
                  enctype="multipart/form-data" 
                  autocomplete="off">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $producto->id }}">
                <input type="hidden" name="con_receta" id="con_receta" value="{{ $producto->con_receta }}">
                <input type="hidden"  name="nombre_imagen" id="nombre_imagen" value="{{ $producto->imagen }}">
                <input type="hidden" name="tasa_iva" id="tasa_iva" value="{{ $producto->tasa_iva }}">
                <input type="hidden" name="costo_siniva" id="costo_siniva" value="0">
                <input type="hidden" name="costo_x_unidad_sin_iva" id="costo_x_unidad_sin_iva" value="{{ $producto->costo_x_unidad_sin_iva }}">
                <input type="hidden" name="costo_x_bulto_sin_iva" id="costo_x_bulto_sin_iva" value="{{ $producto->costo_x_bulto_sin_iva }}">
                <input type="hidden" name="articulo_indiv_id" id="articulo_indiv_id" value="{{ $producto->articulo_indiv_id }}">
                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-2 gap-x-2 gap-y-0 px-6 pb-1">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="codigo" :value="__('Código')"/>
                        <x-input id="codigo"
                                name="codigo"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $producto->codigo }}" 
                                readonly />
                    </div>
                    <div>   <!-- Row 1 Col 2 -->
                        <x-label for="tipo_producto_id" :value="__('Tipo')" />
                        <x-select id="tipo_producto_id" name="tipo_producto_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option hidden selected value=0>Seleccione tipo...</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}"
                                        @if ($tipo->id == $producto->tipo_producto_id )
                                            selected 
                                        @endif>
                                        {{ $tipo->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 3-4 -->
                        <x-label for="grupo_id" :value="__('Grupo')" />
                        <x-select id="grupo_id" name="grupo_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=0 hidden selected>Seleccione grupo...</option>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo->id }}"
                                        {{ ($grupo->id == $producto->grupo_id) ? ' selected' : '' }}>
                                        {{ $grupo->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>     <!-- Row 1 Col 5 -->
                        <x-label for="ultima_actualizacion" :value="__('Última actualización')" />
                        <x-input type="text" 
                                    name="ultima_actualizacion" 
                                    id="ultima_actualizacion" 
                                    class="block mt-0 w-full h-9 text-right bg-green-100 text-gray-700"
                                    readonly/>
                    </div>
                    <div class="col-span-2">    <!-- Row 2 Col 1-2 -->
                        <x-label for="descripcion" :value="__('Descripción')" />
                        <x-input type="text"
                                    name="descripcion"
                                    id="descripcion"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9"
                                    value="{{ $producto->descripcion }}" />
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 3-4 -->
                        <x-label for="descripcion_ticket" :value="__('Descripción Ticket')" />
                        <x-input type="text"
                                 name="descripcion_ticket"
                                 id="descripcion_ticket"
                                 autocomplete="off"
                                 tabindex="-1"
                                 class="block mt-0 w-full h-9"
                                 value="{{ $producto->descripcion_ticket }}" />
                    </div>
                    <div>   <!-- Row 1 Col 5 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <select name="estado" id="estado"
                            class='mt-0 pt-1 w-full h-8 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'>
                            <option value="1"
                                {{ ($producto->estado == 1) ? ' selected' : '' }}>
                                Activo
                            </option>
                            <option value="0"
                                {{ ($producto->estado == 0) ? ' selected' : '' }}>
                                Dado de baja
                            </option>
                        </select>
                    </div>
                </div> <!-- fin GRID 1 -->

                <div class="pl-8 pt-3 text-gray-700 text-xl font-bold">
                    Datos generales
                </div>
                <div>
                    <hr class="mb-3 mt-1 mx-4 border-gray-400">
                </div>
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-1 px-1 py-1">
                    <div class="flex">
                        <input type="checkbox"
                            name="venta_publico"
                            id="venta_publico"
                            class="block ml-6 mr-1"
                            {{ ($producto->venta_publico == 1) ? ' checked' : '' }} />
                        <x-label for="venta_publico" :value="__('Venta al Público')" />
                    </div>
                    <div class="flex">
                        <input type="checkbox"
                            name="apto_receta"
                            id="apto_receta"
                            class="block ml-4 mr-1"
                            {{ ($producto->apto_receta == 1) ? ' checked' : '' }} />
                        <x-label for="apto_receta" :value="__('Apto p/receta')" />
                    </div>
                    <div class="flex">   <!-- Row 1 / Col 6 -->
                        <input type="checkbox" 
                            name="mide_desvio" 
                            id="mide_desvio" 
                            class="block ml-4 mr-1"
                        {{ ($producto->mide_desvio == 1) ? ' checked' : '' }}/>
                        <x-label for="mide_desvio" :value="__('Medir desvío')" />
                    </div>
                    <div class="flex">
                        <input type="checkbox"
                            name="generico"
                            id="generico"
                            class="block ml-4 mr-1"
                            {{ ($producto->generico == 1) ? ' checked' : '' }} />
                        <x-label for="generico" :value="__('Genérico')" />
                    </div>
                </div>
                <div class="grid overflow-auto grid-cols-6 grid-rows-1 gap-2">
                    <!-- IMAGEN -->
                    <div class="col-span-3 px-6 py-1">
                        @if (! $producto->imagen)
                            <div id="divImagen" class="hidden">
                        @else
                            <div id="divImagen">
                        @endif
                            <div class="flex items-end">
                                <div class="shadow-md">
                                    @if (! $producto->imagen)
                                        <img src="" 
                                    @else
                                        <img src="{{ asset('imagenes/' . $producto->imagen) }}" 
                                    @endif
                                            id="previewImagen" 
                                            alt="imagen" 
                                            class="class="object-contain"
                                            width="180" height="120">
                                </div>
                                <button id="btnBorrarImg"
                                    class="h-8 ml-2 px-4 py-2 bg-red-800 border border-transparent rounded font-semibold text-xs text-white tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Borrar
                                </button>
                            </div>
                        </div>
                        @if (! $producto->imagen)
                            <div id="divInputImagen" class="mt-2">
                        @else
                            <div id="divInputImagen" class="mt-2 hidden">
                        @endif
                            <x-label for="imagen" :value="__('Cargar imagen')" />
                            <x-input type="file" 
                                    name="imagen" 
                                    id="imagen" 
                                    class="block"/>
                        </div>
                    </div>  <!-- / fin IMAGEN -->
                    <div></div>
                    <div class="mt-3">    <!-- Row 1 / Col 4 -->
                        <x-label for="peso_materia_prima" :value="__('Peso Mat. Prima (Kgs.)')" />
                        <div class="flex">
                            <x-input type="text" 
                                name="peso_materia_prima" 
                                id="peso_materia_prima" 
                                class="mt-0 w-full h-9 text-right" 
                                value="{{ $producto->peso_materia_prima }}" />       
                        </div>
                    </div>
                </div>
                <!-- TABS -->
                <div x-data="{ 
                        activeTab:0,
                        activeClass:   'tabActivo',
                        inactiveClass: 'tabInactivo'
                    }" id="tabs">
                    <div>
                        <ul class="flex mt-4 mb-1 mx-6">
                            <li>
                                <a href="#tabs" id="tabDatosCompra"
                                    x-on:click="activeTab = 0" 
                                    :class="activeTab === 0 ? activeClass : inactiveClass">Compra</a>
                            </li>
                            <li>
                                <a href="#tabs" id="tabDatosVenta"
                                    x-on:click="activeTab = 1" 
                                    :class="activeTab === 1 ? activeClass : inactiveClass">Venta</a>
                            </li>
                            <li> 
                                <a href="#tabs" id="tabReceta"
                                    x-on:click="activeTab = 2" 
                                    :class="activeTab === 2 ? activeClass : inactiveClass">Receta</a> 
                            </li>
                            <li> 
                                <a href="#tabs" id="tabPromo"
                                    x-on:click="activeTab = 3" 
                                    :class="activeTab === 3 ? activeClass : inactiveClass">Promociones</a> 
                            </li>
                            <li> 
                                <a href="#tabs" id="tabSucursales"
                                    x-on:click="activeTab = 4" 
                                    :class="activeTab === 4 ? activeClass : inactiveClass">Sucursales</a> 
                            </li>
                        </ul>
                    </div>
                    <div class="min-w-screen mx-6 bg-gray-100 border border-gray-400 p-3">
                        <div x-show="activeTab === 0">
                            @include('productos.tab-compra')
                        </div>
                        <div x-show="activeTab === 1">
                            @include('productos.tab-venta')
                        </div>
                        <div x-show="activeTab === 2">
                            @include('productos.tab-receta')
                        </div>
                        <div x-show="activeTab === 3">
                            @include('productos.tab-promo')
                        </div>
                        <div x-show="activeTab === 4">
                            @include('productos.tab-sucursales')
                        </div>
                    </div>
                </div>
                <!-- Final TABS  -->
                {{-- <div>
                    <hr class="my-6 mx-4 border-gray-400">
                </div> --}}
                <div class="flex justify-between mt-2">
                    <div>
                        <div class="hidden" id="spinGuardando">
                            <div class="flex ml-10">
                                <div class="w-48 ml-6">
                                    <p>Guardando datos...</p>
                                </div>
                                <div class="w-32 mx-auto">
                                    <div style="border-top-color:transparent"
                                        class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-2 mb-6 mr-6">
                        <div>
                            <x-button class="mr-4" id="btnConfirma">
                                Confirma
                            </x-button>
                            <x-link-salir href="{{ route('producto.index') }}">
                                Salir                    
                            </x-link-salir>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('productos.modal-receta')
    @include('productos.modal-promocion')

    @push('scripts')
        <script>
            // Variables globales
            var _status_de_create = false;
            @if (session('status'))
                _status_de_create = '{{ session('status') }}';
            @endif
            var _producto_id = parseInt("{{ $producto->id }}");
            var _producto_codigo = "{{ $producto->codigo }}";
            var _producto = @json($producto);
            var _productos = @json($productos);
            var _productosReceta = @json($receta);  // Receta del producto (lista productos de la receta)
            var _productos_receta = @json($productosReceta);    // Lista de productos para receta
            var _articulos_promo = @json($articulosPromo);
            var _promocion_producto = @json($promocionProducto);    // pasar a _PROMO._producto_promocion
            var _promocion_opciones = @json($promocionOpciones);
            var _inputPrecioPromo = {};
            var _inputModalCosto = {};
            var _inputModalCantidad = {};
            var _inputModalPromoCantidad = {};
            var _inputModalPromoPrecio = {};
            var _editaProducto = false;
            var _editaArtPromo = false;
            var _idProdEditado = 0;
            var _idArtEditado = 0;
            var _producto_sucurs = @json($productoSucursales);    // Acá van los datos del tab sucursales
            var _sucursales = @json($sucursales);
            var _pathSalvarSucursal = "{{ route('producto_sucursal.store') }}";
            var _pathBorrarSucursal = "{{ route('producto_sucursal.elimina', 0) }}";

            var _PROMO = {
                _pathSalvarProducto: "{{ route('producto.promo.salvar_producto') }}",
                _pathSalvarArtOpc: "{{ route('producto.promo.salvar_opciones') }}",
                _producto_promocion: {
                    producto_codigo: '',
                    desde_fecha_hora: '',
                    hasta_fecha_hora: '',
                    dias_semana: '',
                    costo: 0, 
                    precio_vta: 0,
                    estado: 1,
                },
                _modeloArtOpcion: {
                    id: 0,
                    producto_codigo: '',
                    nro_combo: 0,
                    producto_codigo_opcion: '',
                    descripcion: '',
                    cantidad: 0,
                    costo: 0,
                    precio: 0,
                    subtotal: 0,
                    estado: 1,
                },
                _opciones: [],
                _inputPrecioPromo: undefined,
                _inputModalPromoPrecio: undefined,
                _inputModalPromoCantidad: undefined,
                _inputsHoras: undefined,
            };

        </script>
        <script src="{{ asset('js/productos/edit.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>
