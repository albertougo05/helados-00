<!-- componente MODAL Muestra comprobantes de caja y/o ventas -->
    <div class="modal hidden">
        <div class="z-30 h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
            <!-- modal -->
            <div class="bg-white rounded shadow-lg w-11/12">
                <!-- modal header -->
                <div class="border-b px-4 py-2 mb-4 flex justify-between items-center">
                    <h2 id="tituloModal" class="font-semibold text-lg">Comprobantes de Ventas</h2>
                    <button class="text-black close-modal">&cross;</button>
                </div>
                <!-- modal body -->
                <div class="flex">
                    <div class="pb-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow-md overflow-auto rounded">
                            <table class="min-w-full divide-y divide-gray-300 table-fixed" 
                                id="tablaComprobs">
                                <thead class="bg-green-600">
                                    <tr class="text-sm font-semibold text-gray-100">
                                        <th scope="col" 
                                            class="px-1 py-2 text-center">
                                            Fecha
                                        </th>
                                        <th scope="col" class="p-2 text-center">
                                            Hora
                                        </th>
                                        <th scope="col" class="p-2 text-center">
                                            Tipo
                                        </th>
                                        <th scope="col" class="p-2 text-center">
                                            Número
                                        </th>
                                        {{-- <th scope="col" class="p-2 text-left">
                                            Sucursal
                                        </th> --}}
                                        <th scope="col" class="p-2 text-center">
                                            Caja Nro.
                                        </th>
                                        <th scope="col" class="p-2 text-left">
                                            Concepto
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Efectivo
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Debito
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Tarjeta
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Valores
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Transfer.
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Cta. Cte.
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-right">
                                            Importe
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-xs" 
                                    id="bodyTablaComprobs">
                                    <!-- llena JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- modal footer -->
                <div class="flex justify-end items-center w-100 border-t p-5 mt-4">
                    <button id="btnCerrarModal"
                        class="close-modal px-4 py-1 mr-3 bg-gray-200 border border-transparent rounded text-gray-800 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
