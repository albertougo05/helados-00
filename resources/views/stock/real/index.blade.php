<x-app-layout>
    <x-slot name="title">Heladerías - Stock Real</x-slot>

    <div id="scrollUp"></div>

    <div class="flex mt-4 mb-3">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between px-6">
                <div class="mt-5 text-gray-700 text-3xl font-bold">
                    {{ $titulo }}
                </div>
                <div class="mt-8 mr-1 text-blue-600 text-base font-semibold">
                    <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal }}</p>
                </div>
            </div>
            <div>
                <hr class="mb-4 mt-1 mx-4 border-gray-500">
            </div>

            <div class="flex flex-col" x-data="{ open: false }">
                <div class="flex justify-end px-8">
                    <a href="{{ route('stock.real.planilla.create') }}" class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-500 hover:bg-green-600 hover:shadow-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nueva
                    </a>
                </div>
                <div class="bg-white shadow rounded-md overflow-auto mt-3 mx-7">
                    <table class="min-w-full md:rounded-lg">
                        <thead class="bg-indigo-800 text-gray-200">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-right">Id</th>
                                <th scope="col" class="py-2 text-center">Fecha toma</th>
                                <th scope="col" class="px-2 py-2 text-center">Hora toma</th>
                                <th scope="col" class="px-2 py-2">Tipo toma stock</th>
                                <th scope="col" class="px-2 py-2">Detalle</th>
                                <th scope="col" class="px-2 py-2 text-center">Fecha stock final</th>
                                <th scope="col" class="px-2 py-2 text-center">Hora stock final</th>
                                <th scope="col" class="relative px-2 py-2 w-16">
                                    <span class="sr-only">Ver / Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($planillas as $plan)
                                <tr class="hover:bg-gray-100">
                                    <td class="text-right">{{ $plan->id }}</td>
                                    <td class="text-center">{{ date('d/m/y', strtotime($plan->fecha_toma_stock)) }}</td>
                                    <td class="text-center">{{ date('H:i', strtotime($plan->hora_toma_stock)) }}</td>
                                    <td class="text-center">{{ ucfirst($plan->tipo_toma_stock) }}</td>
                                    <td class="text-left">{{ $plan->detalle }}</td>
                                    @if ($plan->fecha_periodo_stock_final == null || $plan->fecha_periodo_stock_final == '')
                                        <td></td>
                                    @else
                                        <td class="text-center">{{ date('d/m/y', strtotime($plan->fecha_periodo_stock_final)) }}</td>
                                    @endif
                                    @if ($plan->hora_periodo_stock_final == null || $plan->hora_periodo_stock_final == '00:00:00')
                                        <td></td>
                                    @else
                                        <td class="text-center">{{ date('H:i', strtotime($plan->hora_periodo_stock_final)) }}</td>
                                    @endif
                                    <td class="px-3 py-1 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('stock.real.planilla.edit', $plan->id) }}" 
                                            class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-3 py-1">
                                            Ver / Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginacion ... -->
                <div class="mx-7 mt-2 mb-3">
                    {{ $planillas->links() }}
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
        <script>
            //  
        </script>
    @endpush

</x-app-layout>
