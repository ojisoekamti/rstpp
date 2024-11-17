<!-- resources/views/order-print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .receipt {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            font-size: 14px;
        }

        .receipt-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .receipt-info {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-items th, .order-items td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-items th {
            background-color: #f4f4f4;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            font-size: 12px;
            margin-top: 20px;
            color: #888;
        }

        @media print {
            body {
                padding: 10mm;
                font-size: 12px;
            }

            .receipt-header {
                font-size: 16px;
            }

            .receipt-info, .total {
                font-size: 14px;
            }

            .order-items th, .order-items td {
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Receipt Header -->
        <div class="receipt-header">
            <h2>{{ strtoupper($order->restaurant_name) }}</h2>
            <p>{{ $order->address ?? 'Address Not Available' }}</p>
            <p>{{ $order->phone ?? 'Phone Not Available' }}</p>
        </div>

        <!-- Order Information -->
        <div class="receipt-info">
            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            @if($order->customer_name)
                <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            @endif
        </div>

        <!-- Order Items -->
        <table class="order-items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Price -->
        <div class="total">
            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your order!</p>
            <p>Visit us again at {{ $order->website ?? 'our website' }}</p>
        </div>
    </div>
</body>
</html>
