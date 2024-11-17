<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\TcpConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class PrintController extends Controller
{
    public function printReceipt()
    {
        try {
            $connector = new WindowsPrintConnector("EPSON TM-T82 ReceiptSA4");
            $printer = new Escpos($connector);
            $printer -> text("Hello World!\n");
            $printer -> cut();
            $printer -> close();
        } catch(Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }

        try {
            // Define the printer's IP and port (default is 9100 for Epson printers)
            $printerIp = '192.168.1.100'; // Replace with your printer's IP
            $printerPort = 9100;          // Default TCP port for TM-T82

            // Create a connection to the printer
            $connector = new TcpConnector($printerIp, $printerPort);
            $printer = new Printer($connector);

            // Begin printing commands
            $printer->setStyles([
                'font' => Printer::FONT_B,
                'align' => Printer::ALIGN_CENTER,
                'height' => Printer::HEIGHT_DOUBLE,
                'width' => Printer::WIDTH_DOUBLE,
            ]);

            // Print a sample receipt header
            $printer->text("Restaurant Name\n");
            $printer->text("Order No: 1234\n");
            $printer->text("Date: " . now()->format('Y-m-d H:i:s') . "\n");

            // Print items
            $printer->text("--------------------------------\n");
            $printer->text("Item 1       $10.00\n");
            $printer->text("Item 2       $15.00\n");
            $printer->text("Item 3       $5.00\n");
            $printer->text("--------------------------------\n");

            // Print total
            $printer->text("Total        $30.00\n");

            // Print footer
            $printer->text("Thank you for your visit!\n");

            // Cut the paper
            $printer->cut();

            // Close the connection
            $printer->close();
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
