<x-app-layout>
    <x-slot name="title">Heladerías - Firmas/Clientes</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Clientes / Firmas
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>

            <div class="flex flex-col" x-data="{ open: false }">
                <div class="flex justify-between px-8">
                    <div class="flex flex-row">
                        <form method="GET" action="{{ route('firma.filtrado') }}" class="relative">
                            @csrf
                            <!--  -->
                            <svg width="20" height="20" fill="currentColor" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                            </svg>
                            <input class="focus:border-light-blue-500 focus:ring-1 focus:ring-light-blue-500 focus:outline-none w-64 text-sm text-black placeholder-gray-500 border border-gray-200 rounded-md py-2 pl-10" 
                                   type="text"
                                   id="buscar"
                                   name="buscar"
                                   value="{{ $filtroBuscar }}"
                                   aria-label="Filtro"
                                   placeholder="Buscar" 
                                   autofocus />
                        </form>

                        <button class="ml-4 mt-1 inline-flex items-center h-8 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded focus:shadow-outline hover:bg-indigo-800" 
                                @click="open = ! open">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filtros</span>
                        </button>

                        @if($filtros)
                            <div>
                                <button class="ml-4 mt-1 text-white px-4 w-auto h-8 bg-red-600 rounded-full hover:bg-red-700 active:shadow-lg mouse shadow transition ease-in duration-200 focus:outline-none"
                                        id="btnQuitarBusq">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    Quitar filtro
                                </button>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('firma.create') }}" class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nuevo
                    </a>
                </div>

                <div x-show="open" class="flex flex-row space-x-10 mx-32 mt-8 mb-4">
                    <x-select id="tipo_firma" name="tipo_firma" class="item w-10" autofocus>
                        <x-slot name="options">
                            <option value=0 selected>Seleccione tipo...</option>
                                <option value=1>Proveedor</option>
                                <option value=2>Cliente</option>
                                <option value=3>Otrod</option>
                        </x-slot>
                    </x-select>

                    <button id="btnFiltrar" class="item inline-flex items-center h-8 px-5 mt-0.5 text-indigo-100 transition-colors duration-150 bg-indigo-600 rounded focus:shadow-outline hover:bg-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                          </svg>
                        <span>Filtrar</span>
                    </button>
                </div>

                <div class="bg-white shadow rounded-md overflow-auto mt-6 mx-7">
                    <table class="min-w-full md:rounded-lg">
                        <thead class="bg-indigo-800 text-gray-200">
                            <tr>
                                <th scope="col" class="pr-2 py-2 text-right">Id</th>
                                <th scope="col" class="py-2 text-left">Firma/Cliente</th>
                                <th scope="col" class="px-2 py-2 text-right">Cuit/Dni</th>
                                <th scope="col" class="px-2 py-2 text-left">Cond.Iva</th>
                                <th scope="col" class="px-2 py-2 text-left">Dirección</th>
                                <th scope="col" class="px-2 py-2 text-left">Localidad</th>
                                <!-- <th scope="col" class="px-2 py-2 text-left">Provincia</th> -->
                                <!-- <th scope="col" class="px-2 py-2 text-left">Teléfono</th> -->
                                <!-- <th scope="col" class="px-2 py-2 text-left">Contacto</th> -->
                                <th scope="col" class="px-2 py-2 text-center">Tipo</th>
                                <th scope="col" class="relative px-2 py-2 w-16">
                                    <span class="sr-only">Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($firmas as $firma)
                                <tr class="hover:bg-gray-100">
                                    <td class="text-right pr-2">{{ $firma->id }}</td>
                                    <td class="text-left pl-2 truncate">{{ $firma->firma }}</td>
                                    <td class="text-right pr-2">{{ $firma->dni_cuit }}</td>
                                    <td class="text-center pl-2">{{ $firma->cond_iva }}</td>
                                    <td class="text-left pl-2 truncate">{{ $firma->direccion }}</td>
                                    <td class="text-left pl-2">{{ $firma->localidad }}</td>
                                    <!-- <td class="text-left pl-2">{{ $firma->provincia }}</td> -->
                                    <!-- <td class="text-left pl-2">{{ $firma->telefono }}</td> -->
                                    <!-- <td class="text-left pl-2 truncate">{{ $firma->contacto }}</td> -->
                                    <td class="text-center">
                                        @if ($firma->proveedor == 1)
                                            <span class="inline-flex justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                                                Proveedor
                                            </span>
                                        @elseif ($firma->cliente == 1)
                                            <span class="inline-flex justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                                                Cta. Cte.
                                            </span>
                                        @else
                                            <span class="inline-flex justify-center px-2 py-1 text-xs font-bold leading-none text-orange-100 bg-orange-600 rounded-full">
                                                Otros
                                            </span>
                                         @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('firma.edit', $firma) }}" class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-3 py-1">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- Paginacion ... -->
                <div class="mx-7 mt-1 mb-6">
                    {{ $firmas->links() }}
                </div>

                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 mb-5">
                    <x-link-cancel href="{{ route('dashboard') }}">
                        Salir
                    </x-link-cancel>
                </div>
        </div>
    </div>

    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            @if (session('status'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{ session('status') }}',
                    showConfirmButton: false,
                    timer: 3000
                    });
            @endif

            const _FIRMA = {
                pathIndex: "{{ route('firma.index') }}",
                pathFiltrado: "{{ route('firma.filtrado') }}",
                filtros: @json($filtros),
            };
        </script>

        <script src="{{ asset('/js/firmas/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>
