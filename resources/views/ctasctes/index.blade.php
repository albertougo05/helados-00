<x-app-layout>
    <x-slot name="title">Heladerías - Informe Ctas Ctes.</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-9/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-8">
                <div class="mt-6 text-gray-700 text-3xl font-bold">
                    Informe de cuentas corrientes
                </div>
                <div class="mt-10 text-blue-600 text-base font-semibold">
                    <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }}</p>
                </div>
            </div>
            <div>
                <hr class="mt-2 mb-5 mx-4 border-gray-500">
            </div> <!-- linea -->
            <div class="grid overflow-auto grid-cols-4 gap-x-3 gap-y-4 mx-6 mb-2 p-1">
                <div>   <!-- Row 1 Col 1 -->
                    <x-label for="fecha_desde" :value="__('Fecha desde')" class="mb-0"/>
                    <x-input id="fecha_desde"
                            class="h-9 w-full text-right mb-1 pt-1" 
                            type="date" 
                            autofocus />
                </div>
                <div>   <!-- Row 1 Col 2 -->
                    <x-label for="fecha_hasta" :value="__('Fecha hasta')" class="mb-0"/>
                    <x-input id="fecha_hasta"
                            class="h-9 w-full text-right mb-1 pt-1"
                            type="date" />
                </div>
                <div class="col-span-2">    <!-- Row 1 Col 1/2 -->
                    <x-label for="cliente" :value="__('Cliente')" />
                    <x-select id="cliente" class="w-full h-9 mt-0">
                        <x-slot name="options">
                            <option value="0">
                                Selecione cliente...
                            </option>
                            @foreach ($clientes as $clie)
                                <option value="{{ $clie['id'] }}">
                                    {{ $clie['firma'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
            </div>
            <div class="flex justify-center my-4">
                <button id="btnGenerarInfo"
                    class="px-12 py-1 rounded-md shadow-md bg-green-600 text-green-50 hover:bg-green-800">
                    Generar informe
                </button>
            </div>
            <div class="bg-white shadow rounded-md overflow-auto mt-8 mb-4 mx-8">
                <table id="ctasctesTable" class="min-w-full md:rounded-lg">
                    <thead class="bg-indigo-800 text-gray-200">
                        <tr>
                            <th scope="col" class="px-2 py-2 text-center">Fecha</th>
                            <th scope="col" class="px-2 py-2 text-center">Hora</th>
                            <th scope="col" class="px-2 py-2 text-center">Nro. ticket</th>
                            <th scope="col" class="px-4 py-2 text-right">Importe</th>
                        </tr>
                    </thead>
                    <tbody id="bodyDataCtaCte" class="bg-white divide-y divide-gray-200">
                        <!-- Lo rellena js -->
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mr-12">
                <div class="font-semibold text-gray-700">
                    Total $ <span id="spTotal" class="text-lg text-slate-800 font-bold">0,00</span>
                </div>
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>  <!-- linea -->
            <div class="flex justify-end px-7 my-5">    <!-- Botón Salida -->
                <x-link-cancel href="{{ route('ctasctes.index') }}" class="mr-4">
                    Cancela
                </x-link-cancel>
                <x-link-salir href="{{ route('dashboard') }}">
                    Salir
                </x-link-salir>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const _CTASCTES = {
                _pathDataCtaCte: "{{ route('ctasctes.getdata') }}",
            };

        </script>
        <script src="{{ asset('js/ctasctes/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>