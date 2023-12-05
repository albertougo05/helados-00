<x-app-layout>
    <x-slot name="title">Heladerías - Firmas/Clientes</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Editar Cliente/Firma
            </div>
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <form method="POST" 
                  id="formEditFirm"
                  action="{{ route('firma.update', $firma->id) }}"
                  autocomplete="off">
                @csrf
                @method('PUT')

                <input type="hidden" name="nombre_fantasia" value="">
                <input type="hidden" name="nro_ing_brutos" value="">
                <input type="hidden" name="contacto" value="">
                <input type="hidden" name="proveedor" value="0">
                <input type="hidden" name="otros" value="0">
                <input type="hidden" name="plan_cuenta_id" value="0">

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="id" :value="__('Id')"/>
                        <x-input class="block mt-0 w-full h-9 text-right" 
                                type="text" 
                                value="{{ $firma['id'] }}" 
                        />
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 2/3 -->
                        <x-label for="firma" :value="__('Nombre')" />
                        <x-input type="text"
                                    name="firma"
                                    autocomplete="off"
                                    value="{{ $firma['firma'] }}"
                                    class="block mt-0 w-full h-9" />
                    </div>
                    <div>   <!-- Row 1 Col 4 -->
                        <x-label for="tipo_doc_id" :value="__('Tipo de documento')" />
                        <x-select name="tipo_doc_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                @foreach ($tipos_doc as $tipo)
                                    <option value="{{ $tipo['codigo_afip'] }}">
                                        {{ $tipo['documento'] }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 1 Col 5 -->
                        <x-label for="dni_cuit" :value="__('Nro. documento')" />
                        <x-input type="text"
                                 name="dni_cuit"
                                 autocomplete="off"
                                 value="{{ $firma['dni_cuit'] }}"
                                 class="block mt-0 w-full h-9"/>
                    </div>
                </div>
                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div>   <!-- Row 2 Col 1 -->
                        <x-label for="cond_iva" :value="__('Tipo de documento')" />
                        <x-select name="cond_iva" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                @foreach ($condic_iva as $key => $value)
                                    <option value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div class="col-span-2">   <!-- Row 2 Col 2/3 -->
                        <x-label for="direccion" :value="__('Dirección')" />
                        <x-input type="text"
                                 name="direccion"
                                 autocomplete="off"
                                 value="{{ $firma['direccion'] }}"
                                 class="block mt-0 w-full h-9"/>
                    </div>
                    <div>   <!-- Row 2 Col 4 -->
                        <x-label for="localidad" :value="__('Localidad')" />
                        <x-input type="text"
                                 name="localidad"
                                 autocomplete="off"
                                 value="{{ $firma['localidad'] }}"
                                 class="block mt-0 w-full h-9"/>
                    </div>
                    <div>   <!-- Row 2 Col 5 -->
                        <x-label for="provincia" :value="__('Provincia')" />
                        <x-input type="text"
                                 name="provincia"
                                 autocomplete="off"
                                 value="{{ $firma['provincia'] }}"
                                 class="block mt-0 w-full h-9"/>
                    </div>
                </div>
                <!-- GRID 3 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div>   <!-- Row 3 Col 1 -->
                        <x-label for="codigo_postal" :value="__('Código Postal')" />
                        <x-input type="text"
                                 name="codigo_postal"
                                 autocomplete="off"
                                 value="{{ $firma['codigo_postal'] }}"
                                 class="block mt-0 w-full h-9"/>
                    </div>
                    <div>   <!-- Row 3 Col 2 -->
                        <x-label for="telefono" :value="__('Teléfono')" />
                        <x-input type="text"
                                 name="telefono"
                                 autocomplete="off"
                                 value="{{ $firma['telefono'] }}"
                                 class="block mt-0 w-full h-9"/>
                    </div>
                    <div>   <!-- Row 3 Col 3 -->
                        <x-label for="celular" :value="__('Celular')" />
                        <x-input type="text"
                                 name="celular"
                                 autocomplete="off"
                                 value="{{ $firma['celular'] }}"
                                 class="block mt-0 w-full h-9" />
                    </div>
                    <div class="col-span-2">   <!-- Row 3 Col 4/5 -->
                        <x-label for="email" :value="__('Email')" />
                        <x-input type="text"
                                 name="email"
                                 autocomplete="off"
                                 value="{{ $firma['email'] }}"
                                 class="block mt-0 w-full h-9" />
                    </div>
                </div>
                <!-- GRID 4 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-1">
                    <div>   <!-- Row 4 Col 1 -->
                        <x-label for="cliente" :value="__('Tipo de Cliente')" />
                        <x-select name="cliente" id="cliente" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value="1" @if($firma['cliente'] == 1 ) selected @endif>
                                    Cliente Cta. Cte.
                                </option>
                                <option value="0" @if($firma['cliente'] == 0 ) selected @endif>
                                    Proveedor
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 4 Col 2 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <x-select name="estado" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value="1">
                                    Activo
                                </option>
                                <option value="0">
                                    Baja
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                </div>
                <!-- Línea y botones confirma / cancela  -->
                <div>
                    <hr class="my-4 mx-4 border-gray-400">
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
                            <x-link-salir href="{{ route('firma.index') }}">
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
            const _FIRMA = {
                pathIndex: "{{ route('firma.index') }}",
                pathFiltrado: "{{ route('firma.filtrado') }}",
            };
            const _setInputProveedor = () => {
                const _selectCliente = document.querySelector('#cliente');

                if (_selectCliente.value === "0") {     // Seleccionó Tipo Proveedor
                    document.querySelector('[name="proveedor"]').value = 1;
                }
            };

            const _verificaEmail = () => {
                //
            };

            const btnConfirma = document.querySelector('#btnConfirma');
            btnConfirma.addEventListener('click', function (el) {
                const form = document.querySelector('form');
                el.preventDefault();
                _setInputProveedor();
                form.submit();
            });

        </script>
    @endpush

</x-app-layout>
