<x-app-layout>
    <x-slot name="title">Heladerías - Caja movimiento</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="flex justify-between px-8">
                <div class="mt-6 text-gray-700 text-3xl font-bold">
                    Nuevo movimiento de caja
                </div>
                <div class="mt-10 text-blue-600 text-base font-semibold">
                    <p>
                        <span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }} - 
                        <span class="text-gray-800">Turno: </span>{{ $turno_nro }}
                    </p>
                </div>
            </div>
            <div>
                <hr class="my-2 mx-4 border-gray-400">
            </div>
            @if (session('status'))
                <x-alert-success titulo="Resultado:" id="alert_success">
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <form id="formMovim"
                method="POST" 
                action="{{ route('caja_movimiento.store') }}"
                autocomplete="off">
                @csrf
                <input type="hidden" name="crea_mov" value="1">
                <input type="hidden" name="sucursal_id" value="{{ $sucursal_id ?? '' }}">
                <input type="hidden" name="usuario_id" value="{{ $usuario->id }}">
                <input type="hidden" name="concepto" id="concepto">
                <input type="hidden" name="fecha_hora" id="fecha_hora">
                <input type="hidden" name="fecha_registro" id="fecha_registro">
                <input type="hidden" name="forma_pago_id" value=0>
                <input type="hidden" name="tipo_movim_id" value=0>
                <input type="hidden" name="importe" id="importe">
                <input type="hidden" name="nro_comprobante" value="0">
                <input type="hidden" name="estado" value="1">

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-1">
                    <div class="col-span-2">   <!-- Row 1 Col 1/2 -->
                        <x-label for="tipo_comprobante_id" :value="__('Tipo de movimiento')" />
                        <x-select id="tipo_comprobante_id" name="tipo_comprobante_id" class="w-full h-9 mt-0" autofocus>
                            <x-slot name="options">
                                <option value="0">
                                    Seleccione un movimiento...
                                </option>
                                @foreach ($tipos_movim as $tipo)
                                    <option value="{{ $tipo['tipo_movim_id'] }}"
                                            data-idtipomovim="{{ $tipo['id'] }}">
                                        {{ $tipo['descripcion'] }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 1 Col 3 -->
                        <x-label for="caja_nro" :value="__('Caja Nro.')" />
                        <x-select id="caja_nro" name="caja_nro" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                @foreach ($ptos_vta as $punto)
                                    <option value="{{ $punto['id'] }}">
                                        {{ $punto['texto'] }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 1 Col 2 -->
                        <x-label for="fecha" :value="__('Fecha')" class="mb-0"/>
                        <x-input id="fecha"
                                :value="old('fecha')" 
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 1 Col 3 -->
                        <x-label for="hora" :value="__('Hora')" class="mb-0"/>
                        <x-input id="hora"
                            type="text"
                            :value="old('hora')"
                            class="h-9 w-full text-right mb-1" 
                            disabled />
                    </div>
                    <div>   <!-- Row 1 Col 5-6 -->
                        <x-label for="importe_1" :value="__('Importe')" class="mb-0"/>
                        <x-input id="importe_1"
                                :value="old('importe_1')"
                                class="h-9 w-full text-right font-bold mb-1 text-white" 
                                type="text"/>
                    </div>
                </div>

                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div class="col-span-3">   <!-- Row 2 Col 1-3 -->
                        <x-label for="observaciones" :value="__('Observaciones / Detalle')" />
                        <x-input type="text"
                                name="observaciones"
                                id="observaciones"
                                :value="old('observaciones')"
                                autocomplete="off"
                                class="block mt-0 w-full h-9" />
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 5/6 -->
                        <x-label for="empleado" :value="__('Usuario')" />
                        <x-input type="text"
                                id="empleado"
                                :value="old('empleado')"
                                autocomplete="off"
                                class="block mt-0 w-full h-9" 
                                disabled />
                    </div>
                    <div class="flex justify-center pt-6">
                        <div id="descrip_tipo_mov"></div>
                    </div>
                </div>

                <!-- Línea y botones confirma / cancela  -->
                <div>
                    <hr class="my-6 mx-4 border-gray-400">
                </div>
                <div class="flex justify-between mt-0">
                    <div>
                        <div class="hidden" id="spinGuardando">
                            <div class="flex ml-10">
                                <div class="w-60 ml-6">
                                    <p>Imprime y guarda datos...</p>
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
                            <x-button class="mr-4" id="btnConfirma">
                                Confirma
                            </x-button>
                            <x-link-cancel href="{{ route('caja_movimiento.create') }}" class="mr-4">
                                Cancelar
                            </x-link-cancel>
                            <x-link-salir href="{{ route('dashboard') }}">
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
            const CAJA = { 
                _new_id: "{{ $new_id }}",
                _sucursal_id: {{ $sucursal_id }},
                _turno_nro: "{{ $turno_nro }}",
                _sucursal: "{{ $sucursal->nombre }}",
                _fecha_actual: "{{ $fecha_actual }}",
                _usuario_nombre: "{{ $usuario->name }}",
                _tipo_movim: "SALIDA",
                _pathCajaStoreMov: "{{ route('caja_movimiento.store') }}",
                _hayStatus: false,
                _nombreImpresora: "{{ $nombreImpresora }}",
                _env: "{{ $env }}",
            };
            @if (session('status'))
                CAJA._hayStatus = true;
            @endif
        </script>
        <script src="{{ asset('js/caja/create.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>