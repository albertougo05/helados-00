<!-- componente MODAL Selecciona opciones de combos promociones -->
<div class="modal-promo hidden">
    <div class="z-30 h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
        <!-- modal -->
        <div class="bg-white border-2 border-indigo-800 rounded-md shadow-lg w-9/12 md:w-2/5">
            <!-- modal header -->
            <div class="border-b px-4 py-2 flex justify-between items-center">
                <h2 class="font-semibold text-lg">Seleccione opciones combo</h2>
                <button class="text-black close-modal-promo">&cross;</button>
            </div>
            <!-- modal body -->
            <div id="modal-promo-body" class="px-8 py-4 flex flex-col">
                <!-- row 1 -->
                <h2 id="tituloModalPromo" class="p-1 mt-1 mb-2 font-bold text-lg rounded text-center bg-indigo-800">
                    Promo ...
                </h2>
                <h3 class="p-1 font-medium text-sm text-center">
                    $ 0,00
                </h2>
                <!-- row 2 -->
                <div id="muestra" class="pl-1 mt-2">
                    <label for="selCombo-1" 
                           class="block font-medium text-sm text-gray-700 pl-1">
                        Combo 1
                    </label>
                    <select id="selCombo-1" 
                            class="h-8 w-full pt-0.5  text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline">
                        <option value="1">ALFAJOR NEGRO X 1</option>
                        <option value="2">ALMENDRADO X 1</option>
                    </select>
                </div>
                <!-- row 3 -->
                <div class="mt-2 pl-1">
                    <label for="selCombo-0" 
                           class="block font-medium text-sm text-gray-700 pl-1">
                        Combo 0
                    </label>
                    <select id="selCombo-0" 
                            class="h-8 w-full pt-0.5  text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline">
                        <option value="1">Opcion 1</option>
                        <option value="2">Opcion 2</option>
                    </select>
                </div>
            </div>
            <!-- modal footer -->
            <div class="flex justify-end items-center w-100 border-t p-5">
                <button id="btnCancelModalPromo"
                    class="close-modal-promo px-4 py-1 mr-3 bg-gray-200 border border-transparent rounded text-gray-800 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Cancela
                </button>
                <button class="px-4 py-1 mr-2 bg-green-500 hover:bg-green-700 border border-transparent rounded text-white active:bg-green-600 focus:outline-none focus:border-green-600 focus:ring ring-green-600 disabled:opacity-25 transition ease-in-out duration-150"
                        id="btnConfirmModalPromo">
                    Confirma
                </button>
            </div>
        </div>
    </div>
</div>