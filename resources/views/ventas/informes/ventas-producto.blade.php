<x-app-layout>
    <x-slot name="title">Heladerías - Info Vta. Producto</x-slot>

    <div class="flex my-8 x-0">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Informe Ventas Producto
            </div>
            <div>
                <hr class="mt-2 mb-5 mx-4 border-gray-500">
            </div>
            <div class="grid overflow-hidden grid-cols-4 grid-rows-1 gap-10 px-10 w-full mx-auto">
                <div class="box col-span-2">
                    <div>
                        <x-label for="selProducto" :value="__('Lista de productos')" class="block mb-1" />
                        <x-select id="selProducto" class="w-full h-9" autofocus>
                            <x-slot name="options">
                                <option class="bg-gray-300" value="0" selected>
                                    Seleccione producto ...
                                </option>
                                @foreach ($productos as $prod)
                                    <option class="bg-gray-300" value="{{ $prod->codigo }}">
                                        {{ $prod->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                </div>
                <div class="box flex flex-col">
                    <x-label for="fecha_desde" :value="__('Fecha desde')" />
                    <x-input-date id="fecha_desde"
                            name="fecha_desde"
                            class="mt-0.5 block w-full mb-1" 
                            type="date" 
                            value="{{ $fecha_desde }}" />
                </div>
                <div class="box flex flex-col">
                    <x-label for="fecha_hasta" :value="__('Fecha hasta')" />
                    <x-input-date id="fecha_hasta"
                            name="fecha_hasta"
                            class="mt-0.5 block w-full mb-1" 
                            type="date" 
                            value="{{ $fecha_hasta }}" />
                </div>
            </div>
            <div>
                <hr class="mb-4 mt-3 mx-4 border-gray-500">
            </div>
            <div id="div-transp" class="hidden">
                <div class="flex justify-end">
                    <p id="transporte" class="font-medium text-sm text-gray-700 mb-0 mr-10">Transporte de stock: </p>
                </div>
            </div>
            <div class="bg-white shadow rounded-md overflow-auto mt-2 mb-6 mx-7" style="height: 40vh;">
                <table class="tableFixHead min-w-full md:rounded">
                    <thead class="bg-indigo-800 text-gray-200">
                        <tr>
                            <th scope="col" class="py-2 text-center">Fecha</th>
                            <th scope="col" class="py-2 text-center">Hora</th>
                            <th scope="col" class="py-2 text-right">Comprobante</th>
                            <th scope="col" class="py-2 text-center">Sucursal</th>
                            <th scope="col" class="px-2 py-2 text-center">Producto</th>
                            <th scope="col" class="px-2 py-2 text-right">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTablaMovs" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>

            <div>
                <hr class="mb-6 mt-3 mx-4 border-gray-500">
            </div>
            <div class="flex justify-end mt-4 mb-6 mr-6">
                <x-link-cancel href="{{ route('ventas.infoventasprod') }}" class="mr-4">
                    Cancelar
                </x-link-cancel>
                <x-link-salir href="{{ route('dashboard') }}">
                    Salir
                </x-link-salir>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var _INFO = {};
        </script>
        <script src="{{ asset('/js/ventas/infoventasprod.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>
