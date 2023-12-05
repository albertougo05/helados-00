<x-app-layout>
    <x-slot name="title">Heladerías - Caja movimiento</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Movimiento de caja
            </div>
            @if (session('status'))
                <x-alert-success titulo="Resultado:" id="alert_success">
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <form id="formMovim"
                method="POST" 
                action="{{ route('caja_movimiento.update', $movim->id) }}"
                autocomplete="off">
                @method('PUT')
                @csrf

                {{-- <input type="hidden" name="sucursal_id" value="{{ $movim->sucursal_id ?? '' }}">
                <input type="hidden" name="usuario_id" value="{{ $movim->usuario_id }}">
                <input type="hidden" name="importe" id="importe"> --}}

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-1">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="tipo_comprobante_id" :value="__('Tipo de movimiento')" />
                        <select id="tipo_comprobante_id" 
                                class="h-8 mt-0 w-full pt-1 pl-3 pr-6 text-base placeholder-gray-600 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline"
                                disabled>
                            <option value="5"{{ $movim->tipo_comprobante_id == 5 ? ' selected' : '' }}>Ingreso</option>
                            <option value="4"{{ $movim->tipo_comprobante_id == 4 ? ' selected' : '' }}>Egreso</option>
                        </select>
                    </div>
                    <div>   <!-- Row 1 Col 2 -->
                        <x-label for="fecha_registro" :value="__('Fecha')" class="mb-0"/>
                        <x-input id="fecha_registro"
                                value="{{ $movim->fecha_registro }}" 
                                class="h-9 w-full mb-1" 
                                type="date" 
                                disabled />
                    </div>
                    <div>   <!-- Row 1 Col 3 -->
                        <x-label for="hora" :value="__('Hora')" class="mb-0"/>
                        <x-input id="hora"
                            type="text"
                            class="h-9 w-full text-right mb-1" 
                            disabled />
                    </div>
                    <div>   <!-- Row 1 Col 4 -->
                        <x-label for="caja_nro" :value="__('Caja Nro.')" />
                        <x-input id="caja_nro"
                            type="text"
                            value="{{ $movim->caja_nro }}"
                            class="h-9 w-full text-right mb-1" 
                            disabled />
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 5-6 -->
                        <x-label for="importe_1" :value="__('Importe')" class="mb-0"/>
                        <x-input id="importe_1"
                                value="{{ $movim->importe }}"
                                class="h-9 w-full text-right mb-1" 
                                type="text"
                                readonly />
                    </div>
                </div>
                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div class="col-span-3">   <!-- Row 2 Col 1-3 -->
                        <x-label for="concepto" :value="__('Concepto')" />
                        <input type="text"
                               name="concepto"
                               id="concepto"
                               value="{{ $movim->concepto }}"
                               autocomplete="off"
                               class="block mt-0 w-full h-9 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                               {{ $movim->turno_cierre_id > 0 ? ' readonly' : '' }} />
                    </div>
                    <div>   <!-- Row 2 Col 4 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <select id="estado" 
                                name="estado" 
                                class="h-8 mt-0 w-full pt-1 pl-3 pr-6 text-base placeholder-gray-600 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline"
                                {{ $movim->turno_cierre_id > 0 ? ' disabled' : '' }}>
                                <option value="1"{{ $movim->estado == 1 ? ' selected' : '' }}>
                                    Normal
                                </option>
                                <option value="0"{{ $movim->estado == 0 ? ' selected' : '' }}>
                                    Anulado
                                </option>
                        </select>
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 5/6 -->
                        <x-label for="usuario" :value="__('Usuario')" />
                        <x-input type="text"
                                id="usuario"
                                value="{{ $usuario }}"
                                autocomplete="off"
                                class="block mt-0 w-full h-9" 
                                disabled />
                    </div>
                </div>
                <!-- GRID 3 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div class="col-span-3">   <!-- Row 2 Col 1-3 -->
                        <x-label for="observaciones" :value="__('Observaciones')" />
                        <input type="text"
                               name="observaciones"
                               id="observaciones"
                               value="{{ $movim->observaciones }}"
                               autocomplete="off"
                               class="block mt-0 w-full h-9 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               {{ $movim->turno_cierre_id > 0 ? ' readonly' : '' }} />
                    </div>
                </div>
                <!-- GRID 4 -->
                <div class="pl-8">
                    <p class="font-semibold">
                        {{ $tipo_movimiento }}
                    </p>
                </div>
                <!-- GRID 4 - TABLA DETALLE -->
                @if($movim->tipo_comprobante_id == 1)
                    <div class="bg-white shadow rounded overflow-x-auto mt-1 mx-24">
                        <table class="table-fixed min-w-full md:rounded" id="tablaDetalle">
                            <thead class="text-sm bg-indigo-800 text-gray-200">
                                <tr>
                                    <th scope="col" class="py-1 text-center">Código</th>
                                    <th scope="col" class="py-1 text-center w-1/2">Descripción</th>
                                    <th scope="col" class="pr-1 py-1 text-right">Precio Unit.</th>
                                    <th scope="col" class="pr-1 py-1 text-right">Cant.</th>
                                    <th scope="col" class="pr-2 py-1 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200"
                                id="bodyTablaDetalle">
                                @foreach ($detalle_mov as $detalle)
                                    <tr class="hover:bg-gray-100">
                                        <td class="text-center">
                                            {{ $detalle->producto_id }}
                                        </td>
                                        <td class="text-left pl-1 w-1/2 overflow-ellipsis">
                                            {{ $detalle->descripcion }}
                                        </td>
                                        <td class="text-right pr-1">
                                            {{ number_format($detalle->precio_unitario,2,',','.') }}
                                        </td>
                                        <td class="text-right pr-1">
                                            {{ number_format($detalle->cantidad,2,',','.') }}
                                        </td>
                                        <td class="text-right pr-1">
                                            {{ number_format($detalle->subtotal,2,',','.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <!-- Línea y botones confirma / cancela  -->
                <div>
                    <hr class="mb-6 mt-3 mx-4 border-gray-400">
                </div>
                <div class="flex justify-between mt-0">
                    <div>
                        <div class="hidden" id="spinGuardando">
                            <div class="flex ml-10">
                                <div class="w-48 ml-6">
                                    <p>Guardando datos...</p>
                                </div>
                                <div class="w-32 mx-auto">
                                    <div style="border-top-color:transparent"
                                        class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-0 mb-6 mr-6">
                        <div>
                            @if ($movim->turno_cierre_id == 0)
                                <x-button class="mr-4" id="btnConfirma">
                                    Confirma
                                </x-button>
                                <x-link-cancel href="{{ route('caja_movimiento.edit', $movim->id) }}" class="mr-4">
                                    Cancelar
                                </x-link-cancel>
                            @endif
                            <x-link-salir href="{{ $prevUrl }}">
                                Volver
                            </x-link-salir>
                        </div>
                    </div>
                </div> 
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Variables globales
            const CAJA = { 
                _hayStatus: false,
                _fecha_hora: "{{ $movim->fecha_hora }}",
                _prevUrl: "{{ $prevUrl }}",
            };
            @if (session('status'))
                CAJA._hayStatus = true;
            @endif
        </script>
        <script src="{{ asset('js/caja/edit.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>