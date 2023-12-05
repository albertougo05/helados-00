<?php

namespace App\Http\Controllers\Ventas\Imprimir;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Exception;



class ImprimeMuestrasController extends Controller
{
    /**
     * Muestras de impresion - Impresora EPSON TM-T20III.
     * http://localhost:8000/ventas/imprimir/muestras
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function muestras(Request $request)
    {
        // DETECTAR SISTEMA OPERATIVO !!
        //$userAgent = $request->input('sistoper');

        try {
            // Linux con CUPS
            $connector = new CupsPrintConnector("EPSON_TM-T20III");
            // Enter the device file for your USB printer here (FUNCIONA)
            // $connector = new FilePrintConnector("/dev/usb/lp0");


            // Windows
            //$connector = new WindowsPrintConnector("Nombre impresora compartida");

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);

            /* Initialize */
            $printer -> initialize();

            $printer -> text("Tipos de Letras:\n");
            $printer -> text("1234567890123456789012345678901234\n");
            $printer -> text(".........1.........2.........3....\n");
            $printer -> feed();

            /** Set MODE_FONT_A */
            $printer -> text("Set Modo: MODE_FONT_A\n");
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer -> text("Modo: MODE_EMPHASIZED (A)");
            $printer -> feed();
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> text("Modo: MODE_DOUBLE_HEIGHT (A)\n");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("Modo: MODE_DOUBLE_WIDTH (A)\n");
            $printer->selectPrintMode(Printer::MODE_UNDERLINE);
            $printer -> text("Modo: MODE_DOUBLE_UNDERLINE (A)\n");
            $printer -> feed();

            // $printer -> setUnderline(1);
            // $printer -> text("The quick brown fox jumps (uso setUnderLine 1)\n");
            // $printer -> setUnderline(2);
            // $printer -> text("The quick brown fox jumps  (uso setUnderLine 2)\n");
            // $printer -> setUnderline(0); // Reset
            // $printer -> feed();

            $printer -> setEmphasis(true);
            $printer -> text("Uso de setEmphasis(true)\n");
            $printer -> setEmphasis(false);
            $printer -> feed(2);  /** Salta dos renglones */

            $printer->setFont(); // Reset 
            /* Seting height an widht */
            $printer->setTextSize(2, 2);
            $printer->text("ARQUEO DE CAJA");
            $printer->text("\n");

            /** Set MODE_FONT_B */
            // $printer->selectPrintMode(Printer::MODE_FONT_B);
            // $printer -> text("Set Modo: MODE_FONT_B\n");
            // $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            // $printer -> text("Modo: MODE_EMPHASIZED (B)");
            // $printer -> feed();
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            // $printer -> text("Modo: MODE_DOUBLE_HEIGHT (B)\n");
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer -> text("Modo: MODE_DOUBLE_WIDTH (B)\n");
            // $printer->selectPrintMode(Printer::MODE_UNDERLINE);
            // $printer -> text("Modo: MODE_DOUBLE_UNDERLINE (B)\n");
            // $printer -> feed();

            // $printer -> setUnderline(1);
            // $printer -> text("The quick brown fox jumps (uso setUnderLine 1)\n");
            // $printer -> setUnderline(2);
            // $printer -> text("The quick brown fox jumps  (uso setUnderLine 2)\n");
            // $printer -> setUnderline(0); // Reset
            // $printer -> feed();

            // $printer -> setEmphasis(true);
            // $printer -> text("Uso de setEmphasis(true)\n");
            // $printer -> setEmphasis(false);
            // $printer -> feed();

            // $printer->setFont(Printer::FONT_A);
            // $printer -> text("Font: FONT_A\n");
            // $printer->setFont(Printer::FONT_B);
            // $printer -> text("Font: FONT_B\n");
            // $printer->setFont(Printer::FONT_C);
            // $printer -> text("Font: FONT_C\n");
            // $printer->setFont(); // Reset 
            // $printer -> feed();

            /* Justification */
            // $printer -> text("Muestras de justificación:\n");
            // $printer -> text("------------------------------------------------\n");  // 48 char
            // $justification = array(
            //     Printer::JUSTIFY_LEFT,
            //     Printer::JUSTIFY_CENTER,
            //     Printer::JUSTIFY_RIGHT);
            // for ($i = 0; $i < count($justification); $i++) {
            //     $printer -> setJustification($justification[$i]);
            //     $printer -> text("A man a plan a canal panama\n");
            // }
            // $printer -> setJustification(); // Reset
            // $printer -> feed(3);
            // $printer -> selectPrintMode(); // Reset

            /* Justification */
            // $printer -> text("Muestras de Sizes of text:\n");
            // $printer -> text("------------------------------------------------\n");

            /* Text of various (in-proportion) sizes */
            // $this->title($printer, "Change height & width\n");
            // for ($i = 1; $i <= 8; $i++) {
            //     $printer->setTextSize($i, $i);
            //     $printer->text($i);
            // }
            // $printer->text("\n");

            /* Width changing only */
            // $this->title($printer, "Change width only (height=4):\n");
            // for ($i = 1; $i <= 8; $i++) {
            //     $printer -> setTextSize($i, 4);
            //     $printer -> text($i);
            // }
            // $printer -> text("\n");

            /* Height changing only */
            // $this->title($printer, "Change height only (width=4):\n");
            // for ($i = 1; $i <= 8; $i++) {
            //     $printer -> setTextSize(4, $i);
            //     $printer -> text($i);
            // }
            // $printer -> text("\n");

            /* Very narrow text */
            // $this->title($printer, "Very narrow text:\n");
            // $printer -> setTextSize(1, 8);
            // $printer -> text("The quick brown fox jumps over the lazy dog.\n");

            /* Very flat text */
            // $this->title($printer, "Very wide text:\n");
            // $printer -> setTextSize(4, 1);
            // $printer -> text("Hello world!\n");

            /* Very large text */
            // $this->title($printer, "Largest possible text:\n");
            // $printer -> setTextSize(8, 8);
            // $printer -> text("Hello\nworld!\n");

            $printer -> cut();
            
            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }


    private function title(Printer $printer, $text)
    {
        $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer -> text("\n" . $text);
        $printer -> selectPrintMode(); // Reset
    }
}
