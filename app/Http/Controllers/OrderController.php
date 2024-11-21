<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\OrderItem;
use Illuminate\Support\Facades\Log;

class OrderController  extends Controller
{


    public function printOrder($orderId)
    {

        // Dummy order data (manually created for testing purposes)
        $order = (object) [
            'id' => 1,
            'restaurant_name' => 'Pizza Place',
            'order_number' => '12345',
            'created_at' => now(), // Current time
            'customer_name' => 'John Doe',
            'address' => '123 Pizza St, Pizzaville',
            'phone' => '555-1234',
            'website' => 'www.pizzaplace.com',
            'items' => [
                (object) [
                    'name' => 'Margherita Pizza',
                    'quantity' => 2,
                    'price' => 10.99
                ],
                (object) [
                    'name' => 'Coke',
                    'quantity' => 1,
                    'price' => 1.99
                ],
                (object) [
                    'name' => 'Garlic Bread',
                    'quantity' => 1,
                    'price' => 3.50
                ],
            ],
            'total' => 27.47, // Total price for all items
        ];

        // Return the view with the dummy order data
        return view('order-print', compact('order'));
    }

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

        return response()->json(['success' => false, 'message' => 'Order saved successfully!', 'name' => $request->all()], 500);

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
}
