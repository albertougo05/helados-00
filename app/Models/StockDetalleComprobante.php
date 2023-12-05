<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StockDetalleComprobante extends Model
{
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_detalle_comprobantes';


    /**
     * Get the StockComprobante associated with the StockDetalleComprobante.
     */
    public function comprobante_stock()
    {
        return $this->hasOne(StockComprobante::class, 'id', 'stock_comprob_id');
    }
}
