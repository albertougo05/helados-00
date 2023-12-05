<x-app-layout>
    <x-slot name="title">Heladerías - Lista precios</x-slot>

    <div class="my-4 x-0">
        <div class="mx-auto w-10/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex">
                <div class="pt-4 pl-6 text-gray-700 text-2xl font-bold">
                    Lista de precios
                </div>
            </div>
            <hr class="mt-2 mb-1 mx-4 border-gray-500">
            <div class="flex mx-2 px-2">
                <div class="mb-3 w-full">
                    <p class="ml-6 text-lg font-bold">Artículos</p>
                    <div class="my-0 mx-2 shadow-md overflow-auto rounded-md bg-white"
                        style="height: 75vh;">
                        <table class="tableFixHead min-w-full divide-x divide-y divide-gray-200" 
                            id="tablaArticulos">
                            <thead class="bg-teal-800">
                                <tr class="text-sm font-semibold text-teal-100">
                                    {{-- <th scope="col" style="width: 7%;" class="pl-3 py-2 text-center">Id</th> --}}
                                    <th scope="col" style="width: 32%;" class="pr-3 py-2 text-center tracking-widest">
                                        Descripción
                                    </th>
                                    <th scope="col" style="width: 12%;" class="pr-3 py-2 text-right">
                                        Costo unid.
                                    </th>
                                    <th scope="col" style="width: 14%;" class="px-2 py-2 text-right">
                                        Costo bulto
                                    </th>
                                    <th scope="col" style="width: 14%;" class="px-2 py-2 text-right">
                                        Pr. Lista 1
                                    </th>
                                    <th scope="col" style="width: 14%;" class="px-2 py-2 text-right">
                                        Pr. Lista 2
                                    </th>
                                    <th scope="col" style="width: 14%;" class="px-2 py-2 text-right">
                                        Pr. Lista 3
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="bodyArticulos"
                                class="bg-white divide-x divide-y divide-gray-200">
                                @foreach ($productos as $prod)
                                    <tr class="text-gray-700 text-xs hover:bg-gray-300">
                                        <td class="text-left pl-2 truncate"
                                            style="width: 32%;">
                                            {{ $prod['descripcion'] }}
                                        </td>
                                        <td class="text-right" style="width: 12%;">
                                            <input type="text" 
                                                id="costo_unid_{{ $prod['codigo'] }}" 
                                                data-idprod="{{ $prod['id'] }}"
                                                data-codigo="{{ $prod['codigo'] }}"
                                                data-campo="costo_x_unidad"
                                                data-valororig="{{ $prod['costo_x_unidad'] }}"
                                                onFocus="this.select();"
                                                onblur="_LISTA_PREC._blurInputs(this);"
                                                class="h-5 w-4/5 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value={{ $prod['costo_x_unidad'] }}>
                                                {{-- value={{ number_format($prod['costo_x_unidad'],2,',','.') }}> --}}
                                        </td>
                                        <td class="text-right" style="width: 14%;">
                                            <input type="text" 
                                                id="costo_bulto_{{ $prod['codigo'] }}" 
                                                data-idprod="{{ $prod['id'] }}"
                                                data-codigo="{{ $prod['codigo'] }}"
                                                data-campo="costo_x_bulto"
                                                data-valororig="{{ $prod['costo_x_bulto'] }}"
                                                data-unidbulto="{{ $prod['unidades_x_bulto'] }}"
                                                data-artindiv="{{ $prod['articulo_indiv_id'] }}"
                                                onFocus="this.select();"
                                                onblur="_LISTA_PREC._blurCostoBulto(this);"
                                                class="h-5 w-4/5 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value={{ $prod['costo_x_bulto'] }}>
                                        </td>
                                        <td class="text-right" style="width: 14%;">
                                            <input type="text" 
                                                id="precio_lista1_{{ $prod['codigo'] }}" 
                                                data-idprod="{{ $prod['id'] }}"
                                                data-campo="precio_lista_1"
                                                data-valororig="{{ $prod['precio_lista_1'] }}"
                                                onFocus="this.select();"
                                                onblur="_LISTA_PREC._blurInputs(this);"
                                                class="h-5 w-4/5 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value={{ $prod['precio_lista_1'] }}>
                                        </td>
                                        <td class="text-right" style="width: 14%;">
                                            <input type="text" 
                                                id="precio_lista2_{{ $prod['codigo'] }}" 
                                                data-idprod="{{ $prod['id'] }}"
                                                data-campo="precio_lista_2"
                                                data-valororig="{{ $prod['precio_lista_2'] }}"
                                                onFocus="this.select();"
                                                onblur="_LISTA_PREC._blurInputs(this);"
                                                class="h-5 w-4/5 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value={{ $prod['precio_lista_2'] }}>
                                        </td>
                                        <td class="text-right" style="width: 14%;">
                                            <input type="text" 
                                                id="precio_lista3_{{ $prod['codigo'] }}" 
                                                data-idprod="{{ $prod['id'] }}"
                                                data-campo="precio_lista_3"
                                                data-valororig="{{ $prod['precio_lista_3'] }}"
                                                onFocus="this.select();"
                                                onblur="_LISTA_PREC._blurInputs(this);"
                                                class="h-5 w-4/5 text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value={{ $prod['precio_lista_3'] }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Botones confirma / cancela  -->
            <div class="flex justify-between mb-3">
                <div class="invisible w-1/2" id="spinGuardando">
                    <div class="flex justify-center pt-1 mr-2">
                        <div class="mr-6 mt-1">
                            <p>Guardando datos...</p>
                        </div>
                        <div class="float-right">
                            <div style="border-top-color:transparent"
                                class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mr-6">
                    <div>
                        <x-button class="mr-4" id="btnConfirma">
                            Confirma
                        </x-button>
                        <x-link-cancel href="{{ route('dashboard') }}">
                            Salir
                        </x-link-cancel>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    @push('scripts')
        <script>
            var _LISTA_PREC = {
                _productos: @json($productos),
                _pathInicio: "{{ route('dashboard') }}",
                _pathSalvarLista: "{{ route('producto.listaprecios.actualiza') }}",
                _inputsIMask: [],
                _blurInputs: {},
                _blurCostoBulto: {},
            };
        </script>
        <script src="{{ asset('/js/productos/listaprecios/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>
