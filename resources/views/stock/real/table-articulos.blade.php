<table class="tableFixHead min-w-full divide-x divide-y divide-gray-200" 
       id="tablaArticulos">
    <thead class="bg-teal-800">
        <tr class="text-sm font-semibold text-teal-100">
            {{-- <th scope="col" style="width: 7%;" class="pl-3 py-2 text-center">Id</th> --}}
            <th scope="col" style="width: 23%;" class="pr-3 py-2 text-center tracking-widest">
                Descripción
            </th>
            <th scope="col" style="width: 13%;" class="pr-3 py-2 text-right">
                Bultos
            </th>
            <th scope="col" style="width: 13%;" class="px-1 py-2 text-right">
                Unid.
            </th>
            <th scope="col" style="width: 10%;" class="px-1 py-2 text-right">
                Total Un.
            </th>
            <th scope="col" style="width: 12%;" class="px-1 py-2 text-right">
                $ x Bulto
            </th>
            <th scope="col" style="width: 22%;" class="px-1 py-2 text-center">
                $ Total
            </th>
        </tr>
    </thead>
    <tbody id="bodyArticulos"
        class="bg-white divide-x divide-y divide-gray-300">
        @foreach ($articulos as $art)
            <tr class="text-gray-700 text-xs hover:bg-gray-200">
                {{-- <td class="text-right"  style="width: 7%;">{{ $art->codigo }}</td> --}}
                <td class="text-left pl-2 truncate"
                    style="width: 30%;">
                    {{ $art->descripcion }}
                </td>
                <td class="mx-0"  style="width: 10%;">
                    <input type="text" 
                        id="bultos_{{ $art->id }}"  {{-- ->id --}}
                        data-id_art="{{ $art->id }}"    {{-- ->id --}}
                        data-codigo="{{ $art->codigo }}"
                        data-costobulto="{{ $art->costo_x_bulto }}"
                        data-unid_x_bulto="{{ $art->unidades_x_bulto }}"
                        data-total_unidades=0
                        class="h-5 w-full text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        onkeydown="return _REAL.keydown(event.key);"
                        onblur="_REAL.bultos_onblur(event);">
                </td>
                <td class="mx-0" style="width: 10%;">
                    <input type="text" 
                        id="unid_{{ $art->id }}"
                        data-id_art="{{ $art->id }}"
                        data-codigo="{{ $art->codigo }}"
                        data-costounid="{{ $art->costo_x_unidad }}"
                        data-descripcion="{{ $art->descripcion }}"
                        class="h-5 w-full text-right text-xs pl-0 pr-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        onkeydown="return _REAL.keydown(event.key);"
                        onblur="_REAL.unid_onblur(event);">
                </td>
                
                <td id="total_unid_{{ $art->id }}" class="text-right" style="width: 10%;"></td>
                <td id="pesos_x_bulto_{{ $art->id }}" class="text-right" style="width: 12%;">
                    {{ number_format($art->costo_x_bulto, 2, ',', '.') }}
                </td>
                <td id="pesos_total_{{ $art->id }}" class="text-right pr-3" style="width: 22%;"></td>
            </tr>
        @endforeach
    </tbody>
</table>