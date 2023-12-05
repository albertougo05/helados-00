<x-app-layout>
    <x-slot name="title">Heladerías - Info Stock Diario</x-slot>

    <div class="my-4 x-0">
        <div class="mx-auto w-10/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between">
                <div class="pt-4 pl-6 text-gray-700 text-2xl font-bold">
                    Informe de Stock Diario
                </div>
                <div class="pr-6 pt-6 mb-0 text-gray-700 font-bold">
                    Sucursal: <span class="text-blue-700">{{ $sucursal->nombre }}</span>&nbsp;
                    -&nbsp; Usuario: <span class="text-base text-blue-700">{{ $usuario->name }}</span>&nbsp;
                    -&nbsp; Turno: <span class="text-blue-700">{{ $turno_id }}</span>
                </div>
            </div>
            <hr class="mt-2 mb-1 mx-4 border-gray-500">
            <div class="flex flex-row px-2 mx-2">
                <aside class="sidebar w-1/2 mb-4">
                    <div>
                        <p class="ml-6 text-lg font-bold">Artículos</p>
                        <div class="my-0 mx-2 shadow-md overflow-auto rounded-md bg-white"
                            style="height: 70vh;">
                            <table class="tableFixHead min-w-full divide-x divide-y divide-gray-200" 
                                id="tablaArticulos">
                                <thead class="bg-teal-800">
                                    <tr class="text-sm font-semibold text-teal-100">
                                        {{-- <th scope="col" style="width: 7%;" class="pl-3 py-2 text-center">Id</th> --}}
                                        <th scope="col" style="width: 60%;" class="pr-3 py-2 text-center tracking-widest">
                                            Descripción
                                        </th>
                                        <th scope="col" style="width: 20%;" class="pr-3 py-2 text-right">
                                            Bultos
                                        </th>
                                        <th scope="col" style="width: 20%;" class="px-2 py-2 text-right">
                                            Unidades
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="bodyArticulos"
                                    class="bg-white divide-x divide-y divide-gray-200">
                                    @foreach ($articulos as $art)
                                        @if ($art['final_bultos'] !== 0 || $art['final_unid'] !== 0)
                                            <tr class="text-gray-700 text-xs bg-green-300 hover:bg-gray-200">
                                        @else
                                            <tr class="text-gray-700 text-xs hover:bg-gray-200">
                                        @endif
                                                <td class="text-left pl-2 truncate"
                                                    style="width: 60%;">
                                                    {{ $art['descripcion'] }}
                                                </td>
                                                <td class="text-right"  style="width: 20%;">
                                                    <input type="text" 
                                                        id="bultos_{{ $art['id'] }}" 
                                                        data-id_art="{{ $art['id'] }}"
                                                        data-codigo="{{ $art['codigo'] }}"
                                                        data-costobulto="{{ $art['costo_x_bulto'] }}"
                                                        data-unid_x_bulto="{{ $art['unid_x_bulto'] }}"
                                                        data-total_unidades=0
                                                        class="h-5 w-2/3 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                        onkeydown="return _INFO_DIARIO._keydown(event.key);"
                                                        value={{ $art['final_bultos'] }}>
                                                </td>
                                                <td class="text-right" style="width: 20%;">
                                                    <input type="text" 
                                                        id="unid_{{ $art['id'] }}"
                                                        data-id_art="{{ $art['id'] }}"
                                                        data-codigo="{{ $art['codigo'] }}"
                                                        data-costounid="{{ $art['costo'] }}"
                                                        data-descripcion="{{ $art['descripcion'] }}"
                                                        class="h-5 w-2/3 mx-1 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                        onkeydown="return _INFO_DIARIO._keydown(event.key);"
                                                        value={{ $art['final_unid'] }}>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </aside>
                <div class="flex flex-col justify-between w-1/2 mb-4">
                    <!-- <div class="w-1/3 mx-auto mt-3"> grid overflow-hidden grid-cols-5 mx-6 mb-1 -->
                    <div class="grid grid-cols-2 gap-4 mt-3 mx-4">
                        <div>
                            <x-label for="fecha_toma_stock" :value="__('Fecha')"/>
                            <x-input id="fecha_toma_stock"
                                class="h-8 text-right w-full pb-1 pt-0.5"
                                type="date"
                                disabled />
                        </div>
                        <div>
                            <x-label for="hora_toma_stock" :value="__('Hora')"/>
                            <x-input id="hora_toma_stock"
                                class="h-8 text-right w-full"
                                type="text"
                                disabled />
                        </div>
                    </div>
                    <div>
                        <hr class="my-4 border-gray-500">
                        <div class="flex justify-end w-full">
                            <div id="spinImprime" class="hidden mr-4">
                                <div class="flex">
                                    <div class="w-40">
                                        <p>Imprimiendo...</p>
                                    </div>
                                    <div class="w-12">
                                        <div style="border-top-color:transparent" class="w-8 h-8 border-4 border-blue-500 border-solid rounded-full animate-spin"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end mx-2">
                                <x-button class="mr-4" id="btnImprimir">
                                    Imprimir
                                </x-button>
                                <x-link-salir href="{{ route('dashboard') }}">
                                    Salir
                                </x-link-salir>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var _INFO_DIARIO = {
                _usuario: @json($usuario),
                _sucursal_id: "{{ $sucursal_id }}",
                _sucursal: "{{ $sucursal->nombre }}",
                _idComprobSelec: 0,
                _nroComprobSelec: 0,
                _detalleComp: [],
                _turno_id: parseInt('{{ $turno_id }}'),
                _turno_sucursal: parseInt('{{ $turno_sucursal }}'),
                _pathImprime: "{{ route('comprobante_stock.imprime', '') }}",
                _nombreImpresora: "{{ $nombreImpresora }}",
                _keydown: function (key) {
                    // Admite solo números, tab, flechas, delete y backspace
                    return (key >= '0' && key <= '9') ||
                        ['Tab','ArrowLeft','ArrowRight','Delete','Backspace'].includes(key);
                },
                _env: "{{ $env }}",
            };
        </script>
        <script src="{{ asset('/js/stock/infodiario/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>
