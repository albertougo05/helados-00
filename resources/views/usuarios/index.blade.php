<x-app-layout>

    <x-slot name="title">Heladerías - Usuarios</x-slot>

    <div class="flex mt-12">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Usuarios
            </div>
            <div>
                <hr class="my-6 mx-4 border-gray-500">
            </div>

            <div class="flex flex-col">

                <div class="flex justify-between px-8">
                    <form class="relative">
                        <svg width="20" height="20" fill="currentColor" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                        </svg>
                        <input class="focus:border-light-blue-500 focus:ring-1 focus:ring-light-blue-500 focus:outline-none w-64 text-sm text-black placeholder-gray-500 border border-gray-200 rounded-md py-2 pl-10" type="text" aria-label="Filtro" placeholder="Buscar" autofocus />
                    </form>

                    <a href="/usuario/create" class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h- w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nuevo
                    </a>
                </div>

                <div class="bg-white shadow rounded-md overflow-hidden mt-6 mx-7">
                    <table class="min-w-full md:rounded-lg">
                            <thead class="bg-indigo-800 text-gray-100 font-semibold">
                                <tr>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 tracking-wider">Usuario (Sucursal)</th>
                                    <th scope="col" class="px-6 py-3 text-center tracking-wider">Perfil</th>
                                    <th scope="col" class="px-6 py-3 text-center tracking-wider">Estado</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($usuarios as $usuario)
                                    <tr class="hover:bg-gray-100">
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $usuario->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $usuario->email }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <div class="ml-4">
                                                @if ($usuario->nombre_usuario)
                                                    @php
                                                        $suc_id = $usuario->sucursal_id - 1;
                                                    @endphp
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if ($usuario->sucursal_id == 0)
                                                            {{ $usuario->nombre_usuario }}
                                                        @else
                                                            {{ $usuario->nombre_usuario }} ({{ $sucursales[$suc_id]['nombre'] }})
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 text-sm text-center text-gray-500">
                                            @php
                                                $id = $usuario->perfil_id - 1;
                                            @endphp
                                            {{ $roles[$id]['label'] }}
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            @if ($usuario->estado == 1)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-900">
                                                    Activo
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-900">
                                                    Baja
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('usuario.edit', $usuario) }}" class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-3 py-1">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                </div>

                <!-- Paginacion ... -->
                <div class="mx-7 mt-1 mb-6">
                    {{ $usuarios->links() }}
                </div>

                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 mb-5">
                    <a href="{{ route('dashboard') }}" 
                        class="px-5 py-1 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 hover:text-gray-100 focus:outline-none focus:bg-gray-700">
                        Salir
                    </a>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
