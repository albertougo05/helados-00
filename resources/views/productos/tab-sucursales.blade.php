<div class="flex my-2">
    <div class="flex mx-6 px-4">
        <input type="checkbox"
               id="sucursalesTodas"
               class="block ml-3 mr-3" 
               value="todas" />
        <x-label for="sucursalesTodas" :value="__('Seleccionar todas')" />
    </div>
</div>
<div class="mx-12 mb-4 bg-white overflow-x-auto rounded shadow-md">
    <table class="table-fixed min-w-full rounded">
        <thead class="bg-indigo-800 text-gray-200">
        <tr class="h-10">
            <th scope="col" class="text-right">Sucursal</th>
            <th scope="col">Habilitar</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($sucursales as $suc)
                <tr class="hover:bg-gray-100">
                    <td class="w-2/4 py-1 text-right">{{ $suc->nombre }}</td>
                    <td>
                        <div class="flex justify-center">
                            <input type="checkbox"
                                name="sucursales[]"
                                value="{{ $suc->id }}"
                                class="block ml-3" 
                                data-idregsuc="0"
                            />
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- <div class="flex justify-end mt-2 mb-4 mr-6">
    <button type="button"
        class="h-8 py-1 px-2 mt-4 mb-2 mr-6 inline-flex items-center bg-red-800 border border-transparent rounded font-semibold text-base text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
        id="btnConfirmaSucs">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>
        Confirma Sucursales
    </button>
</div> --}}
