<?php
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * Controlador para prueba de impresion en windows
 * 
 * Url: http://localhost:8000/windows/print
 * 
 */
class PrintController extends Controller
{
    public function print(Request $request)
    {

        return view('print.index');
    }
}