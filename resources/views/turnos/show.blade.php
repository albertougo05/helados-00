<x-app-layout>
    <x-slot name="title">Heladerías - Turno de caja</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="flex justify-between px-8">
                <div class="pt-4 text-gray-700 text-3xl font-bold">
                    Turno de Caja
                </div>
                <div class="mt-6 flex">
                    <p class="text-base font-semibold pr-2">Sucursal: </p>
                    <p class="text-blue-600 text-base font-bold">{{ $sucursal->nombre }}</p>
                </div>
            </div>
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <div id="formTurno">
                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-1">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="id" :value="__('Id')"/>
                        <x-input id="id"
                                name="id"
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
                                value="{{ $apertura_fecha_hora }}" 
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
                    <div class="col-span-3">   <!-- Row 2 Col 1-3 -->
                        <x-label for="observaciones" :value="__('Observaciones')" />
                        <x-input type="text"
                                    id="observaciones"
                                    value="{{ $turno->observaciones }}"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9" 
                                    readonly />
                    </div>
                    <div>   <!-- Row 2 Col 4 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <x-select id="estado" name="estado" class="w-full h-9 mt-0" disabled>
                            <x-slot name="options">
                                <option value="1" @if($turno->estado == 1)selected @endif>
                                    Normal
                                </option>
                                <option value="0" @if($turno->estado == 0)selected @endif>
                                    Anulado
                                </option>
                            </x-slot>
                        </x-select>
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
                    <hr class="my-6 mx-4 border-gray-400">
                </div>
                <!-- GRID 3 / 4 columnas -->
                <div class="grid overflow-auto grid-cols-4 gap-3 px-6 pb-1">
                    <div>   <!-- Row 3 Col 1 -->
                        <x-label for="cierre_fecha" :value="__('Fecha cierre')" class="mb-0"/>
                        <x-input id="cierre_fecha"
                                class="h-9 w-full text-right mb-1" 
                                value="{{ $cierre_fecha }}"
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 3 Col 2 -->
                        <x-label for="cierre_hora" :value="__('Hora cierre')" class="mb-0"/>
                        <x-input id="cierre_hora"
                            type="text"
                            value="{{ $cierre_hora }}"
                            class="h-9 w-full text-right mb-1"
                            readonly />
                    </div>
                    @if ($cierre_fecha == '')
                        <div class="flex flex-col">
                            <input type="text" 
                                   class="mt-4 rounded shadow-sm h-8 bg-red-500 border-red-700 text-white font-bold text-center "
                                   disabled 
                                   value="Turno aún abierto !!">
                        </div>
                        <div></div>
                    @endif
                </div>
                <!-- GRID 4 / 4 columnas -->
                <div class="grid overflow-auto grid-cols-4 gap-3 px-6 pb-1">
                    <div>   <!-- Row 4 Col 1 -->
                        <x-label for="venta_total" :value="__('Venta total')" class="mb-0"/>
                        <input id="venta_total"
                                name="venta_total"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->venta_total }}"
                                @else
                                    value="{{ $totales['venta'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 4 Col 2 -->
                        <x-label for="efectivo" :value="__('Efectivo')" class="mb-0"/>
                        <input id="efectivo"
                                name="efectivo"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->efectivo }}"
                                @else
                                    value="{{ $totales['efectivo'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 4 Col 3 -->
                        <x-label for="tarjeta_credito" :value="__('Tarjetas crédito')" class="mb-0"/>
                        <input id="tarjeta_credito"
                                name="tarjeta_credito"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->tarjeta_credito }}"
                                @else
                                    value="{{ $totales['tarj_cred'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 4 Col 4 -->
                        <x-label for="tarjeta_debito" :value="__('Tarjetas débito')" class="mb-0"/>
                        <input id="tarjeta_debito"
                                name="tarjeta_debito"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->tarjeta_debito }}"
                                @else
                                    value="{{ $totales['tarj_debi'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                </div>
                <!-- GRID 5 / 4 columnas -->
                <div class="grid overflow-auto grid-cols-4 gap-3 px-6 pb-1">
                    <div>   <!-- Row 5 Col 1 -->
                        <x-label for="cuenta_corriente" :value="__('Cta. Corriente')" class="mb-0"/>
                        <input id="cuenta_corriente"
                                name="cuenta_corriente"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->cuenta_corriente }}"
                                @else
                                    value="{{ $totales['cta_cte'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 5 Col 2 -->
                        <x-label for="otros" :value="__('Transferencia')" class="mb-0"/>
                        <input id="otros"
                                name="otros"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->otros }}"
                                @else
                                    value="{{ $totales['transfer'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 5 Col 3 -->
                        <x-label for="egresos" :value="__('Egresos')" class="mb-0"/>
                        <input id="egresos"
                                name="egresos"
                                @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->egresos }}"
                                @else
                                    value="{{ $totales['egresos'] }}"
                                @endif
                                class="h-9 w-full bg-red-100 text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 5 Col 4 -->
                        <x-label for="ingresos" :value="__('Ingresos')" class="mb-0"/>
                        <input id="ingresos"
                                name="ingresos"
                            |   @if($turno->cierre_fecha_hora)
                                    value="{{ $turno->ingresos }}"
                                @else
                                    value="{{ $totales['ingresos'] }}"
                                @endif
                                class="h-9 w-full text-right mb-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" 
                                readonly />
                    </div>
                </div>
                <!-- GRID 6 / 4 columnas -->
                <div class="grid overflow-auto grid-cols-4 gap-3 px-6 pb-3">
                <div>   <!-- Row 6 Col 1 -->
                        <x-label for="caja" :value="__('Caja')" class="mb-0"/>
                        <x-input id="caja"
                                value="{{ $turno->caja }}"
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                <div>   <!-- Row 6 Col 2 -->
                        <x-label for="arqueo" :value="__('Arqueo')" class="mb-0"/>
                        <x-input id="arqueo"
                                value="{{ $turno->arqueo }}"
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 6 Col 2 -->
                        <x-label for="diferencia" :value="__('Diferencia')" class="mb-0"/>
                        <x-input id="diferencia"
                                value="{{ $turno->diferencia }}"
                                class="h-9 w-full text-right mb-1 font-semibold" 
                                type="text" 
                                readonly />
                    </div>
                </div>
                <div>
                    <hr class="my-4 mx-4 border-gray-400">
                </div>
                {{-- <div class="flex justify-between">
                    <div class="ml-4">
                        <p class="pl-2">
                            Cantidad de comprobantes: {{ $comprobantes['cantidad'] }}
                        </p>
                        <p class="pl-10">
                            Desde: {{$comprobantes['f_desde']}} a las {{$comprobantes['h_desde']}}
                        </p>
                        <p class="pl-10">
                            Hasta: {{ $comprobantes['f_hasta'] }} a las {{$comprobantes['h_hasta']}}
                        </p>
                    </div>
                    <div class="pt-8 mr-6">
                        <button class="focus:outline-none text-white text-base py-1 px-5 rounded bg-green-600 hover:bg-green-700 hover:shadow-md flex items-center" 
                            id="btnVerComprobs">
                            Ver comprobantes
                        </button>
                    </div>
                </div>
                <div>   <!-- Línea y botones confirma / salir  -->
                    <hr class="mb-6 mt-4 mx-4 border-gray-400">
                </div> --}}

                <div class="flex justify-end mt-0 mb-6 mr-6">
                    @if ($cierre_fecha != '')
                        <x-button class="mr-4" id="btnEnviaEmail">
                            Envia e-mail
                        </x-button>
                        <x-button class="mr-4" id="btnImprime">
                            Imprime cierre
                        </x-button>
                    @endif
                    <div>
                        <x-link-salir href="{{ route('turnos.informe') }}">
                            Volver
                        </x-link-salir>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('turnos.modal-comprobs-ventas')
    @include('turnos.modal-spin-buscando')

    @push('scripts')
        <script>
            // Variables globales
            const TURNO = {
                _cierre_fecha_hora: "{{ $turno->cierre_fecha_hora }}",
                _turno_id: "{{ $turno->id }}",
                _sucursal_id: "{{ $sucursal_id }}",
                _caja_nro: "{{ $turno->caja_nro }}",
                _comprobantes: [],
                _pathBuscarComprobs: "{{ route('turno.comprobantesvtas') }}",
                _turno: @json($turno),
                _pathToEmail: "{{ $pathToEmail }}",
                _impresora: "{{ $impresora }}",
                _env: "{{ $env }}"
            };

        </script>
        <script src="{{ asset('js/turnos/show.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>