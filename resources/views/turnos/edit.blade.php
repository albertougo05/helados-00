<x-app-layout>
    <x-slot name="title">Heladerías - Cerrar Turno/Caja</x-slot>
    <div class="flex mt-6 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-6 pt-4 text-gray-700 text-3xl font-bold">
                Cerrar Turno/Caja
            </div>
            @if (session('status'))
                <x-alert-success titulo="Perfecto !" >
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <div>
                <hr class="my-1 mx-4 border-gray-400">
            </div>
            <form id="formTurno"
                method="POST" 
                action="{{ route('turno.update', $turno) }}"
                autocomplete="off">

                @method('PUT')
                @csrf

                <input type="hidden" name="sucursal_id" value="{{ $turno->sucursal_id }}">
                <input type="hidden" name="usuario_id" value="{{ $turno->usuario_id }}">
                <input type="hidden" name="cierre_fecha_hora" value="">
                <input type="hidden" name="efectivo" value="{{ $totales['efectivo'] }}">
                <input type="hidden" name="egresos" value="{{ $totales['egresos'] }}">
                <input type="hidden" name="ingresos" value="{{ $totales['ingresos'] }}">
                <input type="hidden" name="venta_total" value="{{ $totales['venta'] }}">
                <input type="hidden" name="caja" value="{{ $totales['caja_teorica'] }}">
                <input type="hidden" name="diferencia" value="0">
                <input type="hidden" name="estado" value="1">

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-6 gap-x-2 gap-y-1 px-6 pb-1">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="turno_sucursal" :value="__('Turno Nro.')"/>
                        <x-input id="turno_sucursal"
                                name="turno_sucursal"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $turno->turno_sucursal }}"
                                readonly />
                    </div>
                    <div>   <!-- Row 1 Col 2 -->
                        <x-label for="caja_nro" :value="__('Caja Nro.')" />
                        <x-input id="caja_nro"
                                name="caja_nro"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $turno->caja_nro }}"
                                readonly />
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 3 -->
                        <x-label for="apertura_fecha" :value="__('Apertura')" class="mb-0"/>
                        <x-input id="apertura_fecha"
                                value="{{ $fecha_hora_apertura }}" 
                                class="h-9 w-full mb-1 text-right" 
                                type="text" 
                                readonly />
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 4 -->
                        <x-label for="saldo_inicio" :value="__('Efectivo inicial')" class="mb-0"/>
                        <x-input id="saldo_inicio"
                                value="{{ $turno->saldo_inicio }}"
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                </div>
                <!-- GRID 2 / 6 columnas -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div class="col-span-4">   <!-- Row 2 Col 1-3 -->
                        <x-label for="observaciones" :value="__('Observaciones')" />
                        <x-input type="text"
                                    name="observaciones"
                                    id="observaciones"
                                    value="{{ $turno->observaciones }}"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9" />
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 5/6 -->
                        <x-label for="usuario" :value="__('Usuario que abrió')" />
                        <x-input type="text"
                                    id="usuario"
                                    value="{{ $usuario_turno }}"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9" 
                                    disabled />
                    </div>
                </div>
                <div>
                    <hr class="my-2 mx-4 border-gray-400">
                </div>
                <!-- GRID 3 / 4 columnas -->
                <div class="flex justify-center px-6 pb-1">
                    <div class="mr-4">   <!-- Row 3 Col 1 -->
                        <x-label for="cierre_fecha" :value="__('Fecha cierre')" class="mb-0"/>
                        <x-input id="cierre_fecha"
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                    <div class="ml-4">   <!-- Row 3 Col 2 -->
                        <x-label for="cierre_hora" :value="__('Hora cierre')" class="mb-0"/>
                        <x-input id="cierre_hora"
                            type="text"
                            class="h-9 w-full text-right mb-1" 
                            readonly />
                    </div>
                </div>
                <!-- GRID 4 / 2 columnas -->
                <div class="grid overflow-visible grid-cols-2 grid-rows-1 gap-1">
                    <!-- column datos -->
                    <div class="flex flex-col mx-auto mt-2">
                        <div class="w-48 mr-2">   <!-- Row 1 Col 1 -->
                            <x-label for="tarjeta_credito" :value="__('Tarjetas crédito')" class="mb-0"/>
                            <x-input id="tarjeta_credito"
                                    name="tarjeta_credito"
                                    value="{{ $totales['tarj_cred'] }}"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>
                        <div class="w-48 mr-2">   <!-- Row 2 Col  -->
                            <x-label for="tarjeta_debito" :value="__('Tarjetas débito')" class="mb-0"/>
                            <x-input id="tarjeta_debito"
                                    name="tarjeta_debito"
                                    value="{{ $totales['tarj_debi'] }}"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>
                        <div class="w-48 mr-2">   <!-- Row 3 Col 1 -->
                            <x-label for="cuenta_corriente" :value="__('Cta. Corriente')" class="mb-0"/>
                            <x-input id="cuenta_corriente"
                                    name="cuenta_corriente"
                                    value="{{ $totales['cta_cte'] }}"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>
                        <div class="w-48 mr-2">   <!-- Row 4 Col 1 -->
                            <x-label for="otros" :value="__('Transferencia')" class="mb-0"/>
                            <x-input id="otros"
                                    name="otros"
                                    value="{{ $totales['transfer'] }}"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>
{{--                       <div class="w-48 mr-2">   <!-- Row 4 Col 1 -->
                            <x-label for="ingresos" :value="__('Ingresos')" class="mb-0"/>
                            <x-input id="ingresos"
                                    name="ingresos"
                                    value="{{ $totales['ingresos'] }}"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>
                        <div class="w-48 mr-2">   <!-- Row 4 Col 1 -->
                            <x-label for="egresos" :value="__('Egresos')" class="mb-0"/>
                            <x-input id="egresos"
                                    name="egresos"
                                    value="{{ $totales['egresos'] }}"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>  --}}
                        <div class="w-48 mr-2">   <!-- Row 5 Col 1 -->
                            <x-label for="arqueo" :value="__('Total arqueo')" class="mb-0"/>
                            <x-input id="arqueo"
                                    name="arqueo"
                                    class="h-8 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>
{{--                         <div class="w-48 mr-2">   <!-- Row 6 Col 1 -->
                            <x-label for="total_venta" :value="__('Total venta')" class="mb-0"/>
                            <x-input id="total_venta"
                                    name="total_venta"
                                    class="h-9 w-full text-right mb-1" 
                                    type="text" 
                                    readonly />
                        </div>  --}}
                    </div>
                    <!-- column grilla billetes -->
                    <div class="mr-4 mt-0 px-6">
                        <p class="text-sm ml-2 my-1">Arqueo de caja</p>
                        <div class="my-0 mx-1 shadow-md overflow-auto rounded-md bg-white">
                            <table class="table-fixed min-w-full divide-x divide-gray-200" 
                                id="tablaBilletes">
                                <thead class="bg-indigo-800">
                                    <tr class="text-sm font-semibold text-gray-100">
                                        <th scope="col" width="20%"
                                            class="px-1 py-2 text-center">
                                            Cantidad
                                        </th>
                                        <th scope="col" class="pr-3 py-2 text-right" width="35%">
                                            Importe
                                        </th>
                                        <th scope="col" class="pr-5 py-2 text-right" width="45%">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="bodyBilletes"
                                    class="bg-white divide-y divide-x divide-gray-200 text-xs">
                                    @foreach ($billetes as $bill)
                                        <tr>
                                            <td>
                                                <input onkeyup="TURNO._onkeyUpBilletes(this);"
                                                    type="text" 
                                                    class="h-6 w-24 rounded border border-gray-300 mx-1 px-2 divide-x divide-gray-200 text-xs text-center focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    id="bill-{{ $bill->id }}">
                                            </td>
                                            <td class="h-6 text-right divide-x divide-gray-500">
                                                {{ number_format($bill->importe,2,',','.') }}
                                            </td>
                                            <td>
                                                <div class="h-6 pr-3 pt-1 text-xs text-right"
                                                    id="tot-{{ $bill->id }}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div>   <!-- Línea y botones confirma / salir  -->
                    <hr class="my-3 mx-4 border-gray-400">
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
                    <div class="flex justify-end mt-0 mb-4 mr-6">
                        <div>
                            <x-button class="mr-4" id="btnConfirma">
                                Confirma
                            </x-button>
                            <x-link-salir href="{{ route('turno.index') }}">
                                Salir
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
            const TURNO = { 
                _turno_id: {{ $turno->id }},
                _turno_sucursal: {{ $turno->turno_sucursal }},
                _fecha_actual: "{{ $fecha_actual }}",
                _sucursal_id: {{ $sucursal_id }},
                _usuario_id: {{ $usuario->id }},
                _caja_nro: {{ $turno->caja_nro }},
                _billetes: @json($billetes),
                _pathUpdateTurno: "{{ route('turno.update', $turno->id) }}",
                _totales: @json($totales),
            }

        </script>
        <script src="{{ asset('js/turnos/edit.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>