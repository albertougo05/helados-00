
Informe de cierre de caja.
-------------------------------------------------

Sucursal: {{ $content->sucursal }} -//- Turno nro: {{ $content->turno_sucursal }}
Usuario: {{ $content->usuario }}

Apertura: {{ $content->apertura_fecha }} -//- Cierre: {{ $content->cierre_fecha }}

Saldo inicial: {{ $content->saldo_inicio }}
Venta Total: {{ $content->vta_total }}
Venta efectivo: {{ $content->vta_efectivo }}
Venta débito: {{ $content->vta_debito }}
Venta crédito: {{ $content->vta_credito }}
Venta cta. cte.: {{ $content->vta_ctacte }}
Tranferencias: {{ $content->vta_transf }}
Otros ingresos: {{ $content->ingresos }}
Otros egresos: {{ $content->egresos }}

Caja teórica: {{ $content->caja_teorica }}
Arqueo: {{ $content->arqueo }}
Diferencia: {{ $content->diferencia }}

Observaciones: {{ $content->observaciones }}


Enviado por: {{ $content->usuario }}
