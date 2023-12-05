<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\StockComprobanteTipoMovimiento;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateStockTipoMovimRequest;
use Illuminate\Support\Facades\DB;


class TiposMovimientoController extends Controller
{
    /**
     * Listado de Tipos de Movimientos de Stock
     * Name: stock_tipos_movim.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposMovim = StockComprobanteTipoMovimiento::orderBy('descripcion')
            ->paginate(15);

        $data = compact(
            'tiposMovim', 
        );

        return view('stock.tipos_movim.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * Name: stock_tipos_movim.create
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo = StockComprobanteTipoMovimiento::latest()->first();
        if ($tipo) {
            $newid = (integer) $tipo->id + 1;
        } else {
            $newid = 1;
        }

        $data = compact(
            'newid',
        );

        return view('stock.tipos_movim.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * Name: stock_tipos_movim.store (POST)
     *
     * @param ValidateStockTipoMovimRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateStockTipoMovimRequest $request)
    {
        $request->validated();
        $tipo = StockComprobanteTipoMovimiento::create([
            'descripcion' => $request->descripcion,
            'tipo_movimiento' => (integer) $request->tipo_movimiento,
        ]);

        if ($tipo) {
            return redirect(route('stock_tipos_movim.index'))->with('status', 'Nuevo Tipo creado !');
        } else {
            return redirect(route('stock_tipos_movim.index'))->with('status', 'No se pudo crear nuevo Tipo !!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Name: stock_tipos_movim.edit (PUT)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo = StockComprobanteTipoMovimiento::find($id);

        return view('stock.tipos_movim.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     * Name: stock_tipos_movim.update
     *
     * @param  ValidateStockTipoMovimRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateStockTipoMovimRequest $request)
    {
        $request->validated();
        $data = [
            'descripcion' => $request->descripcion,
            'tipo_movimiento' => $request->tipo_movimiento,
            'estado' => $request->estado
        ];

        // Start transaction
        DB::beginTransaction();
            $rows =  StockComprobanteTipoMovimiento::where('id', $request->input('id'))
                        ->lockForUpdate()
                        ->update($data);
        // Si NO actualizó ninguna línea...
        if ($rows == 0) {
            DB::rollBack();
            return redirect(route('stock_tipos_movim.index'))->with('status', 'NO se pudo actualizar Tipo !!');
        } 

        DB::commit();

        return redirect(route('stock_tipos_movim.index'))->with('status', 'Tipo actualizado !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

}
