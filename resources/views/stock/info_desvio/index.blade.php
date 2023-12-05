<x-app-layout>
    <x-slot name="title">Heladerías - Info desvío Stock</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-2 mb-3 p-4">
        <div class="mx-auto w-full bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-8">
                <div class="mt-4 text-gray-700 text-3xl font-bold">
                    Informe desvío Stock
                </div>
                <div></div>
            </div>
            <div>
                <hr class="mt-2 mb-4 mx-4 border-gray-500">
            </div>
            <div class="grid overflow-hidden grid-cols-4 grid-rows-1 gap-3 px-10 w-full mx-auto">
                <div>
                    <x-label for="selSucursal" :value="__('Sucursal')" class="block mb-1" />
                    <x-select id="selSucursal" class="w-full h-9 mb-1" autofocus>
                        <x-slot name="options">
                            @foreach ($sucursales as $suc)
                                <option class="bg-gray-300" value="{{ $suc['id'] }}" 
                                    @if ($sucursal_id == $suc['id'])
                                        selected
                                    @endif>
                                    {{ $suc['nombre'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div>
                    <x-label for="selPeriodoDesde" :value="__('Periodo a controlar')" class="block mb-1" />
                    <x-select id="selPeriodoDesde" class="w-full h-9 mb-1 text-center">
                        <x-slot name="options">
                            @foreach ($periodos as $periodo)
                                <option class="bg-gray-300 text-center" value="{{ $periodo['id'] }}"
                                        data-fecha="{{ $periodo['fecha_toma_stock'] }}"
                                        data-hora="{{ $periodo['hora_toma_stock'] }}"
                                        @if ($loop->index == 1)
                                            selected
                                        @endif>
                                    {{ $periodo['fecha_hora'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div>
                    <x-label for="selPeriodoHasta" :value="__('Hasta')" class="block mb-1" />
                    <x-select id="selPeriodoHasta" class="w-full h-9 mb-1 text-center">
                        <x-slot name="options">
                            @foreach ($periodos as $periodo)
                                <option class="bg-gray-300 text-center" value="{{ $periodo['id'] }}">
                                    {{ $periodo['fecha_hora'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div class="pt-5 flex items-center">
                    <button id="btnGenerar"
                        class="flex items-center space-x-2 py-0.5 w-full justify-center focus:outline-none text-green-100 text-base rounded bg-green-500 hover:bg-green-600 hover:text-white hover:font-semibold hover:shadow-md ring-offset-0 ring-2 ring-green-500 hover:ring-green-600" >
                        <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                            <path d="M21 5c0-1.103-.897-2-2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5zM5 19V5h14l.002 14H5z"></path><path d="M7 7h1.998v2H7zm4 0h6v2h-6zm-4 4h1.998v2H7zm4 0h6v2h-6zm-4 4h1.998v2H7zm4 0h6v2h-6z"></path>
                        </svg>
                        <span>Generar informe</span>
                  </button>
                </div>
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>
            <div class="bg-white shadow rounded-md overflow-auto mt-2 mx-7" style="height: 60vh;">
                <table class="tableFixHead table-fixed min-w-full divide-y divide-gray-200 text-xs md:rounded-lg" id="tabla_info">
                    <thead class="bg-indigo-800 text-gray-200">
                        @include('stock.info_desvio.table-thead')
                    </thead>
                    <tbody id="tbody_tabla_info"
                        class="bg-white divide-y divide-gray-200">
                        <!-- Rellena JS 'llenaTablaInfo.js' -->
                    </tbody>
                </table>
            </div>

            <!-- Spin espera genera comprobante ... -->
            <div class="hidden mx-6 w-full mt-2" id="spinCargando">
                <div class="flex justify-center pt-1 mr-2">
                    <div class="mr-8 mt-1">
                        <p>Por favor espere... Cargando datos !</p>
                    </div>
                    <div class="float-right">
                        <div style="border-top-color:transparent"
                            class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>

            <!-- Paginacion ... -->
            <!-- <div class="mx-7 mt-1 mb-6"> -->
                {{-- {{ $informe->links() }} --}}
            <!-- </div> -->
            <!-- Botón Salida (cancelar) -->

            <div class="flex justify-end px-8 mt-3 mb-5">
                <x-link-cancel href="{{ route('stock.infodesvio') }}" class="mr-4">
                    Cancela
                </x-link-cancel>
                <x-link-salir href="{{  route('dashboard') }}">
                    Salir
                </x-link-salir>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const _DESVIO = {
                _pathGetData: "{{ route('stock.infodesvio.data') }}",
            };
        </script>
        <script src="{{ asset('js/stock/infodesvio/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>
