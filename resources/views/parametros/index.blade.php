<x-app-layout>
    <x-slot name="title">Heladerías - Parametros</x-slot>

    <div class="flex mt-4 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="flex justify-between pt-6">
                <div class="pl-8 text-gray-700 text-3xl font-bold">
                    Parámetros generales 
                </div>
                <div class="mr-8">
                    <span class="text-xl text-gray-700 font-bold">Sucursal: </span><span class="text-xl font-bold text-blue-700">{{ $sucursal->nombre }}</span>
                </div>
            </div>
            <div>
                <hr class="mt-2 mb-6 mx-4 border-gray-500">
            </div>

            <div x-data="{ selected: null }" class="py-2 flex flex-col items-center justify-center relative overflow-auto">
                <div @click="selected !== 0 ? selected = 0 : selected = null" 
                    class="mb-0.5 p-4 bg-blue-100 w-1/2 rounded-md flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(43, 108, 176, 1);transform: ;msFilter:;">
                            <path d="M19 7h-1V2H6v5H5c-1.654 0-3 1.346-3 3v7c0 1.103.897 2 2 2h2v3h12v-3h2c1.103 0 2-.897 2-2v-7c0-1.654-1.346-3-3-3zM8 4h8v3H8V4zm8 16H8v-4h8v4zm4-3h-2v-3H6v3H4v-7c0-.551.449-1 1-1h14c.552 0 1 .449 1 1v7z"></path>
                            <path d="M14 10h4v2h-4z"></path>
                        </svg>
                        <h4 class="font-medium text-blue-700">Impresoras sucursal</h4>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div x-show="selected == 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-0"
                      x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
                      x-transition:leave-start="opacity-100 translate-y-10" x-transition:leave-end="opacity-0 translate-y-0" class="w-1/2 bg-white p-4 ">

                    @foreach ($cajas_sucursal as $key => $caja)
                        @php($nombre_impresora = $impresoras[$key]['nombre'] ?? '')
                        <div class="inputs_impresoras mb-2">   <!-- Inputs impresoras -->
                            <label for="imp_caja_{{ $caja['id'] }}">Impresora {{ $caja['texto'] }}</label>
                            <div class="flex">
                                <input id="imp_caja_{{ $caja['id'] }}"
                                        data-sop="{{ $impresoras[$key]['sist_operativo'] ?? $SO }}"
                                        data-caja="{{ $caja['id'] }}"
                                        class="block mt-0 w-3/4 h-8 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                        type="text" 
                                        @if (!$nombre_impresora)
                                            placeholder="Ingrese nombre..."
                                        @else
                                            value="{{ $impresoras[$key]['nombre'] ?? ''}}"
                                        @endif
                                        />
                                        @if ($nombre_impresora)
                                            <button id="btnEliminaImp-{{ $caja['id'] }}"
                                                    data-cajaid="{{ $caja['id'] }}"
                                                    data-impid="{{ $impresoras[$key]['id'] }}"
                                                    class="btnElim bg-red-600 ml-4 mt-0 px-3 py-1 text-red-100 focus:border-red-900 focus:ring ring-red-500 font-bold rounded disabled:bg-red-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" data-impid="{{ $impresoras[$key]['id'] }}" data-cajaid="{{ $caja['id'] }}" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                        <path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                    </svg>
                                            </button>
                                        @endif
                            </div>
                            <p class="text-sm pl-2 text-emerald-600">Sistema operativo: {{ $impresoras[$key]['sist_operativo'] ?? $SO }}</p>
                        </div>
                    @endforeach
                    <div class="flex justify-end">
                        <button id="btnConfirmaImpresora" class="bg-green-500 px-3 py-2 mt-1 text-sm text-green-100 hover:bg-green-600 font-bold rounded disabled:bg-green-300">
                            Confirma
                        </button>
                    </div>
                </div>
                <div @click="selected !== 1 ? selected = 1 : selected = null" 
                    class="mb-0.5 p-4 bg-blue-100 w-1/2 rounded-md flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="rgba(43, 108, 176, 1)" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <h4 class="font-medium text-blue-700">Peso lata de helado</h4>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div x-show="selected == 1" x-transition:enter="transition ease-out duration-300"
                      x-transition:enter-start="opacity-0 translate-y-0"
                      x-transition:enter-end="opacity-100 translate-y-0"
                      x-transition:leave="transition ease-in duration-300"
                      x-transition:leave-start="opacity-100 translate-y-10"
                      x-transition:leave-end="opacity-0 translate-y-0" class="w-1/2 bg-white p-4 ">

                    <div>   <!-- Input otro param -->
                        <x-label for="peso_envase" :value="__('Ingrese peso lata de helado (Kgs.)')"/>
                        <x-input id="peso_envase"
                                class="block mt-0 w-3/4 text-right"
                                type="text" 
                                value="{{ $peso_envase }}" />
                    </div>
                    <div class="flex justify-end">
                        <button id="btnConfirmaEnv" class="bg-green-500 px-3 py-2 mt-1 text-sm text-green-100 hover:bg-green-600 font-bold rounded">
                            Confirma
                        </button>
                    </div>
                </div>

                <div @click="selected !== 2 ? selected = 2 : selected = null" class="mb-0.5 p-4 bg-blue-100 w-1/2 rounded-md flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(43, 108, 176, 1);transform: ;msFilter:;">
                            <path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z"></path>
                        </svg>
                        <h4 class="font-medium text-blue-700">Envío e-mail al cerrar turno caja</h4>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div x-show="selected == 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-0"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-10" x-transition:leave-end="opacity-0 translate-y-0" class="w-1/2 bg-white p-4 ">
                    @foreach ($lista_emails as $key => $email)
                        @php($email_1 = $email['direccion_email'] ?? '')
                        <div class="mb-2">   <!-- Inputs emails -->
                            <div class="flex flex-col inputs_emails">
                                <div id="divinput-{{ $key }}" class="flex">
                                    <input id="inp_email_{{ $key }}"
                                        data-emailid="{{ $key }}"
                                        class="inputEmail block mt-0 w-3/4 h-8 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                        type="email"
                                        @if (!$email_1)
                                            placeholder="Ingrese email..."
                                            data-tienebtn="false"
                                        @else
                                            value="{{ $email['direccion_email'] ?? ''}}"
                                            data-tienebtn="true"
                                        @endif
                                    />
                                    @if ($email_1)
                                        <button id="btnEliminaEmail-{{ $key }}"
                                                data-emailid="{{ $key }}"
                                                class="btnElimEmail bg-red-600 ml-4 mt-0 px-2 py-1 text-red-100 focus:border-red-700 focus:ring ring-red-500 font-bold rounded disabled:bg-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="btnElimEmail" data-emailid="{{ $key }}" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                                    <path class="btnElimEmail" data-emailid="{{ $key }}" d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                                </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex justify-end">
                        <button id="btnAgregaEmail"
                                onclick="PARAMS._clickBtnAgregarEmail();"
                                class="bg-orange-500 px-3 py-2 mt-3 mr-3 text-sm text-orange-100 hover:bg-orange-600 font-bold rounded disabled:bg-orange-300">
                            Agrega email
                        </button>
                        <button id="btnConfirmaEmail" class="bg-green-500 px-3 py-2 mt-3 text-sm text-green-100 hover:bg-green-600 font-bold rounded disabled:bg-green-300">
                            Confirma
                        </button>
                    </div>
                </div>

            </div>

            <div>
                <hr class="mt-4 mb-3 mx-4 border-gray-500">
            </div>
            <!-- Botón Salida (cancelar) -->
            <div class="flex justify-between px-8 mb-3">
                <div class="invisible w-full" id="spinGuardando">
                    <div class="flex justify-center pt-1 mr-2">
                        <div class="mr-6 mt-1">
                            <p>Guardando datos...</p>
                        </div>
                        <div class="float-right">
                            <div style="border-top-color:transparent"
                                class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
                <div class="invisible w-full" id="guardadoOk">
                    <div class="flex flex-row mt-1 mr-2">
                        <h6 id="textoGuardadoOk" class="text-base mr-2 font-medium leading-tight text-gray-800">
                            Guardado
                        </h6>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(56, 161, 105, 1);transform: ;msFilter:;">
                            <path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-7.933 13.481-3.774-3.774 1.414-1.414 2.226 2.226 4.299-5.159 1.537 1.28-5.702 6.841z"></path>
                        </svg>
                    </div>
                </div>
                <x-link-cancel href="{{ route('dashboard') }}" id="btnSalir">
                    Salir
                </x-link-cancel>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var PARAMS = {
                _pathStoreImpresoras: "{{ route('parametros.store_impresora') }}",
                _pathStorePesoEnvases: "{{ route('parametros.store_peso_env') }}",
                _pathEliminarImpresora: "{{ route('parametros.eliminar_impres') }}",
                _pathStoreEmails: "{{ route('parametros.store_email') }}",
                _sistOperativo: "{{ $SO }}",
                _sucursal_id: "{{ $sucursal_id }}",
                _peso_envase: "{{ $peso_envase }}",
                _clickBtnEliminarEmail: undefined,
                _clickBtnAgregarEmail: undefined,
                _onblurCheckEmail: undefined,
                _idxInputsEmails: "{{ count($lista_emails) }}",
            };
        </script>
        <script src="{{ asset('js/parametros/index.js') }}?ver=0.0.{{ rand()"></script>
    @endpush

</x-app-layout>
