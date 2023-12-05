<x-app-layout>
    <x-slot name="title">Heladerías - Caja Movimientos</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-8">
                <div class="mt-6 text-gray-700 text-3xl font-bold">
                    Movimientos de caja
                </div>
                <div class="mt-10 text-blue-600 text-base font-semibold">
                    <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }}</p>
                </div>
            </div>
            <div>
                <hr class="mt-2 mb-5 mx-4 border-gray-500">
            </div>
            <div class="flex flex-col"">
                <div class="bg-white shadow rounded-md overflow-auto mt-6 mx-7">
                    <table class="min-w-full md:rounded-lg">
                        @include('caja.thead-table-index')
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($movims)
                                @foreach ($movims as $mov)
                                    @if ($mov->tipo_comprobante_id == 4)
                                        <tr class="hover:bg-gray-100 text-sm font-bold bg-red-100 text-red-800">
                                    @else
                                        <tr class="hover:bg-gray-100 text-sm">
                                    @endif
                                    <td class="text-center">{{ date('d/m/y', strtotime($mov->fecha_hora)) }} {{ date('H:i', strtotime($mov->fecha_hora)) }}</td>
                                    <td class="text-center">{{ $mov->caja_nro }}</td>
                                    <td class="text-center">{{ $mov->id }}</td>
                                    <td class="text-center">{{ $mov->concepto }}</td>
                                    <!-- Columna ingresos -->
                                    @if ($mov->tipo_comprobante_id == 2 || $mov->tipo_comprobante_id == 5)
                                        <td class="text-right pr-1">$ {{ number_format($mov->importe, 2, ',', '.') }}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <!-- Columna egresos -->
                                    @if ($mov->tipo_comprobante_id == 4)
                                        <td class="text-right pr-2">$ -{{ number_format($mov->importe, 2, ',', '.') }}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    {{-- <td class="text-center py-1">
                                        @if ($mov->estado == 1)
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-700 rounded-full">
                                                Activo
                                        @else
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-700 rounded-full">
                                                Anulado
                                        @endif
                                        </span>
                                    </td> --}}
                                    {{-- <td class="px-2 py-1 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('caja_movimiento.edit', $mov) }}" 
                                           class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-2 py-1">
                                            Ver
                                        </a>
                                    </td> --}}
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 my-5">
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