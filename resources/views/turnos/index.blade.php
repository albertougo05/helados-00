<x-app-layout>
    <x-slot name="title">Heladerías - Turnos</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Abrir/Cerrar Turnos de caja
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>
            <div class="flex flex-col"">
                <div class="flex justify-end px-8">
                    <a @if ($no_puede_abrir_turno)
                            href="#" 
                            class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-700 hover:shadow-md flex items-center opacity-50 cursor-not-allowed" 
                        @else
                            href="{{ route('turno.create') }}"                         
                            class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-700 hover:shadow-md flex items-center" 
                        @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Abrir turno
                    </a>
                </div>
                <div class="bg-white shadow rounded-md overflow-auto mt-4 mx-7">
                    <table class="min-w-full md:rounded-lg">
                        <thead class="bg-indigo-800 text-gray-200">
                            <tr>
                                <th scope="col" class="px-3 py-2">Sucursal</th>
                                <th scope="col" class="px-2 py-2">Caja</th>
                                <th scope="col" class="px-2 py-2">Turno</th>
                                <th scope="col" class="px-2 py-2">Apertura</th>
                                <th scope="col" class="px-2 py-2">Cierre</th>
                                <th scope="col" class="px-2 py-2">Usuario</th>
                                <th scope="col" class="py-2 text-center">Inicio</th>

                                {{-- <th scope="col" class="px-2 py-2">Arqueo Actual</th>
                                <th scope="col" class="px-2 py-2">Vta. Total</th>
                                <th scope="col" class="px-2 py-2">Efectivo</th>
                                <th scope="col" class="px-2 py-2">Debito</th>
                                <th scope="col" class="px-2 py-2">Cta.Cte.</th>
                                <th scope="col" class="px-2 py-2">Diferencia</th> --}}

                                <th scope="col" class="px-2 py-2 w-14">Estado</th>
                                <th scope="col" class="relative px-2 py-2 w-16">
                                    <span class="sr-only">Cerrar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($turnos as $turno)
                                <tr class="hover:bg-gray-100">
                                    <td class="text-center">{{ $turno['nombre'] }}</td>
                                    <td class="text-center">{{ $turno['caja_nro'] }}</td>
                                    <td class="text-center">{{ $turno['turno_sucursal'] }}</td>
                                    <td class="text-center">
                                        {{ Carbon\Carbon::parse($turno['apertura_fecha_hora'])->format('d/m/Y') }} 
                                        {{ Carbon\Carbon::parse($turno['apertura_fecha_hora'])->format('H:i') }}
                                    </td>
                                    @if($turno['cierre_fecha_hora'])
                                        <td class="text-center">
                                            {{ Carbon\Carbon::parse($turno['cierre_fecha_hora'])->format('d/m/Y') }} 
                                            {{ Carbon\Carbon::parse($turno['cierre_fecha_hora'])->format('H:i') }}
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td class="text-center">{{ $turno['name'] }}</td>
                                    <td class="text-right pr-1">$ {{ number_format($turno['saldo_inicio'], 2, ',', '.') }}</td>

                                    {{-- <td class="text-center">$ {{ number_format($turno['arqueo_parcial'], 2, ',', '.') }}</td>
                                    <td class="text-center">{{ $turno['venta_total'] }}</td>
                                    <td class="text-center">{{ $turno['efectivo'] }}</td>
                                    <td class="text-center">{{ $turno['tarjeta_debito'] }}</td>
                                    <td class="text-center">{{ $turno['cuenta_corriente'] }}</td>
                                    <td class="text-center">{{ $turno->['diferencia'] }}</td> --}}

                                    <td class="text-center">
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                                            @if ($turno['estado'] == 1)
                                                Activo
                                            @else
                                                Anulado
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('turno.edit', $turno['id']) }}"
                                            class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-3 py-1">
                                            Cerrar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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