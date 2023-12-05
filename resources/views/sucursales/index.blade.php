<x-app-layout>
    <x-slot name="title">Heladerías - Sucursales</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Sucursales
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>
            <div class="flex flex-col"">
                @if(Auth::user()->perfil_id == 1)
                    <div class="flex justify-end px-8">
                        <a href="{{ route('sucursal.create') }}" class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nueva
                        </a>
                    </div>
                @endif
                <div class="bg-white shadow rounded-md overflow-auto mt-4 mx-7">
                    <table class="min-w-full md:rounded-lg">
                        <thead class="bg-indigo-800 text-gray-200">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-center">Id</th>
                                <th scope="col" class="px-2 py-2">Nombre</th>
                                <th scope="col" class="px-2 py-2">Dirección</th>
                                <th scope="col" class="px-2 py-2">Localidad</th>
                                <th scope="col" class="px-2 py-2">Provincia</th>
                                <th scope="col" class="px-2 py-2 text-center w-14">Estado</th>
                                <th scope="col" class="relative px-2 py-2 w-16">
                                    <span class="sr-only">Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($sucursales as $suc)
                                <tr class="hover:bg-gray-100">
                                    <td class="text-center">{{ $suc->id }}</td>
                                    <td>{{ $suc->nombre }}</td>
                                    <td class="truncate">{{ $suc->direccion }}</td>
                                    <td>{{ $suc->localidad }}</td>
                                    <td>{{ $suc->provincia }}</td>
                                    @if ($suc->estado == 1)
                                        <td class="text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                                                Activa
                                            </span>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                                Baja
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('sucursal.edit', $suc) }}" 
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
                <div class="mx-7 mt-1 mb-6">
                    {{ $sucursales->links() }}
                </div>

                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 mb-5">
                    <x-link-salir href="{{ route('dashboard') }}">
                        Salir
                    </x-link-salir>
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

        </script>
    @endpush

</x-app-layout>
