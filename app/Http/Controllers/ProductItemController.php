<?php

namespace App\Http\Controllers;

use App\ProductItem;
use Illuminate\Http\Request;

class ProductItemController extends Controller
{
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
        ]);

        try {
            $product = ProductItem::findOrFail($id);

            // Update stock
            $product->stock += $request->quantity; // Increment or decrement based on quantity
            $product->save();

            return response()->json(['success' => true, 'message' => 'Stock updated successfully!', 'stock' => $product->stock]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update stock.', 'error' => $e->getMessage()], 500);
        }
    }
}
