<x-app-layout>
    <x-slot name="title">Heladerías - Turno</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="flex justify-between px-6">
                <div class="pl-4 pt-6 text-gray-700 text-3xl font-bold">
                    Abrir Turno
                </div>
                <div class="mt-10 mr-4 text-blue-600 text-base font-semibold">
                    <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }}</p>
                </div>
            </div>
            @if (session('status'))
                <x-alert-success titulo="Perfecto !" >
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <div>
                <hr class="mt-2 mb-3 mx-4 border-gray-400">
            </div>
            <form id="formTurno"
                method="POST" 
                action="{{ route('turno.store') }}"
                autocomplete="off">
                @csrf

                <input type="hidden" name="sucursal_id" value="{{ $sucursal_id }}">
                <input type="hidden" name="usuario_id" value="{{ $usuario->id }}">
                <input type="hidden" name="apertura_fecha_hora" id="apertura_fecha_hora">
                <input type="hidden" name="caja" id="caja">

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-1">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="turno_sucursal" :value="__('Turno Nro.')"/>
                        <x-input id="turno_sucursal"
                                name="turno_sucursal"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $newid }}" 
                                readonly />
                    </div>
                    <div>   <!-- Row 1 Col 2 -->
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
                    <div>   <!-- Row 1 Col 3 -->
                        <x-label for="apertura_fecha" :value="__('Apertura')" class="mb-0"/>
                        <x-input id="apertura_fecha"
                                :value="old('apertura_fecha')" 
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                    <div>   <!-- Row 1 Col 3 -->
                        <x-label for="apertura_hora" :value="__('Hora apertura')" class="mb-0"/>
                        <x-input id="apertura_hora"
                            type="text"
                            :value="old('apertura_hora')"
                            class="h-9 w-full text-right mb-1" 
                            readonly />
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 4 -->
                        <x-label for="saldo_inicio" :value="__('Efectivo inicial')" class="mb-0"/>
                        <x-input id="saldo_inicio"
                                name="saldo_inicio"
                                :value="old('saldo_inicio')"
                                class="h-9 w-full text-right mb-1" 
                                type="text" 
                                readonly />
                    </div>
                </div>

                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div class="col-span-3">   <!-- Row 2 Col 1-3 -->
                        <x-label for="observaciones" :value="__('Observaciones')" />
                        <x-input type="text"
                                    name="observaciones"
                                    id="observaciones"
                                    :value="old('observaciones')"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9" 
                                    autofocus />
                    </div>
                    <div>   <!-- Row 2 Col 4 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <x-select id="estado" name="estado" class="w-full h-9 mt-0" disabled>
                            <x-slot name="options">
                                <option value="1">
                                    Normal
                                </option>
                                <option value="0">
                                    Anulado
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 5/6 -->
                        <x-label for="empleado" :value="__('Usuario que abre')" />
                        <x-input type="text"
                                    id="empleado"
                                    :value="old('empleado')"
                                    autocomplete="off"
                                    class="block mt-0 w-full h-9" 
                                    disabled />
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
                            <x-button class="mr-4" id="btnConfirma">
                                Confirma
                            </x-button>
                            <x-link-cancel href="{{ route('turno.create') }}" class="mr-4">
                                Cancelar
                            </x-link-cancel>
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
                _sucursal_id: {{ $sucursal_id }},
                _fecha_actual: "{{ $fecha_actual }}",
                _usuario_nombre: "{{ $usuario->name }}",
                _saldo_inicio: {{ $saldo_inicio }},
                _pathBuscarArqueo: "{{ route('turno.buscararqueo') }}",
            };
        </script>
        <script src="{{ asset('js/turnos/create.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>