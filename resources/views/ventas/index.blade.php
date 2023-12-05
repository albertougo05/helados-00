<x-app-layout>
    <x-slot name="title">Heladerías - Comprobante Venta</x-slot>
    <div class="mt-3 mx-auto w-full md:w-11/12 bg-gray-100 shadow-lg sm:rounded-lg opacity-85">
        <div class="flex justify-between px-6">
            <div class="pt-4 pl-2 text-gray-700 text-3xl font-bold tracking-wide">
                Venta
            </div>
            <div class="mt-7 text-blue-600 text-base font-semibold">
                <p id="suc_cajaNro">{{ $sucursal->nombre }} - Caja: {{ $caja_nro }} - Turno: {{ $turno_sucursal }}</p>
            </div>
            <div class="flex justify-end pt-5">
                <a href="/ventas/comprobante" 
                    class="h-7 inline-flex mr-2 text-base items-center px-3 py-2 bg-gray-300 border border-transparent rounded tracking-wide font-semibold text-gray-500 hover:bg-gray-400 hover:text-gray-100 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-400 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                      &nbsp;Cancela
                </a>
                <a href="/inicio" 
                    class="h-7 inline-flex items-center text-base px-3 py-2 bg-red-500 border border-transparent rounded tracking-wide font-semibold text-red-200 hover:bg-red-700 hover:text-gray-100 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-700 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    &nbsp;Salir
                </a>
            </div>
        </div>
        <hr class="mt-2 mb-3 mx-4 border-gray-500">
        <!-- DATOS COMPROBANTE -->
        <form id="datos_comprobante">
            @csrf
            <input type="hidden" name="usuario_id" id="usuario_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="cliente_id" id="cliente_id" value=0>
            <input type="hidden" name="codigo_comprob" id="codigo_comprob" value=1>
            <input type="hidden" name="turno_id" id="turno_id" value="{{ $turno_id }}">
            <input type="hidden" name="turno_sucursal" id="turno_sucursal" value="{{ $turno_sucursal }}">

            <!-- Row 1 -->
            <div class="grid overflow-auto grid-cols-6 grid-rows-1 gap-2 w-auto px-4 pb-2">
                <div class="col-start-1 col-span-3">
                    <x-label for="cliente" :value="__('Cliente')" />
                    <x-input-xs id="cliente"
                        name="cliente"
                        type="text"
                        class="block w-full"/>
                </div>
                <div class="flex">
                    <button id="btnBuscarCliente"
                            type="button"
                            class="w-3/5 bg-blue-500 hover:bg-blue-600 text-sm text-white h-8 mt-5 py-1 px-3 rounded inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        &nbsp;&nbsp;Cta.Cte.
                    </button>
                    <div id="divBtnBorrarClie" class="hidden ml-2 mb-0">
                        <button id="btnBorrarClie"
                            type="button"
                            class="w-full bg-red-500 hover:bg-red-600 text-sm text-white h-8 mt-5 py-1 px-3 rounded inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="white">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            &nbsp;&nbsp;Borrar
                        </button>
                    </div>
                </div>
                <div>
                    <x-label for="fecha_comprobante" :value="__('Fecha')" />
                    <x-input-xs id="fecha_comprobante"
                            name="fecha_comprobante"
                            class="block w-full text-right" 
                            type="text"
                            readonly/>
                </div>
                <div>
                    <x-label for="nro_comprobante_completo" :value="__('Número')" />
                    <x-input-xs id="nro_comprobante_completo"
                            name="nro_comprobante_completo"
                            class="block w-full text-right" 
                            type="text" 
                            value="{{ $numero }}"
                            readonly/>
                </div>
            </div>
        </form>
        <div class="pl-8 text-gray-700 text-xl font-bold">
            Ingreso de productos
        </div>
        <div>
            <hr class="mt-1 mb-3 mx-4 border-gray-500">
        </div>
        <div class="grid overflow-visible grid-cols-2 grid-rows-1 gap-1">
            <!-- col grupos y productos -->
            <div class="flex flex-row px-2">
                <aside class="sidebar w-40 overflow-auto flex-shrink mx-1 mb-3 shadow-md rounded-md bg-white"
                        style="height: 70vh;">
                    <ul class="p-1">
                        @foreach ($grupos as $grupo)
                            @continue($grupo->id == 10 || $grupo->id == 11)
                            <li class="relative">
                                <button type="button"
                                    id="ID_{{ $grupo->id }}"
                                    class="btnGrupos w-full px-2 py-1 mb-1 text-sm text-left text-gray-800 font-medium rounded focus:text-gray-200 hover:bg-gray-200 focus:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-50 transition duration-300 ease-in-out">
                                    {{ $grupo->descripcion }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </aside>
                <main class="main flex flex-col flex-grow" style="height: 70vh;">
                    <div class="mt-0 mb-0 mx-1 shadow-md overflow-auto rounded-md bg-white">
                        <table class="tableFixHead min-w-full divide-y divide-gray-200" 
                            id="tablaProductos">
                            <thead class="bg-indigo-800">
                                <tr class="text-sm font-semibold text-gray-100">
                                    {{-- <th scope="col" 
                                        class="pl-3 py-2 text-center">
                                        Código
                                    </th> --}}
                                    <th scope="col" 
                                        class="px-1 py-2 text-center tracking-widest">
                                        Descripción
                                    </th>
                                    <th scope="col" class="pr-3 py-2 text-right">
                                        Precio
                                    </th>
                                </tr>
                            </thead>
                            <tbody 
                                id="bodyProductos"
                                class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
            <!-- col venta -->
            <div>
                <div class="shadow-md mr-4 bg-white overflow-y-auto border-b border-gray-200 sm:rounded-lg"
                    style="height: 40vh;">
                    <table id="tablaVenta" class="tableFixHead min-w-full divide-y divide-gray-300 table-fixed">
                        <thead class="bg-green-600">
                            <tr class="text-sm font-semibold text-gray-100">
                                <th scope="col" 
                                    class="px-1 py-2 text-center tracking-widest"
                                    width="45%">
                                    Descripción
                                </th>
                                <th scope="col" class="px-2 py-2 text-right">
                                    Precio
                                </th>
                                <th scope="col" class="px-2 py-2 text-right">
                                    Cant.
                                </th>
                                <th scope="col" class="px-2 py-2 text-right"
                                    width="25%">
                                    Total
                                </th>
                                <th scope="col" class="pr-2 py-2 text-center"
                                    width="10%">
                                    Borrar
                                </th>
                                {{-- <th scope="col" class="relative pl-2 w-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="rgba(239, 68, 68, 0.8)" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 overflow-y-scroll" id="bodyVenta">
                            <!-- llena JS -->
                        </tbody>
                    </table>
                </div>
                <!-- TOTAL -->
                <div id="totalVenta">
                    <div class="flex justify-center">
                        <p id="total" class="mt-2 text-gray-700 text-2xl font-bold">Total $ 0,00</p>
                    </div>
                </div>
                <!-- FORMAS DE PAGO -->
                <div class="flex justify-between">
                    <div class="item pl-10">
                        <x-label for="pagoContado" :value="__('Pago efectivo')" id="lblFormaPago"/>
                        <x-input id="pagoContado"
                        name="pagoContado"
                        class="text-right w-4/5"
                        type="text"/>
                    </div>
                    <div class="item" id="div-vuelto">
                        <x-label for="vuelto" :value="__('Vuelto')" />
                        <x-input id="vuelto"
                                name="vuelto"
                                class="text-right w-4/5"
                                type="text" 
                                disabled/>
                    </div>
                </div>
                <div>
                    <hr class="mt-4 ml-2 mr-4 border-gray-500">
                </div>
                <!-- CONFIRMA VENTA -->
                <div class="flex justify-between mt-3">
                    <div class="ml-8">
                        <x-label for="forma_pago_id" :value="__('Forma de Pago')"/>
                        <x-select-xs id="forma_pago_id" name="forma_pago_id" class="w-full mb-1">
                            <x-slot name="options">
                                @foreach ($formas_pago as $forma)
                                    <option value="{{ $loop->index }}">{{ $forma }}</option>
                                @endforeach
                            </x-slot>
                        </x-select-xs>
                    </div>
                    <div class="mt-4 mr-8">
                        <button id="btnConfirmaVenta"
                            type="button"
                            class="inline-flex items-center px-3 py-1 bg-green-700 border border-transparent rounded shadow-md font-semibold text-base text-white tracking-wide hover:bg-green-800 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-50 transition ease-in-out duration-150"
                            disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            &nbsp;Confirma Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('ventas.modal-venta')
    @include('ventas.modal-cliente')
    @include('ventas.modal-promo')
    @include('ventas.modal-spin-espera')

    @push('scripts')
        <script>
            var _VENTA = {
                pathDetalleComprobReceta: "{{ route('detalle_comprob_receta.index') }}",
                pathDetalleComprobVentaStore: "{{ route('detalle_comprob_venta.store') }}",
                pathVentasComprobante: "{{ route('ventas.comprobante') }}",
                pathNroComprobante: "{{ route('ventas.comprobante.getnumero') }}",
                pathBuscarCliente: "{{ route('firma.getcliente') }}",
                pathImprimeTicket: "{{ route('ventas.comprobante.imprimir') }}",
                pathImprimeTicketCom: "{{ route('ventas.comprobante.imprimir_com') }}",
                pathCajaMovimiento: "{{ route('caja_movimiento.store') }}",

                pathImagenes: "{{ asset('imagenes/') }}" + "/",
                productos: @json($productos),
                promos: @json($promos),
                promo_opcs: @json($promo_opcs),
                promo_cant_combos: 0,
                promo_producto_seleccionado: undefined,
                
                sucursal_id: parseInt("{{ $user_sucursal_id }}"),
                sucursal_nombre: "{{ $sucursal->nombre }}",
                caja_nro: parseInt("{{ $caja_nro }}"),
                turno_id: parseInt("{{ $turno_id }}"),
                turno_sucursal: parseInt("{{ $turno_sucursal }}"),
                nro_comprobante: parseInt("{{ $numero}}"),
                fecha: "",
                usuario_id: "{{ Auth::user()->id }}",
                cajas_suc: @json($cajas_suc),
                grupos: @json($grupos),
                impresora: @json($impresora),
                env: "{{ $env }}",
            };
        </script>

        <script src="{{ asset('/js/ventas/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>