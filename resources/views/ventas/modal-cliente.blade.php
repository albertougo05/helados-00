<!-- componente MODAL Selecciona Cliente (comprobante) -->
<div class="modal-2 hidden">
    <div class="z-30 h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
        <!-- modal -->
        <div class="bg-white rounded shadow-lg w-9/12 md:w-2/5">
            <!-- modal header -->
            <div class="border-b px-4 py-2 flex justify-between items-center">
                <h2 id="tituloModal" class="font-semibold text-lg">Clientes Cuenta. Cte.</h2>
                <button class="text-black close-modal-2">&cross;</button>
            </div>
            <!-- modal body -->
            <div class="px-7 py-4 flex flex-col">
                <div class="grid overflow-auto grid-cols-2 grid-rows-2 gap-x-4 gap-y-2 w-auto px-3">
                    <!-- fila 1 col span 1-2 -->
                    <div class="box col-start-1 col-span-2">
                        <x-label for="selectModalCliente" :value="__('Lista clientes')"/>
                        <x-select-xs id="selectModalCliente" name="selectModalCliente" class="w-full">
                            <x-slot name="options">
                                <option value=0>Seleccione cliente...</option>
                                @foreach ($clientes as $clie)
                                    <option value="{{ $clie->id }}">{{ $clie->firma }} ({{ $clie->id }})</option>
                                @endforeach
                            </x-slot>
                        </x-select-xs>
                    </div>
                    <!-- fila 2 col span 1-2 -->
                    <div class="box pl-5 mt-2 col-start-1 col-span-2">
                        <p id="dataDir" class="text-gray-700">Dirección:</p>
                        <p id="dataLoc" class="text-gray-700">Localidad:</p>
                    </div>
                </div>
            </div>
            <!-- modal footer -->
            <div class="flex justify-end items-center w-100 border-t p-5">
                <button id="btnCancelaModalClie"
                    class="close-modal-2 px-4 py-1 mr-3 bg-gray-200 border border-transparent rounded text-gray-800 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Cancela
                </button>
                <button class="px-4 py-1 mr-2 bg-blue-500 hover:bg-blue-700 border border-transparent rounded text-white active:bg-blue-600 focus:outline-none focus:border-blue-600 focus:ring ring-blue-600 disabled:opacity-25 transition ease-in-out duration-150"
                        id="btnConfirmaModalClie">
                    Confirma
                </button>
            </div>
        </div>
    </div>
</div>