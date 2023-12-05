<x-app-layout>
    <x-slot name="title">Heladerías - Info Turnos</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-8">
                <div class="mt-6 text-gray-700 text-3xl font-bold">
                    Informe turnos de caja
                </div>
                <div class="mt-10 text-blue-600 text-base font-semibold">
                    <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }}</p>
                </div>
            </div>
            <div>
                <hr class="mt-2 mb-4 mx-4 border-gray-500">
            </div>
            <div class="grid overflow-hidden grid-cols-5 grid-rows-1 gap-3 px-10 w-full mx-auto">
                <!-- <div class="box col-span-2"> -->
                    <div>
                        <x-label for="selUsuario" :value="__('Usuarios')" class="block mb-1" />
                        <x-select id="selUsuario" class="w-full h-9" autofocus>
                            <x-slot name="options">
                                <option class="bg-gray-300" value="0" selected>
                                    Todos
                                </option>
                                @foreach ($usuarios as $user)
                                    <option class="bg-gray-300" 
                                        value="{{ $user->id }}"
                                        {{ $usuario_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                <!-- </div> -->
                <div>
                    <x-label for="caja_nro" :value="__('Caja Nro.')" class="block mb-1" />
                    <x-select id="caja_nro" class="w-full h-9">
                        <x-slot name="options">
                            <option value="0">
                                Todas
                            </option>
                            @foreach ($cajas as $punto)
                                <option value="{{ $punto['id'] }}"
                                    {{ $caja_nro == $punto['id'] ? 'selected' : '' }}>
                                    {{ $punto['texto'] }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                </div>
                <div class="box flex flex-col">
                    <x-label for="fecha_desde" :value="__('Fecha desde')" />
                    <x-input-date id="fecha_desde"
                            class="mt-0.5 block w-full mb-1" 
                            type="date" 
                            value="{{ $desde }}" />
                </div>
                <div class="box flex flex-col">
                    <x-label for="fecha_hasta" :value="__('Fecha hasta')" />
                    <x-input-date id="fecha_hasta"
                            class="mt-0.5 block w-full mb-1" 
                            type="date" 
                            value="{{ $hasta }}" />
                </div>
                <div class="pt-6">
                    <button id="btnFiltrar"
                        class="w-full focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center" >
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg> -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filtrar turnos
                    </button>
                </div>
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>
            <div class="bg-white shadow rounded-md overflow-auto mt-4 mx-7">
                    <table class="min-w-full text-xs md:rounded-lg">
                        <thead class="bg-indigo-800 text-gray-200">
                            @include('turnos.informes.table-thead')
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @include('turnos.informes.table-tbody')
                        </tbody>
                    </table>
                </div>
                <!-- Paginacion ... -->
                <div class="mx-7 mt-1 mb-6">
                    {{ $turnos->links() }}
                </div>
                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 my-5">
                    <x-link-cancel href="{{ route('dashboard') }}">
                        Salir
                    </x-link-cancel>
                </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/turnos/informe/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>