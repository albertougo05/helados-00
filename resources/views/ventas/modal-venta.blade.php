<!-- componente MODAL Confirma Venta (comprobante) -->
    <div class="modal hidden">
        <div class="z-30 h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
            <!-- modal -->
            <div class="bg-gray-50 rounded shadow-lg w-9/12 md:w-3/5">
                <!-- modal header -->
                <div class="border-b-2 px-4 py-2 flex justify-between items-center">
                    <h2 id="tituloModal" class="pl-2 font-bold text-2xl">Confirma esta venta ?</h2>
                    <button class="text-black close-modal">&cross;</button>
                </div>
                <!-- modal body -->
                <div class="px-7 py-4 flex flex-col">
                    <div class="grid overflow-auto grid-cols-3 grid-rows-3 gap-x-2 gap-y-1 w-auto px-3">
                        <!-- row 1 / col 1 -->
                        <div class="box flex col-start-1 col-span-2 text-sm">
                            Cliente: &nbsp; <span><p id="spanCliente" class="font-semibold"></p></span>
                        </div>
                        <!-- row 1 / col 2 -->
                        <div class="box flex justify-end text-sm">
                            Fecha: &nbsp; <span><p id="spanFecha" class="font-semibold"></p></span>
                        </div>
                        <!-- row 2 / col 1 -->
                        <div class="box flex text-sm col-start-1 col-span-3">
                            Comprobante: &nbsp; <span><p id="spanComprobante" class="font-semibold"></p></span>
                        </div>
                        <!-- row 3 / col 1  -->
                        <div class="box flex text-sm col-start-1 col-span-3">
                            Forma pago: &nbsp; <span><p id="spanFormaPago" class="font-semibold"></p></span>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="pb-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow-md overflow-auto border-b border-gray-200 rounded">
                            <table class="min-w-full divide-y divide-gray-300 table-fixed" id="tablaVentaModal">
                                <thead class="bg-blue-700">
                                    <tr class="text-sm font-semibold text-gray-100">
                                        <th scope="col" 
                                            class="px-1 py-2 text-center">
                                            Descripción
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-right">
                                            Precio
                                        </th>
                                        <th scope="col" class="px-2 py-2 text-right">
                                            Cantidad
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="bodyVentaModal">
                                    <!-- llena JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-3 mb-2">
                    <!-- PAGO -->
                    <div id="pagoVenta"> 
                        <div class="flex justify-end ml-10">
                            <p id="pagoModal" class="text-gray-600 text-lg font-bold">Pago $ 0,00</p>
                        </div>
                    </div>
                    <!-- VUELTO -->
                    <div id="vueltoVenta"> 
                        <div class="flex justify-end">
                            <p id="vueltoModal" class="text-gray-600 text-lg">Vuelto $ 0,00</p>
                        </div>
                    </div>
                    <!-- TOTAL -->
                    <div id="totalVenta"> 
                        <div class="flex justify-end">
                            <p id="totalModal" class="mr-10 text-gray-900 text-lg font-bold">Total $ 0,00</p>
                        </div>
                    </div>
                </div>
                <!-- modal footer -->
                <div class="flex justify-end items-center p-5">
                    <button id="btnCancelaModal"
                        class="close-modal inline-flex items-center w-32 pl-4 py-1 mr-3 bg-gray-200 border border-transparent rounded text-gray-800 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgb(46, 46, 46);">
                            <path d="M9 10h6c1.654 0 3 1.346 3 3s-1.346 3-3 3h-3v2h3c2.757 0 5-2.243 5-5s-2.243-5-5-5H9V5L4 9l5 4v-3z"></path>
                        </svg>
                        &nbsp;Volver
                    </button>
                    <button class="w-32 inline-flex items-center pl-3 py-1 mr-2 bg-green-500 hover:bg-green-700 border border-transparent rounded text-white active:bg-green-600 focus:outline-none focus:border-green-600 focus:ring ring-green-600 disabled:opacity-25 transition ease-in-out duration-150"
                            id="btnConfirmaTicketVenta">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        &nbsp;Confirma
                    </button>
                </div>
            </div>
        </div>
    </div>
