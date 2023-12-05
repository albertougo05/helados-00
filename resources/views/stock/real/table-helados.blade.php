<table class="tableFixHead min-w-full divide-y divide-gray-200" 
       id="tablaHelados">
    <thead class="bg-indigo-800">
        <tr class="text-sm font-semibold text-teal-100">
            {{-- <th scope="col" style="width: 7%;" class="pl-3 py-2 text-center">Id</th> --}}
            <th scope="col" style="width: 23%;" class="pr-3 py-2 text-center tracking-widest">
                Descripción
            </th>
            <th scope="col" style="width: 13%;" class="pr-3 py-2 text-right">
                Lata cerrada
            </th>
            <th scope="col" style="width: 13%;" class="px-1 py-2 text-right">
                Kgs. abierta
            </th>
            <th scope="col" style="width: 10%;" class="px-1 py-2 text-right">
                Env.
            </th>
            <th scope="col" style="width: 12%;" class="px-1 py-2 text-right">
                Total Kgs.
            </th>
            <th scope="col" style="width: 22%;" class="py-2 text-center">
                $ Total
            </th>
        </tr>
    </thead>
    <tbody 
        id="bodyHelados"
        class="bg-white divide-y divide-gray-200">
        @foreach ($helados as $hela)
            <tr class="text-gray-700 text-xs hover:bg-gray-100">
                {{-- <td class="text-right" style="width: 7%;">{{ $hela->id }}</td> --}}
                <td class="text-left truncate pl-2"
                    style="width: 30%;">
                    {{ $hela->descripcion }}
                </td>
                <td class="mx-0" style="width: 13%;">
                    <input type="text" 
                        id="cajas_{{ $hela->id }}"
                        data-id_hela="{{ $hela->id }}"
                        data-codigo="{{ $hela->codigo }}"
                        data-costo_caja="{{ $hela->costo_x_bulto }}"
                        data-peso_caja="{{ $hela->peso_materia_prima }}"
                        data-kilos_cajas="0"
                        data-descripcion="{{ $hela->descripcion }}"
                        class="h-5 w-full text-right text-xs mr-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        onkeydown="return _REAL.keydown(event.key);"
                        onblur="_REAL.hela_onblur(event);">
                </td>
                <td class="mx-0" style="width: 13%;">
                    <input type="text" 
                        id="kgs_abs_{{ $hela->id }}"
                        data-id_hela="{{ $hela->id }}"
                        data-codigo="{{ $hela->codigo }}"
                        data-costo_kilo="{{ $hela->costo_x_kilo }}"
                        class="h-5 w-full text-right text-xs ml-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        {{-- onkeydown="return _REAL.keydown(event.key);" --}}
                        onblur="_REAL.hela_onblur(event);">
                </td>
                <td id="peso_env_{{ $hela->id }}" class="text-right text-red-500" style="width: 10%;">
                    - {{ number_format($peso_envase, 3, ',', '.') }}
                </td>
                <td id="kgs_netos_{{ $hela->id }}" class="text-right" style="width: 12%;"></td>
                <td id="pesos_total_{{ $hela->id }}" style="width: 22%;" class="text-right pr-2"></td>
            </tr>
        @endforeach
    </tbody>
</table>