<div class="flex justify-between px-6">
    <div class="mt-5 text-gray-700 text-3xl font-bold">
        {{ $titulo }}
    </div>
    <div class="mt-8 text-blue-600 text-base font-semibold">
        <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal->nombre }}</p>
    </div>
    <div class="mt-8 mr-1 text-blue-700 text-base font-semibold">
        <p><span class="text-gray-800">Turno: </span>{{ $turno_sucursal }}</p>
    </div>
    @if (isset($fecha_hora))
        <div class="mt-8 mr-1 text-blue-700 text-base font-semibold">
            <p><span class="text-gray-800">Comprobante: </span>{{ $comprobante->nro_comprobante }} - {{ $fecha_hora }}</p>
        </div>
    @endif
</div>