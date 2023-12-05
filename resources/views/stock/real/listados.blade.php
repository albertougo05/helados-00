<!-- listado Articulos y Helados -->
<div class="grid-container">
    <div class="">
        <p class="ml-4 text-lg font-semibold">Artículos</p>
        <div class="my-0 mx-2 shadow-md overflow-auto rounded-md bg-white"
            style="height: 55vh;">
            @include('stock.real.table-articulos')
        </div>
    </div>
    <div class="">
        <p class="ml-3 text-lg font-semibold">Helados</p>
        <div class="my-0 mx-1 shadow-md overflow-auto rounded-md bg-white"
            style="height: 55vh;">
            @include('stock.real.table-helados')
        </div>
    </div>
</div>
