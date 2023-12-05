<div class="flex flex-col">
    <div class="flex">
        <button 
            type="button"
            class="h-7 px-3 ml-5 my-1 inline-flex items-center bg-green-600 border border-transparent rounded font-semibold text-base text-white hover:bg-green-800 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150"
            id="btnAgregarProducto">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </span>
            Agrega Producto
        </button>
    </div>
    <div class="bg-white shadow rounded overflow-x-auto mt-2 mx-5">
        <table class="table-fixed min-w-full md:rounded" id="tableReceta">
            <thead class="text-sm bg-indigo-800 text-gray-200">
                <tr>
                    <th scope="col" class="hidden">Id</th>
                    <th scope="col" class="py-2 text-center">Código</th>
                    <th scope="col" class="py-2 text-center w-1/2">Descripción</th>
                    <th scope="col" class="pr-1 py-2 text-right">Costo</th>
                    <th scope="col" class="pr-1 py-2 text-right">Cant.</th>
                    <th scope="col" class="pr-2 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200"
                   id="bodyTableReceta">
            </tbody>
        </table>
    </div>
    <div class="flex justify-end">
        <div>
            <p class="text-base mr-7 mt-2">Total &nbsp; &nbsp; $ <span id="totalReceta" class="font-medium">0,00</span></p>
        </div>
    </div>
    <div id="divConfirmarReceta" class="hidden">
        <div>
            <hr class="mt-2 mb-1 mx-4 border-gray-400">
        </div>
        <div class="flex justify-end">
            <button type="button"
                    class="h-8 py-1 px-3 mt-4 mb-2 mr-5 inline-flex items-center bg-blue-600 border border-transparent rounded font-semibold text-base text-white hover:bg-blue-800 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                    id="btnConfirmaReceta">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                Confirma Receta
            </button>
        </div>
    </div>
</div>