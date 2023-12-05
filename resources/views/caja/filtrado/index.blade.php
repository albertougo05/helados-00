<x-app-layout>
    <x-slot name="title">Heladerías - Caja Movimientos</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Movimientos de caja
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>
            <div class="flex flex-col"">
                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 gap-3 px-7 pb-1">
                    <div class="col-span-2">   <!-- Row 1 Col 1-2 -->
                        <x-label for="sucursal_id" :value="__('Sucursal')" />
                        @if ( $user_perfil !== 'admin')
                            <x-select id="sucursal_id" class="w-full h-9 mt-0" disabled>
                                <x-slot name="options">
                                    @foreach ($sucursales as $suc)
                                        <option value="{{ $suc->id }}">
                                            {{ $suc->nombre }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        @else
                            <x-select id="sucursal_id" class="w-full h-9 mt-0">
                                <x-slot name="options">
                                    @foreach ($sucursales as $suc)
                                    <option value="{{ $suc->id }}" {{ $suc->id === 1 ? 'selected' : '' }}>
                                        {{ $suc->nombre }}
                                    </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        @endif
                    </div>
                    <div>   <!-- Row 1 Col 3 -->
                        <x-label for="caja_nro" :value="__('Caja Nro.')" />
                        <x-select id="caja_nro" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                @foreach ($ptos_vta as $punto)
                                    <option value="{{ $punto['id'] }}" {{ $caja_nro == $punto['id'] ? 'selected' : '' }}>
                                        {{ $punto['texto'] }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 1 Col 4 -->
                        <x-label for="desde" :value="__('Desde')" class="mb-0"/>
                        <x-input id="desde"
                                value="{{ $desde }}"
                                class="h-9 w-full mb-1" 
                                type="date" />
                    </div>
                    <div>   <!-- Row 1 Col 5 -->
                        <x-label for="hasta" :value="__('Hasta')" class="mb-0"/>
                        <x-input id="hasta"
                                value="{{ $hasta }}"
                                class="h-9 w-full mb-1" 
                                type="date" />
                    </div>
                </div>  <!-- / end GRID 1 -->

                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-5 gap-3 px-7 pb-1">
                    <div class="col-span-2">   <!-- Row 2 Col 1-2 -->
                        <x-label for="usuario_id" :value="__('Usuario')" />
                        <select id="usuario_id" 
                            class="w-full h-8 mt-0 pt-0.5 pl-3 pr-6 text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline"
                            {{ $user_perfil !== 'admin' ? 'selected' : '' }}>
                            @foreach ($usuarios as $user)
                                <option value="{{ $user->id }}" {{ $user->id === $usuario_id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label for="turno_id" :value="__('Nro. de turno')" class="mb-0 numero"/>
                        <x-input id="turno_id"
                            type="text"
                            value="{{ $turno_cierre }}"
                            class="h-9 w-full text-right mb-1" />
                    </div>
                    <div></div>
                    <div class="pt-6">
                        <button id="btnBuscar"
                            class="w-full focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Buscar movim.
                        </button>
                    </div>
                </div>   <!-- / end GRID 2 -->
                <!-- Tabla de movimientos filtrados -->
                <div class="bg-white shadow rounded-md overflow-auto mt-4 mx-7">
                    <table class="w-full md:rounded-lg">
                        @include('caja.thead-table-index')
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($movims)
                                @foreach ($movims as $mov)
                                    @if ($mov->tipo_comprobante_id == 2 || $mov->tipo_comprobante_id == 4)
                                        <tr class="hover:bg-gray-100 text-sm font-semibold bg-red-100 text-red-800">
                                    @else
                                        <tr class="hover:bg-gray-100 text-sm">
                                    @endif
                                    <td class="text-center">{{ date('d/m/y', strtotime($mov->fecha_hora)) }} {{ date('H:i', strtotime($mov->fecha_hora)) }}</td>
                                    <td class="text-left">{{ $mov->nombre }}</td>
                                    <td class="text-left">{{ $mov->name }}</td>
                                    <td class="text-center">{{ $mov->caja_nro }}</td>
                                    <td class="text-center">{{ $mov->turno_cierre_id == 0 ? '' : $mov->turno_cierre_id }}</td>
                                    @if ($mov->tipo_comprobante_id == 2)
                                        <td class="text-center">Nota Crédito</td>
                                    @elseif ($mov->tipo_comprobante_id == 3)
                                        <td class="text-center">Nota Débito</td>
                                    @elseif ($mov->tipo_comprobante_id == 4)
                                        <td class="text-center">Egreso</td>
                                    @elseif ($mov->tipo_comprobante_id == 5)
                                        <td class="text-center">Ingreso</td>
                                    @else
                                        <td class="text-center">B {{ $mov->codigo_comprob }}-{{ $mov->nro_comprobante }}</td>
                                    @endif
                                    <td class="text-center">{{ $mov->concepto }}</td>
                                    <!-- Columna MODIFICA CAJA -->
                                    @if ($mov->total_efectivo > 0 || $mov->tipo_comprobante_id == 4 || $mov->tipo_comprobante_id == 5)
                                        @if ($mov->tipo_comprobante_id == 2 || $mov->tipo_comprobante_id == 4)
                                            <td class="text-right pr-1">$ -{{ number_format($mov->importe, 2, ',', '.') }}</td>
                                        @else
                                            <td class="text-right pr-1">$ {{ number_format($mov->importe, 2, ',', '.') }}</td>
                                        @endif
                                    @else
                                        <td class="text-right pr-1"></td>
                                    @endif
                                    <!-- Columna que NO MODIFICA CAJA -->
                                    @if ($mov->total_efectivo == 0 && $mov->tipo_comprobante_id != 4 && $mov->tipo_comprobante_id != 5)
                                        @if ($mov->tipo_comprobante_id == 2 || $mov->tipo_comprobante_id == 4)
                                            <td class="text-right pr-1">$ -{{ number_format($mov->importe, 2, ',', '.') }}</td>
                                        @else
                                            <td class="text-right pr-1">$ {{ number_format($mov->importe, 2, ',', '.') }}</td>
                                        @endif
                                    @else
                                        <td class="text-right pr-1"></td>
                                    @endif
                                    <td class="text-center">
                                        @if ($mov->estado == 1)
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                                                Activo
                                        @else
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-red-500 rounded-full">
                                                Anulado
                                        @endif
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('caja_movimiento.edit', $mov) }}" 
                                           class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-2 py-1">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- Paginacion ... -->
                <div class="mx-7 mt-1 mb-0">
                    {{ $movims->links() }}
                </div>
                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 my-4">
                    <x-link-cancel href="{{ route('caja_movimiento.index') }}" class="mr-4">
                        Cancelar
                    </x-link-cancel>
                    <x-link-salir href="{{ route('dashboard') }}">
                        Salir
                    </x-link-salir>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const _pathMovsFiltrado = "{{ route('movimientos_caja.filtrado') }}";
            const _pathBuscaCajas = "{{ route('movimientos_caja.cajas_sucursal') }}";

            @if (session('status'))
                const hayStatus = true;
                const mensajeStatus = "{{ session('status') }}";
            @else
                const hayStatus = false;
            @endif
        </script>
        <script src="{{ asset('js/caja/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>