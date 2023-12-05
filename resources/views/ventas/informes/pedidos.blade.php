<x-app-layout>
    <x-slot name="title">Heladerías - Info Pedidos</x-slot>

    <div class="flex my-4 x-0">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-6">
                <div class="pt-4 text-gray-700 text-2xl font-bold">
                    Informe Pedidos Turno: <span class="text-blue-700">{{ $turno_sucursal }}</span>&nbsp;<span class="text-base text-blue-700">(Usuario: {{ $usuario_name }})</span>
                </div>
                <div class="flex justify-end pt-5">
                    <a href="{{ route('ventas.infopedido') }}" 
                        class="h-7 inline-flex mr-2 text-base items-center px-3 py-2 bg-gray-300 border border-transparent rounded tracking-wide font-semibold text-gray-500 hover:bg-gray-400 hover:text-gray-100 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-400 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        &nbsp;Cancela
                    </a>
                    <a href="{{ route('dashboard') }}" 
                        class="h-7 inline-flex items-center text-base px-3 py-2 bg-red-500 border border-transparent rounded tracking-wide font-semibold text-red-100 hover:bg-red-600 hover:text-gray-100 active:bg-red-600 focus:outline-none focus:border-red-400 focus:ring ring-red-400 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        &nbsp;Salir
                    </a>
                </div>
            </div>
            <div>
                <hr class="mt-2 mb-4 mx-4 border-gray-500">
            </div>
            <!-- col comprobantes y detalle comprobante -->
            <div class="flex flex-row px-2 mr-2">
                <aside class="sidebar w-1/2">
                    <p class="ml-4">Comprobantes de pedido</p>
                    <div class="mt-0 mb-4 mx-2 shadow-md overflow-auto rounded-md bg-white"
                        style="height: 70vh;">
                        <table class="tableFixHead min-w-full divide-x divide-y divide-gray-200" 
                            id="tablaComprobs">
                            <thead class="bg-teal-800">
                                <tr class="text-sm font-semibold text-teal-100">
                                    <th scope="col" class="pl-3 py-2 text-right">
                                        Nro.
                                    </th>
                                    <th scope="col" class="px-1 py-2 text-center tracking-widest">
                                        Operación
                                    </th>
                                    <th scope="col" class="pr-3 py-2 text-center">
                                        F. Pago
                                    </th>
                                    <th scope="col" class="pr-3 py-2 text-center">
                                        Fecha
                                    </th>
                                    <th scope="col" class="pr-3 py-2 text-center">
                                        Hora
                                    </th>
                                    <th scope="col" class="pr-3 py-2 text-right">
                                        Importe
                                    </th>
                                    <th scope="col" class="pr-3 py-2 text-center">
                                        Estado
                                    </th>
                                </tr>
                            </thead>
                            <tbody 
                                id="bodyComprobs"
                                class="bg-white divide-x divide-y divide-gray-200">
                                @foreach ($comprobantes as $comprob)
                                    <tr class="text-gray-700 text-sm hover:bg-gray-100 cursor-pointer"
                                        data-idpedido="{{ $comprob['id'] }}"
                                        data-nrocomp="{{ $comprob['nro_comprobante'] }}"
                                        data-estado="{{ $comprob['estado'] }}">
                                        <td class="text-right">{{ $comprob['nro_comprobante'] }}</td>
                                        <td class="text-center">{{ $comprob['cliente_id'] == 0 ? 'Venta' : 'Cta. Cte.' }}</td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs leading-none text-red-100 bg-blue-500 rounded-full">
                                                {{ $formas_pago[$comprob['forma_pago_id']] }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ Carbon\Carbon::parse($comprob['fecha'])->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ Carbon\Carbon::parse($comprob['hora'])->format('H:i') }}</td>
                                        <td class="text-right pr-2">{{ number_format($comprob['total'], 2, ',', '.') }}</td>
                                        @if($comprob['estado'] == 0)
                                            <td class="text-center" data-estado="{{ $comprob['estado'] }}">
                                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs leading-none text-red-100 bg-red-500 rounded-full">
                                                    Anulado
                                                </span>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs leading-none text-green-100 bg-green-500 rounded-full">
                                                    Normal
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </aside>
                <main class="main flex flex-col flex-grow">
                    <p class="ml-3">Detalle de pedido</p>
                    <div class="my-0 mx-1 shadow-md overflow-auto rounded-md bg-white"
                        style="height: 50vh;">
                        <table class="tableFixHead min-w-full divide-y divide-gray-200" 
                            id="tablaDetalle">
                            <thead class="bg-indigo-800">
                                <tr class="text-sm font-semibold text-indigo-100">
                                    <th scope="col" width="10%" class="pl-3 py-2 text-center">
                                        Cant.
                                    </th>
                                    <th scope="col" class="px-1 py-2 text-center tracking-widest">
                                        Descripción
                                    </th>
                                    <th scope="col" width="15%" class="pr-3 py-2 text-right">
                                        Precio
                                    </th>
                                    <th scope="col" width="20%" class="pr-3 py-2 text-right">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody 
                                id="bodyDetalle"
                                class="bg-white divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <hr class="my-5 mx-1 border-gray-500">
                    </div>
                    <div class="flex justify-around">
                        <button id="btnReimprimir" type="button"
                            class="inline-flex w-48 h-10 justify-center items-center px-3 py-1 bg-blue-800 border border-transparent rounded shadow-md font-semibold text-base text-white tracking-wide hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 255, 255, 0.85);transform: ;msFilter:;">
                                <path d="M19 7h-1V2H6v5H5c-1.654 0-3 1.346-3 3v7c0 1.103.897 2 2 2h2v3h12v-3h2c1.103 0 2-.897 2-2v-7c0-1.654-1.346-3-3-3zM8 4h8v3H8V4zm8 16H8v-4h8v4zm4-3h-2v-3H6v3H4v-7c0-.551.449-1 1-1h14c.552 0 1 .449 1 1v7z"></path>
                                <path d="M14 10h4v2h-4z"></path>
                            </svg>
                            &nbsp;&nbsp;Re-imprimir
                        </button>
                        <button id="btnAnular" type="button"
                            class="inline-flex w-48 h-10 justify-center items-center px-3 py-1 bg-red-800 border border-transparent rounded shadow-md font-semibold text-base text-white tracking-wide hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring-1 ring-red-300 disabled:opacity-50 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 255, 255, 0.85);transform: ;msFilter:;">
                                <path d="M9.172 16.242 12 13.414l2.828 2.828 1.414-1.414L13.414 12l2.828-2.828-1.414-1.414L12 10.586 9.172 7.758 7.758 9.172 10.586 12l-2.828 2.828z"></path>
                                <path d="M12 22c5.514 0 10-4.486 10-10S17.514 2 12 2 2 6.486 2 12s4.486 10 10 10zm0-18c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8z"></path>
                            </svg>
                            &nbsp;&nbsp;Anular
                        </button>
                    </div>
                    <div class="hidden" id="spinImprime">
                        <div class="flex h-20 ml-10 items-center">
                            <div class="ml-5">
                                <p>Re-imprimiendo ticket ! ...</p>
                            </div>
                            <div class="mx-auto">
                                <div style="border-top-color:transparent"
                                    class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            var _PEDIDO = {
                _pathDetalleComprob: '{{ route('ventas.detallecomprobante', 0) }}',
                _pathAnularComprob: "{{ route('ventas.comprobante.anular', 0) }}",
                _pathAnulaDetalleVenta: "{{ route('ventas.detallecomprobventa.anular', 0) }}",
                _pathAnulaDetalleReceta: "{{ route('ventas.detallecomprobreceta.anular', 0) }}",
                _pathAnulaMovCaja: "{{ route('caja_movimiento.anulamovim', 0) }}",
                _pathImprimir: "{{ route('ventas.comprobante.imprimir') }}",
                //_pathImprimirCom: "{{ route('ventas.comprobante.imprimir_com') }}",
                _nroComprobSelec: 0,
                _idComprobSelec: 0,
                _comprobantes: @json($comprobantes),
                _detalleComp: [],
                _turno_sucursal: parseInt('{{ $turno_sucursal }}'),
                _turno_id: parseInt('{{ $turno_id }}'),
                _sucursal_id: parseInt('{{ $sucursal_id }}'),
                _usuario_id: parseInt('{{ $usuario_id }}'),
                _impresora: @json($impresora),
                _env: "{{ $env }}",
            };
        </script>
        <script src="{{ asset('/js/ventas/infopedidos/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>
