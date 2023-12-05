<x-app-layout>
    <x-slot name="title">Heladerías - Caja Informe Movs</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-9/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-8">
                <div class="mt-6 text-gray-700 text-3xl font-bold">
                    Informe movimientos de caja
                </div>
                <div class="mt-10 text-blue-600 text-base font-semibold">
                    <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }}</p>
                </div>
            </div>
            <div><hr class="mt-2 mb-5 mx-4 border-gray-500"></div> <!-- linea -->
            <div class="grid overflow-auto grid-cols-4 gap-x-3 gap-y-2 mx-6 p-1">
                <div>   <!-- Row 1 Col 1 -->
                    <x-label for="fecha_desde" :value="__('Fecha desde')" class="mb-0"/>
                    <x-input id="fecha_desde"
                            class="h-9 w-full text-right mb-1 pt-1" 
                            type="date" 
                            autofocus />
                </div>
                <div>   <!-- Row 1 Col 2 -->
                    <x-label for="hora_desde" :value="__('Hora desde')" class="mb-0"/>
                    <x-input id="hora_desde"
                        type="text"
                        class="h-9 w-full text-right mb-1" />
                </div>
                <div>   <!-- Row 1 Col 3 -->
                    <x-label for="fecha_hasta" :value="__('Fecha hasta')" class="mb-0"/>
                    <x-input id="fecha_hasta"
                            class="h-9 w-full text-right mb-1 pt-1"
                            type="date" />
                </div>
                <div>   <!-- Row 1 Col 4 -->
                    <x-label for="hora_hasta" :value="__('Hora hasta')" class="mb-0"/>
                    <x-input id="hora_hasta"
                        type="text"
                        class="h-9 w-full text-right mb-1" />
                </div>
                <div class="col-span-2">    <!-- Row 2 Col 1/2 -->
                    <x-label for="usuario_id" :value="__('Usuario')" />
                    <x-select id="usuario_id" class="w-full h-9 mt-0">
                        <x-slot name="options">
                            <option value="0">
                                Todos los usuarios...
                            </option>
                            @foreach ($usuarios as $user)
                                <option value="{{ $user['id'] }}">
                                    {{ $user['name'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div class="col-span-2"></div>    <!-- Row 2 Col 3/4 -->
                <div class="col-span-2">        <!-- Row 3 Col 1/2 -->
                    <x-label for="tipo_movim" :value="__('Tipo movimiento')" />
                    <x-select id="tipo_movim" class="w-full h-9 mt-0">
                        <x-slot name="options">
                            <option value="0">
                                Seleccione ...
                            </option>
                            @foreach ($tipos_movim as $tipmov)
                                <option value="{{ $tipmov['id'] }}">
                                    {{ $tipmov['descripcion'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div class="col-span-2">        <!-- Row 3 Col 3/4 -->
                    <x-label for="formas_pago" :value="__('Formas de pago')" />
                    <x-select id="formas_pago" class="w-full h-9 mt-0">
                        <x-slot name="options">
                            <option value="0">
                                Seleccione ...
                            </option>
                            @foreach ($formas_pago as $formas)
                                <option value="{{ $formas['id'] }}">
                                    {{ $formas['descripcion'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
            </div>
            <div class="flex justify-center my-12">
                <button id="btnGenerarInfo"
                    class="px-12 py-2 rounded-md shadow-md bg-green-600 text-green-50 hover:bg-green-800">
                    Generar informe
                </button>
            </div>
            <div><hr class="mt-2 mb-5 mx-4 border-gray-500"></div>  <!-- linea -->
            <div class="flex justify-end px-7 my-5">    <!-- Botón Salida -->
                <x-link-cancel href="{{ route('movimientos_caja.informe') }}" class="mr-4">
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
            const _INFORM = {
                _pathShowInfo: "{{ route('movimientos_caja.informe.show') }}",
            };

        </script>
        <script src="{{ asset('js/caja/informe/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>