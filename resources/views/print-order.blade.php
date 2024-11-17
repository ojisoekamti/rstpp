<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <style>
        /* General styling for the receipt */
        body {
            font-family: 'Courier New', Courier, monospace; /* Monospaced font for alignment */
            font-size: 12px;
            width: 58mm; /* Adjust for paper width */
            margin: 0;
            padding: 0;
        }

        .receipt {
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .items-table {
            width: 100%;
        }

        .items-table th,
        .items-table td {
            text-align: left;
        }

        .items-table td.price {
            text-align: right;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
        }

        .thank-you {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Restaurant Information -->
        <div class="center bold">
            <p>D'Sky Bistro</p>
        </div>
        <div class="line"></div>

        <!-- Order Information -->
        <p><strong>Order #:</strong>  #17</p>
        <p><strong>Date:</strong> 2024-11-16 19:58:01</p>
        <p><strong>Table:</strong> Room #233</p>
        <div class="line"></div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th class="price">Price</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total_price = 0;
                @endphp
                <!-- Loop through items -->
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="price">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @php
                $total_price += $item->price * $item->quantity;
                @endphp
                @endforeach
            </tbody>
        </table>
        <div class="line"></div>

        <!-- Total -->
        <p class="total">Total: Rp {{ number_format($total_price, 0, ',', '.') }}</p>
        <div class="line"></div>

        <!-- Thank You Note -->
        <div class="center thank-you">
            <p>Thank you for dining with us!</p>
            <p>Visit us again soon.</p>
        </div>
    </div>
</body>
</html>
