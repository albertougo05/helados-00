const detalleDeCaja = {
    sucursal_id: _VENTA.sucursal_id,
    usuario_id: _VENTA.usuario_id,
    caja_nro: _VENTA.caja_nro,
    tipo_comprobante_id: 1,
    turno_cierre_id: 0,
    fecha_hora: '',
    fecha_registro: _VENTA.fecha,
    tipo_movim_id: 0,       // Venta
    forma_pago_id: 1,       // Efectivo
    nro_comprobante: _VENTA.numero,
    concepto: 'Venta', 
    importe: 0,
    vuelto: 0,
    total_efectivo: 0,
    total_debito: 0,
    total_tarjeta: 0,
    total_valores: 0,
    total_transfer: 0,
    total_bonos: 0,
    total_retenciones: 0,
    total_otros: 0,
    cuenta_corriente: 0,
    firma_id: 0,
    estado: 1,
    observaciones: '',
};

export default detalleDeCaja;