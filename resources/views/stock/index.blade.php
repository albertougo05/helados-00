<x-app-layout>
    <x-slot name="title">Heladería - Mov. Stock</x-slot>

    <div class="flex my-3 mx-0">
        <div class="mx-4 md:mx-8 w-full bg-gray-100 overflow-y-auto shadow-lg rounded-lg opacity-85">
            @include('stock.titulo-sucursal')
            <div>
                <hr class="mt-2 mb-2 mx-4 border-gray-500">
            </div>
            {{-- GRILLA 2 COL X 1 ROW --}}
            <div class="grid overflow-hidden grid-cols-2 grid-rows-1 gap-1 w-full">
                {{-- COL 1 --}}
                <div>
                    <!-- row select tipo movimiento -->
                    <div class="mx-5 mt-1">
                        <x-label for="selTipoMovim" :value="__('Tipo de movimiento')" class="block" />
                        <x-select id="selTipoMovim" class="w-full h-9">
                            <x-slot name="options">
                                <option class="bg-gray-300" value="0">
                                    Seleccionar...
                                </option>
                                @foreach ($tipos_movim as $tipo)
                                    <option class="bg-gray-300" value="{{ $tipo->id }}">
                                        {{ $tipo->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <!-- row grupos y productos -->
                    <div class="flex flex-row mx-4 mt-4">
                        <aside class="sidebar w-40 overflow-auto flex-shrink mx-1 mb-3 shadow-md rounded-md bg-gray-50"
                                style="height: 41vh;">
                            <ul class="p-1">
                                @foreach ($grupos as $grupo)
                                    {{-- @continue($grupo->id == 10 || $grupo->id == 11) --}}
                                    <li class="relative">
                                        <button type="button"
                                            id="ID_{{ $grupo->id }}"
                                            data-unid="{{ $grupo->unidad }}"
                                            data-caja="{{ $grupo->caja }}"
                                            class="btnGrupos w-full px-2 py-0.5 mb-0.5 text-sm text-left text-gray-800 font-medium rounded focus:text-gray-200 hover:bg-gray-300 focus:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-50 transition duration-300 ease-in-out">
                                            {{ $grupo->descripcion }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>
                        <main class="main flex flex-col flex-grow" style="height: 41vh;">
                            <div class="mt-0 mb-0 mx-1 shadow-md overflow-auto rounded-md bg-gray-50">
                                <table class="tableFixHead min-w-full divide-y divide-gray-200" 
                                    id="tablaProductos">
                                    <thead class="bg-indigo-800">
                                        <tr class="text-sm font-semibold text-gray-100">
                                            <th scope="col" 
                                                class="px-1 py-2 text-center tracking-widest">
                                                Descripción
                                            </th>
                                            {{-- <th scope="col" class="pr-3 py-2 text-right">
                                                Precio
                                            </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody 
                                        id="bodyProductos"
                                        class="bg-gray-50 divide-y divide-gray-200">
                                    </tbody>
                                </table>
                            </div>
                        </main>
                    </div>
                    <hr class="mb-3 mt-1 mx-4 border-gray-500">
                    <div class="grid overflow-scroll grid-cols-2 grid-rows-1 gap-x-4 w-full">
                        <div class="ml-6">   <!-- Col 1 / Row 1 -->
                            <x-label for="cantidad" :value="__('Cantidad')"/>
                            <x-input id="cantidad"
                                        class="text-right w-full"
                                        type="text"/>
                        </div>
                        <div class="mr-6">  <!-- Col 1 / Row 2 -->
                            <x-label for="unid_medida" :value="__('Unidad medida')"/>
                            <x-select-xs id="unid_medida" class="mt-0 mb-1 w-full">
                                <x-slot name="options">
                                    <option value="caja">Cajas/Latas</option>
                                    <option value="gramos">Kilos</option>
                                </x-slot>
                            </x-select-xs>
                        </div>
                        <div class="ml-6">   <!-- Col 2 / Row 1 -->
                            <x-label for="observaciones" :value="__('Observaciones')"/>
                            <x-input id="observaciones"
                                        class="text-left w-full"
                                        type="text"/>
                        </div>
                        <div class="flex justify-end mr-6 my-4">
                            <button id="btnIngresaProd"
                                type="submit"
                                class="inline-flex items-center px-3 py-1 cursor-pointer bg-green-800 border border-transparent rounded shadow-md font-semibold text-base text-white tracking-wide hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:cursor-not-allowed disabled:opacity-50 transition ease-in-out duration-150"
                                disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                &nbsp;Ingresa producto
                            </button>
                        </div>
                    </div>  <!-- / end grid -->
                </div>
                {{-- COL 2 / PRODUCTOS INGRESADOS  --}}
                <div class="shadow-md mr-5 my-2 bg-gray-50 overflow-y-auto border-b border-gray-200 sm:rounded-lg" style="height: 70vh;">
                    <table id="tablaProdsIngresados" class="tableFixHead min-w-full divide-y divide-gray-300 table-fixed">
                        <thead class="bg-teal-700">
                            <tr class="text-sm font-semibold text-gray-100">
                                <th scope="col" 
                                    class="px-1 py-2 text-center tracking-widest"
                                    width="35%">
                                    Descripción
                                </th>
                                <th scope="col" class="px-2 py-2 text-right" 
                                    width="15%">
                                    Cantidad
                                </th>
                                <th scope="col" class="px-2 py-2 text-center"
                                    width="20%">
                                    Un. medida
                                </th>
                                <th scope="col" class="px-2 py-2 text-right"
                                    width="20%">
                                    Total Un.
                                </th>
                                <th scope="col" class="pr-2 py-2 text-center"
                                    width="10%">
                                    Borrar
                                </th>
                            </tr>
                        </thead>
                        <tbody id="bodyProdStock"
                            class="divide-y divide-gray-200 overflow-y-scroll">
                            <!-- llena JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="mb-3 mt-2 mx-4 border-gray-500">
            <div class="flex justify-between mt-2 mb-3">
                <div>
                    <div class="hidden" id="spinGuardando">
                        <div class="flex ml-10">
                            <div class="w-48 ml-6">
                                <p>Guardando datos...</p>
                            </div>
                            <div class="w-32 mx-auto">
                                <div style="border-top-color:transparent"
                                    class="w-8 h-8 border-4 border-blue-500 border-solid rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mr-6">
                    <x-button class="mr-4" id="btnConfirma">
                        Confirma
                    </x-button>
                    <x-link-cancel href="{{ route('comprobante_stock.index') }}" class="mr-4" id="btnCancela">
                        Cancela
                    </x-link-cancel>
                    <x-link-salir href="{{  route('dashboard') }}">
                        Salir
                    </x-link-salir>
                </div>
            </div>
        </div>
    </div>  <!-- /end flex mt-8 -->

    @push('scripts')
        <script>
            var _STOCK = {
                _esEdit: false,
                comprobante_id: parseInt("{{ $new_id_comprob }}"),
                usuario_id: parseInt("{{ Auth::user()->id }}"),
                sucursal_id: parseInt("{{ $sucursal_id }}"),
                turno_id: parseInt("{{ $turno_id }}"),
                turno_sucursal: parseInt("{{ $turno_sucursal }}"),
                pathGuardarComprob: "{{ route('comprobante_stock.store') }}",
                pathGuardarDetalle: "{{ route('detalle_comprobante_stock.store') }}",
                pathImprime: "{{ route('comprobante_stock.imprime', '') }}",
                grupos: @json($grupos),
                grupos_helado: @json($grupos_helado),
                productos: @json($productos),
                productos_indiv: @json($productos_indiv),
                producto_selec: {   // datos del producto seleccionado (unitario)
                    idx: 0,
                    producto_id: '',
                    codigo: '',
                    descripcion: '',
                    cantidad: 0,
                    costo_x_unidad: 0,
                    costo_x_bulto: 0,
                    unid_medida: '',
                },
                productos_a_stock: [],  // productos ingresados a stock (array de producto_selec)
                idx_productos_stock: 0, // indice para ingreso productos a stock
                _nombreImpresora: "{{ $nombreImpresora }}",
                _env: "{{ $env }}",
            };
        </script>

        <script src="{{ asset('/js/stock/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>
