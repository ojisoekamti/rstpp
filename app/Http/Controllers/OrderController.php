<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPlaced;


use App\OrderItem;
use Exception;
use Illuminate\Support\Facades\Log;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


class OrderController  extends Controller
{


    // public function printOrder($orderId)
    // {

    //     // Dummy order data (manually created for testing purposes)
    //     $order = (object) [
    //         'id' => 1,
    //         'restaurant_name' => 'Pizza Place',
    //         'order_number' => '12345',
    //         'created_at' => now(), // Current time
    //         'customer_name' => 'John Doe',
    //         'address' => '123 Pizza St, Pizzaville',
    //         'phone' => '555-1234',
    //         'website' => 'www.pizzaplace.com',
    //         'items' => [
    //             (object) [
    //                 'name' => 'Margherita Pizza',
    //                 'quantity' => 2,
    //                 'price' => 10.99
    //             ],
    //             (object) [
    //                 'name' => 'Coke',
    //                 'quantity' => 1,
    //                 'price' => 1.99
    //             ],
    //             (object) [
    //                 'name' => 'Garlic Bread',
    //                 'quantity' => 1,
    //                 'price' => 3.50
    //             ],
    //         ],
    //         'total' => 27.47, // Total price for all items
    //     ];

    //     // Return the view with the dummy order data
    //     return view('order-print', compact('order'));
    // }

    public function confirmationOrder()
    {
        return view('order-confirmation');
    }

    public function confirmOrder(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'orderDetails' => 'required|array',
            'totalAmount' => 'required|numeric',
        ]);

        // return response()->json(['success' => false, 'message' => 'Order saved successfully!', 'name' => $request->all()], 500);

        // Start a database transaction to ensure both order and items are stored atomically
        DB::beginTransaction();

        try {
            // Create a new order
            $order = Order::create([
                'total_amount' => $request->totalAmount,
                'customer_name' => $request->name,
                'table_id' => $request->tableId,
                'phone' => $request->phone,
            ]);

            // Loop through order items and save them to the database
            foreach ($request->orderDetails as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_item_id' => $item['itemId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'],
                ]);
            }

            // Commit the transaction
            DB::commit();

            broadcast(new OrderPlaced($order));
            return response()->json(['success' => true, 'message' => 'Order saved successfully!', 'name' => $request->name], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if anything goes wrong
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Order Confirmation Error: ' . $e->getMessage());

            // Return a detailed error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function placeOrder(Request $request)
    {
        // Validate order data
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'table_id' => 'required|integer',
            'phone' => 'required|string',
            'order_details' => 'required|array',
            'total_amount' => 'required|numeric',
        ]);

        // Save order to database (example only)
        $order = Order::create($validated);

        // Dispatch an event to notify the frontend
        broadcast(new OrderPlaced($order));

        // Print the order (thermal)
        $this->printOrder($order);

        return response()->json(['success' => true, 'order' => $order]);
    }

    private function printOrder($order)
    {
        try {
            // Create a connector to the thermal printer
            $connector = new NetworkPrintConnector("192.168.0.100", 9100); // Replace with your printer IP
            $printer = new Printer($connector);

            // Print order receipt
            $printer->text("Order Receipt\n");
            $printer->text("-----------------------\n");
            $printer->text("Customer: " . $order->customer_name . "\n");
            $printer->text("Table: " . $order->table_id . "\n");
            $printer->text("-----------------------\n");
            foreach ($order->order_details as $item) {
                $printer->text($item['name'] . " x " . $item['quantity'] . " @ " . $item['price'] . "\n");
            }
            $printer->text("-----------------------\n");
            $printer->text("Total: " . $order->total_amount . "\n");
            $printer->cut();

            // Close printer connection
            $printer->close();
        } catch (Exception $e) {
            Log::error("Printing failed: " . $e->getMessage());
        }
    }

    public function getLatestOrder()
    {
        // Fetch the latest order with its items
        $latestOrder = Order::with('items')->orderBy('created_at', 'desc')->first();
        dd($latestOrder);

        if (!$latestOrder) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        // Format the response
        $orderData = [
            'customer_name' => $latestOrder->customer_name,
            'table_id' => $latestOrder->table_id,
            'phone' => $latestOrder->phone,
            'items' => $latestOrder->items->map(function ($item) {
                return [
                    'name' => $item->productItem->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
            'total_amount' => $latestOrder->total_amount,
        ];

        return response()->json($orderData, 200);
    }
}
