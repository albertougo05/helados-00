<x-app-layout>
    <x-slot name="title">Heladerías - Info Movs Stock</x-slot>

    <div class="flex my-4 x-0">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-6">
                <div class="pt-4 text-gray-700 text-2xl font-bold">
                    Informe Movimientos Stock
                </div>
                <div class="pt-5 text-gray-700 text-lg font-bold">
                    Turno: <span class="text-blue-700">{{ $turno_id }}</span>&nbsp;<span class="text-base text-blue-700">(Usuario: {{ $usuario->name }})</span>
                </div>
                <div class="flex justify-end pt-5">
                    <a href="{{ route('stock.infomovimientos') }}" 
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
                    <p class="ml-4">Comprobantes movimientos stock</p>
                    <div class="mt-0 mb-4 mx-2 shadow-md overflow-auto rounded-md bg-white"
                        style="height: 70vh;">
                        <table class="tableFixHead min-w-full divide-x divide-y divide-gray-200" 
                            id="tablaComprobs">
                            <thead class="bg-teal-800">
                                <tr class="text-sm font-semibold text-teal-100">
                                    <th scope="col" width="20%" class="py-2 text-center">
                                        Nro.
                                    </th>
                                    <th scope="col" width="15%" class="py-2 text-center">
                                        Fecha
                                    </th>
                                    <th scope="col" width="10%" class="py-2 text-center">
                                        Hora
                                    </th>
                                    <th scope="col" width="10%" class="py-2 text-center">
                                        Motivo
                                    </th>
                                    <th scope="col" class="py-2 text-center tracking-widest">
                                        Descripcion
                                    </th>
                                    <th scope="col">

                                    </th>
                                </tr>
                            </thead>
                            <tbody 
                                id="bodyComprobs"
                                class="bg-white divide-x divide-y divide-gray-200">
                                @foreach ($comprobantes as $comprob)
                                    <tr class="text-gray-700 text-sm hover:bg-gray-100 cursor-pointer"
                                        data-id_comp="{{ $comprob->id }}"
                                        data-nro_comp="{{ $comprob->nro_comprobante }}"
                                        data-fecha_hora="{{ $comprob->fecha }} {{ $comprob->hora }}"
                                        data-descripcion="{{ $comprob->descripcion }}"
                                        data-estado="{{ $comprob->estado }}">
                                        <td class="text-center">{{ $comprob->nro_comprobante }}</td>
                                        <td class="text-center">{{ Carbon\Carbon::parse($comprob->fecha)->format('d/m/Y') }}</td>
                                        <td class="text-center">{{ Carbon\Carbon::parse($comprob->hora)->format('H:i') }}</td>
                                        @if($comprob['tipo_movimiento_id'] > 2)
                                            <td class="text-center">
                                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs leading-none text-red-100 bg-red-500 rounded-full">
                                                    Salida
                                                </span>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs leading-none text-green-100 bg-green-500 rounded-full">
                                                    Ingreso
                                                </span>
                                            </td>
                                        @endif
                                        <td class="text-center">{{ $comprob->descripcion }}</td>
                                        @if ($comprob->estado == 2)
                                            <td class="text-center text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                                </svg>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </aside>
                <main class="main flex flex-col flex-grow">
                    <p class="ml-3">Detalle del movimiento</p>
                    <div class="my-0 mx-1 shadow-md overflow-auto rounded-md bg-white"
                        style="height: 50vh;">
                        <table class="tableFixHead min-w-full divide-y divide-gray-200" 
                            id="tablaDetalle">
                            <thead class="bg-indigo-800">
                                <tr class="text-sm font-semibold text-indigo-100">
                                    <th scope="col" width="15%" class="py-2 text-right">
                                        Cantidad
                                    </th>
                                    <th scope="col" width="15%" class="py-2 text-center">
                                        Un. Med.
                                    </th>
                                    <th scope="col" class="px-1 py-2 text-center tracking-widest">
                                        Descripción
                                    </th>
                                    <th scope="col" width="15%" class="pr-3 py-2 text-right">
                                        Total unid.
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
                            class="inline-flex w-48 h-10 justify-center items-center px-3 py-1 bg-blue-600 border border-transparent rounded shadow-md font-semibold text-base text-white tracking-wide hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 255, 255, 0.85);transform: ;msFilter:;">
                                <path d="M19 7h-1V2H6v5H5c-1.654 0-3 1.346-3 3v7c0 1.103.897 2 2 2h2v3h12v-3h2c1.103 0 2-.897 2-2v-7c0-1.654-1.346-3-3-3zM8 4h8v3H8V4zm8 16H8v-4h8v4zm4-3h-2v-3H6v3H4v-7c0-.551.449-1 1-1h14c.552 0 1 .449 1 1v7z"></path>
                                <path d="M14 10h4v2h-4z"></path>
                            </svg>
                            &nbsp;&nbsp;Re-imprimir
                        </button>
                        <!-- ESTE BOTON DEBE SER SOLO PARA ADMIN !! -->
                        @if(Auth::user()->perfil_id < 2)
                            <div id="divBtnEdit">
                        @else
                            <div id="divBtnEdit" class="hidden">
                        @endif
                            <button id="btnEditar" type="button"
                                class="inline-flex w-48 h-10 justify-center items-center px-3 py-1 bg-green-600 border border-transparent rounded shadow-md font-semibold text-base text-white tracking-wide hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring-1 ring-green-300 disabled:opacity-50 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                </svg>
                                &nbsp;&nbsp;Editar
                            </button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var _INFO_MOVS_STOCK = {
                _sucursal_id: "{{ $sucursal_id }}",
                _mensaje_error: "{{ $mensaje_error }}",
                _usuario: @json($usuario),
                _pathDetalleComprob: '{{ route('stock.infomovimientos.getdetalle', 0) }}',
                _pathEditarComprob: "{{ route('comprobante_stock.edit', 0) }}",
                _idComprobSelec: 0,
                _nroComprobSelec: 0,
                _detalleComp: [],
                _turno_id: parseInt('{{ $turno_id }}'),
                _turno_sucursal: parseInt('{{ $turno_sucursal }}'),
                _pathImprime: "{{ route('comprobante_stock.imprime', '') }}",
                _nombreImpresora: "{{ $nombreImpresora }}",
                _env: "{{ $env }}",
            };
        </script>
        <script src="{{ asset('/js/stock/infomovs/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>
