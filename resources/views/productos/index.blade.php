<x-app-layout>
    <x-slot name="title">Heladerías - Productos</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-4 mb-3">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-3 text-gray-700 text-3xl font-bold">
                Productos
            </div>
            <div>
                <hr class="mb-4 mt-1 mx-4 border-gray-500">
            </div>

            <div class="flex flex-col" x-data="{ open: false }">
                <div class="flex justify-between px-8">
                    <div class="flex flex-row">
                        <form method="GET" action="/producto/filtrado" class="relative">
                            @csrf
                            <!--  -->
                            <svg width="24" height="24" fill="currentColor" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                            </svg>
                            <input class="h-8 m-1 py-2 pl-10 focus:border-light-blue-500 focus:ring-1 focus:ring-light-blue-500 focus:outline-none w-64 text-sm text-black placeholder-gray-500 border border-gray-200 rounded-md" 
                                   type="text"
                                   id="buscar"
                                   name="buscar"
                                   value="{{ $filtroBuscar }}"
                                   aria-label="Filtro"
                                   placeholder="Buscar" 
                                   autofocus />
                        </form>
                        <button id="btnBuscar"
                                type="submit"
                                class="ml-4 mt-1 inline-flex items-center h-8 px-5 text-indigo-100 transition-colors duration-150 bg-orange-600 rounded focus:shadow-outline hover:bg-orange-700 hover:font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-3">
                                <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
                            </svg>
                            <span>Buscar</span>
                        </button>
                        <button class="ml-4 mt-1 inline-flex items-center h-8 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-500 rounded focus:shadow-outline hover:bg-indigo-800" 
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

                    <a href="{{ route('producto.create') }}" class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nuevo
                    </a>
                </div>

                <div x-show="open" class="flex flex-row space-x-10 mx-8 mt-5 mb-2">
                    <select id="proveedor_id" 
                            name="proveedor_id" 
                            class="w-full h-9 text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline" 
                            autofocus>
                        <option value=0 selected>Seleccione proveedor...</option>
                        @foreach ($proveedores as $prov)
                            <option value="{{ $prov->id }}">
                                {{ $prov->firma }}
                            </option>
                        @endforeach
                    </select>
                    <select id="tipo_producto_id" 
                            name="tipo_producto_id" 
                            class="w-full h-9 text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline">
                        <option value=0 selected>Seleccione tipo...</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">
                                {{ $tipo->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    <select id="grupo_id" 
                            name="grupo_id" 
                            class="w-full h-9 text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline">
                        <option value=0 selected>Seleccione grupo...</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">
                                {{ $grupo->descripcion }}
                            </option>
                        @endforeach
                    </select>

                    <button id="btnFiltrar" class="w-full inline-flex justify-center items-center h-8 px-5 mt-0.5 text-indigo-100 transition-colors duration-150 bg-indigo-600 rounded focus:shadow-outline hover:bg-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                          </svg>
                        <span>Filtrar</span>
                    </button>
                </div>

                <div class="bg-white shadow rounded-md overflow-auto mt-3 mx-7">
                    <table class="min-w-full md:rounded-lg text-sm" id="tabla-prods">
                        <thead class="bg-indigo-800 text-gray-200">
                            <tr>
                                {{-- <th scope="col" class="px-3 py-2 text-center">Id</th> --}}
                                <th scope="col" class="px-3 py-2 text-center">Código</th>
                                <th scope="col" class="py-2 text-center">Descripción</th>
                                {{-- <th scope="col" class="px-3 py-2 text-left">Proveedor</th> --}}
                                <th scope="col" class="px-2 py-2 text-right">Costo x bulto</th>
                                <th scope="col" class="px-2 py-2 text-right">Precio suger.</th>
                                <th scope="col" class="px-2 py-2 text-right">Un. x bulto</th>
                                <th scope="col" class="px-2 py-2 text-right">Cajas x bulto</th>
                                <th scope="col" class="px-2 py-2 text-right">Un. x caja</th>
                                <th scope="col" class="px-2 py-2 text-center w-14">Estado</th>
                                <th scope="col" class="relative px-2 py-2 w-16">
                                    <span class="sr-only">Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productos as $producto)
                                <tr class="hover:bg-gray-100" id={{ $producto->id }}>
                                    {{-- <td class="text-center">{{ $producto->id }}</td> --}}
                                    <td class="pl-6 text-left">{{ $producto->codigo }}</td>
                                    <td class="text-left truncate">{{ $producto->descripcion }}</td>
                                    {{-- <td class="text-right">{{ $producto->proveedor_id }}</td> --}}
                                    @if ($producto->costo_x_bulto == 0)
                                        <td></td>
                                    @else
                                        <td class="text-right">{{ number_format($producto->costo_x_bulto,2,',','.') }}</td>
                                    @endif
                                    @if ($producto->precio_lista_1 == 0)
                                        <td></td>
                                    @else
                                        <td class="text-right">{{ number_format($producto->precio_lista_1,2,',','.') }}</td>
                                    @endif
                                    @if ($producto->unidades_x_bulto == 0)
                                        <td></td>
                                    @else
                                        <td class="text-right">{{ $producto->unidades_x_bulto }}</td>
                                    @endif
                                    @if ($producto->cajas_x_bulto == 0)
                                        <td></td>
                                    @else
                                        <td class="text-right">{{ $producto->cajas_x_bulto }}</td>
                                    @endif
                                    @if ($producto->unidades_x_caja == 0)
                                        <td></td>
                                    @else
                                        <td class="text-right">{{ $producto->unidades_x_caja }}</td>
                                    @endif
                                    @if ($producto->estado == 1)
                                        <td class="text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                                                Activo
                                            </span>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                                Baja
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-3 py-1 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('producto.edit', $producto) }}" 
                                            class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-3 py-1">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginacion ... -->
                <div class="mx-7 mt-2 mb-3">
                    {{ $productos->links() }}
                </div>

                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 mb-3">
                    <x-link-salir href="/inicio">
                        Salir
                    </x-link-salir>
                </div>
        </div>
    </div>

    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            var __sessionStatusMsj = "",
                __filtros = undefined,
                __proveedores = undefined;

            @if(session('status'))
                __sessionStatusMsj = "{{ session('status') }}";
            @endif

            @if($filtros)
                __filtros = @json($filtros);
                __proveedores = @json($proveedores);
            @endif
        </script>
        <script src="{{ asset('js/productos/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>
