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

    <div class="flex mt-3 mb-2">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Nuevo producto
            </div>
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <form method="POST"
                action="{{ route('producto.store') }}" 
                enctype="multipart/form-data" 
                autocomplete="off">
                @csrf
                <!-- Para saber que quiere cargar una receta al nuevo producto -->
                <input type="hidden" name="carga_receta" id="carga_receta" value="no">
                <input type="hidden" name="con_promocion" id="con_promocion" value="no">
                <input type="hidden" name="ultima_actualizacion" id="ultima_actualizacion" value="{{ $fechaActual }}">
                <input type="hidden" name="estado" id="estado" value="1">
                <input type="hidden" name="costo_siniva" id="costo_siniva" value="0">
                <input type="hidden" name="tasa_iva" id="tasa_iva" value="0">
                <input type="hidden" name="costo_x_unidad_sin_iva" id="costo_x_unidad_sin_iva" value="0">
                <input type="hidden" name="costo_x_bulto_sin_iva" id="costo_x_bulto_sin_iva" value="0">
                <input type="hidden" name="articulo_indiv_id" id="articulo_indiv_id" value="0">
                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-2 gap-x-2 gap-y-0 px-6 pb-1">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="codigo" :value="__('Código')"/>
                        <x-input id="codigo"
                                name="codigo"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $codDisponible }}" 
                                readonly />
                    </div>
                    <div>   <!-- Row 1 Col 2 -->
                        <x-label for="tipo_producto_id" :value="__('Tipo')" />
                        <x-select id="tipo_producto_id" name="tipo_producto_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=0>Seleccione tipo...</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">
                                        {{ $tipo->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        @error('tipo_producto_id')
                            <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('tipo_producto_id') }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 3-4 -->
                        <x-label for="grupo_id" :value="__('Grupo')" />
                        <x-select id="grupo_id" name="grupo_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=0 selected>Seleccione grupo...</option>
                                @foreach ($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">
                                        {{ $grupo->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>     <!-- Row 1 Col 5 -->
                        {{-- <x-label for="ultima_actualizacion" :value="__('Última actualización')" />
                        <x-input type="text" 
                                    name="ultima_actualizacion" 
                                    id="ultima_actualizacion" 
                                    class="block mt-0 w-3/4 h-9 text-right bg-green-100 text-gray-700"
                                    value="{{ $fechaActual }}"
                                    readonly/> --}}
                    </div>
                    <div class="col-span-2">    <!-- Row 2 Col 1-2 -->
                        <x-label for="descripcion" :value="__('Descripción')" />
                        <x-input type="text"
                                    name="descripcion"
                                    id="descripcion"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9"
                                    :value="old('descripcion')" />
                        @error('descripcion')
                             <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('descripcion') }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 3-4 -->
                        <x-label for="descripcion_ticket" :value="__('Descripción Ticket')" />
                        <x-input type="text"
                                 name="descripcion_ticket"
                                 id="descripcion_ticket"
                                 autocomplete="off"
                                 tabindex="-1"
                                 maxlength="20"
                                 class="block mt-0 w-full h-9"
                                 :value="old('descripcion_ticket')" />
                    </div>
                    <div>   <!-- Row 2 Col 5 -->
                        {{-- <x-label for="estado" :value="__('Estado')" />
                        <select name="estado" id="estado"
                            class='mt-0 pt-1 w-full h-8 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'>
                            <option value="1">Activo</option>
                            <option value="0">Dado de baja</option>
                        </select> --}}
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
                            :value="old('venta_publico', 0)" />
                        <x-label for="venta_publico" :value="__('Venta al Público')" />
                    </div>
                    <div class="flex">
                        <input type="checkbox"
                            name="apto_receta"
                            id="apto_receta"
                            class="block ml-4 mr-1"
                            :value="old('apto_receta', 0)" />
                        <x-label for="apto_receta" :value="__('Apto p/receta')" />
                    </div>
                    <div class="flex">
                        <input type="checkbox" 
                            name="mide_desvio" 
                            id="mide_desvio" 
                            class="block ml-4 mr-1"/>
                        <x-label for="mide_desvio" :value="__('Medir desvío')" />
                    </div>
                    <div class="flex">
                        <input type="checkbox"
                            name="generico"
                            id="generico"
                            class="block ml-4 mr-1"
                            :value="old('generico', 0)" />
                        <x-label for="generico" :value="__('Genérico')" />
                    </div>
                </div>
                <div class="grid overflow-auto grid-cols-6 grid-rows-1 gap-2">
                    <!-- IMAGEN -->
                    <div class="col-span-3 px-6 py-1">
                        <div id="divImagen" class="hidden" disabled>
                            <div class="flex items-end">
                                <div class="shadow-md">
                                    <img src="" 
                                            id="previewImagen" 
                                            alt="imagen" 
                                            class="class="object-contain"
                                            width="180" height="120">
                                </div>
                                <button id="btnBorrarImg" type="button"
                                    class="h-8 ml-2 px-4 py-2 bg-red-800 border border-transparent rounded font-semibold text-xs text-white tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Borrar
                                </button>
                            </div>
                        </div>
                        <div id="divInputImagen" class="mt-2">
                            <x-label for="imagen" :value="__('Cargar imagen')" />
                            <x-input type="file" 
                                        name="imagen" 
                                        id="imagen" 
                                        class="block"/>
                            @error('imagen')
                                <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('imagen') }}</p>
                            @enderror
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
                                :value="old('peso_materia_prima')" />       
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
                                <a href="#tabs" 
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
            </form>     <!-- Final form -->
        </div>
    </div>

    @push('scripts')
        <script>
            // Variables globales
            var _producto_id = 0,
                _productos = @json($productos),
                _producto_sucurs = [],    // Acá va los datos del tab sucursales
                _sucursales = @json($sucursales);
        </script>
        <script src="{{ asset('js/productos/create.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>
