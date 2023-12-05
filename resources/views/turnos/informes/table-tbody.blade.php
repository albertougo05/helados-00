@foreach ($turnos as $turno)
    <tr class="hover:bg-gray-100">
        {{-- <td class="text-center">{{ $turno->nombre }}</td> --}}
        <td class="text-center">{{ $turno->caja_nro }}</td>
        <td class="text-center">{{ $turno->turno_sucursal }}</td>
        <td class="text-center">{{ Carbon\Carbon::parse($turno->apertura_fecha_hora)->format('d/m/y H:i') }}</td>
        @if($turno->cierre_fecha_hora)
            <td class="text-center">{{ Carbon\Carbon::parse($turno->cierre_fecha_hora)->format('d/m/y H:i') }}</td>
        @else
            <td></td>
        @endif
        <td class="text-center">{{ $turno->name }}</td>
        <td class="text-right pr-1">{{ number_format($turno->saldo_inicio, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->venta_total, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->efectivo, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->tarjeta_credito, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->tarjeta_debito, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->cuenta_corriente, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->caja, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($turno->arqueo, 2, ',', '.') }}</td>
        @if($turno->diferencia < 0)
            <td class="text-right font-bold text-red-800">{{ number_format($turno->diferencia, 2, ',', '.') }}</td>
        @else
            <td class="text-right">{{ number_format($turno->diferencia, 2, ',', '.') }}</td>
        @endif
        <!-- <td class="text-center">
            <span class="inline-flex items-center justify-center px-1 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                @if ($turno->estado == 1)
                    Activo
                @else
                    Anulado
                @endif
            </span>
        </td> -->
        <td class="px-2 py-2 whitespace-nowrap text-right font-medium">
            <a href="{{ route('turno.show', $turno->id) }}" 
                class="text-indigo-600 hover:text-indigo-100 hover:bg-indigo-500 rounded px-3 py-1">
                Ver
            </a>
        </td>
    </tr>
@endforeach