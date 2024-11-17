<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class ReceiptController extends Controller
{
    public function generateReceipt()
    {
        // Example data for the receipt
        $data = [
            'restaurant_name' => 'My Restaurant',
            'address' => '123 Main Street, City',
            'items' => [
                ['name' => 'Item 1', 'qty' => 2, 'price' => 10000],
                ['name' => 'Item 2', 'qty' => 1, 'price' => 5000],
            ],
            'total' => 25000,
        ];

        // Load the view and pass data
        $pdf = Pdf::loadView('receipt', $data);

        // Set custom paper size (80mm x 300mm)
        $pdf->setPaper([0, 0, 226.772, 850], 'portrait'); // Dimensions in points (1mm = 2.83465 points)

        // Stream the PDF to the browser
        return $pdf->stream('receipt.pdf');
    }
}
