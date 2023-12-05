<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Utils;
use Illuminate\Http\Request;
use App\Models\StockComprobante;
use App\Models\StockDetalleComprobante;
use App\Models\Sucursal;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Carbon\Carbon;
use Exception;


class ImprimeComprobanteController extends Controller
{
    /**
     * Imprime comprobante de movimiento de stock
     * Name: comprobante_stock.imprime
     * 
     * http://localhost:8000/comprobante_stock/imprime/25
     *
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function __invoke(Request $request, Utils $utils)
    {
        $comprobante = $this->_getComprobante($request->id);
        $sucursal = Sucursal::select('id', 'nombre')
            ->find($comprobante[0]['sucursal_id']);

        if ($request->userAgent) {      // Si viene el SO imprime directo
            $sistOp = $utils->getSistemaOperativo($request->userAgent);
            $nombreImpresora = $utils->getNombreImpresora($sucursal->id);
        } else {
            $sistOp = false;
            $nombreImpresora = '';
        }

        $detalle = $this->_getDetalle($request->id, $comprobante[0]['sucursal_id']);
        $nombreImpresora = $utils->getNombreImpresora($sucursal->id);
        $idComprob = $request->id;
        
        $data = compact(
            'comprobante',
            'sucursal',
            'detalle',
            'idComprob',
            'sistOp',
            'nombreImpresora',
        );

        //dd($data);

        if ($sistOp) {
            $result = $this->_impresionDirecta($data);

            return response()->json($result);
        }

        return view('stock.imprimir', $data);
    }

	/**
	 * Es llamada desde ../Printer/IngresoStockController
	 */
    public function detalleComprobante($comprob_id, $sucursal_id)
    {
        $sucursal = Sucursal::select('id', 'nombre')
            ->find($sucursal_id);
        $comprobante = $this->_getComprobante($comprob_id);
        $detalle = $this->_getDetalle($comprob_id, $sucursal_id);

        return compact('sucursal', 'comprobante', 'detalle');
    }


    private function _getComprobante($id)
    {
        return StockComprobante::where('stock_comprobantes.id', $id)
            ->select('stock_comprobantes.*', 
                'stock_comprobante_tipos_movim.descripcion',
                'users.name')
            ->leftJoin('stock_comprobante_tipos_movim', 
                'stock_comprobantes.tipo_movimiento_id',
                '=',
                'stock_comprobante_tipos_movim.id')
            ->leftJoin('users', 
                'stock_comprobantes.usuario_id',
                '=',
                'users.id')
            ->get()
            ->toArray();
    }

    private function _getDetalle($id, $sucursal_id)
    {
        $detalle = StockDetalleComprobante::select(
                'id', 
                'stock_comprob_id',
                'producto_id',
                'grupo_id',
                'cantidad',
                'descripcion',
                'unidad_medida',
            )
            ->where([
                ['stock_comprob_id', $id],
                ['sucursal_id', $sucursal_id], 
            ])
            ->orderBy('grupo_id')
            ->orderBy('descripcion')
            ->get()
            ->toArray();

        return $detalle ? $detalle : [];
    }

    private function _impresionDirecta(array $data)
    {
        //$linea = "------------------------------------------------\n";   // 48 char
        $linea = "-----------------------------------\n";    // 35 char
        $result = ['estado' => 'ok'];

        try {

            if ($data['sistOp'] === 'Linux') {
                // Enter the device file for your USB printer here (FUNCIONA)
                //$connector = new FilePrintConnector("/dev/usb/lp0");
                $connector = new CupsPrintConnector($data['nombreImpresora']);
            } else {
                $connector = new WindowsPrintConnector($data['nombreImpresora']);
            }

            $printer = new Printer($connector);
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //$printer->setTextSize(2, 2);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->text($this->_formatoCentrar($data['sucursal']['nombre']));
            $printer->feed(2);
            $printer->setFont(Printer::FONT_A);
            $printer->setTextSize(1, 1);
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //$printer->text($lin48);
            $printer->text($linea);  // 32 char
            $printer->text("Entrada Stock Nro.: " . $data['comprobante'][0]['nro_comprobante'] . "\n");
            $printer->text("Motivo: " . $data['comprobante'][0]['descripcion'] . "\n");
            $printer->text("Fecha: " . Carbon::parse($data['comprobante'][0]['fecha'])->format('d/m/Y') . " ");
            $printer->text(substr($data['comprobante'][0]['hora'],0,5) . "\n");
            $printer->text("Usuario: " . $data['comprobante'][0]['name'] . "\n");
            $printer->text($linea);
            //$printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($this->_formatoCentrar("Detalle") . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($linea);
            $printer->text(" Cantidad UN Artículo\n");
            $printer->text($linea);

            foreach ($data['detalle'] as $value) {
                $printer->text($this->_formatoCantidad($value['cantidad']));
                $printer->text($value['unidad_medida'] . " ");
                $printer->text($value['descripcion'] . "\n");
            }
            $printer->text($linea);
            $printer->feed(2);
            $printer->text("Nombre:\n");
            $printer->feed(5);
            $printer->text("Firma:\n");
            $printer -> feed(2);

            $printer -> cut();            /* Cut paper */
            $printer -> close();          /* Close printer */

        } catch  (Exception $e) {
            echo "No se puede imprimir: " . $e->getMessage() . "\n";
            $result = ['estado' => "error: " . $e->getMessage()];
        }

        return $result;
    }

    private function _formatoCantidad(string $cant = null)
    {
        $lastChar = substr($cant, -1);

        if ($lastChar === "0") {        // Si el ultimo nro. es 0, van 2 decimales
            $longitud = strlen($cant) - 1;
            $cantidad = substr($cant,0,$longitud);
        } else {
            $cantidad = $cant;      // Si no, van 3 decimales
        }

        return str_pad($cantidad, 9, " ", STR_PAD_LEFT) . " ";
    }

    private function _formatoCentrar($text)
    {
        return str_pad($text, 35, " ", STR_PAD_BOTH);
    }

}
