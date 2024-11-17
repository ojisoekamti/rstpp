<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 72mm;
        }

        .receipt {
            width: 100%;
            padding: 10px;
        }

        .receipt .header,
        .receipt .footer {
            text-align: center;
        }

        .receipt .items {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt .items td,
        .receipt .items th {
            border-bottom: 1px dashed #000;
            padding: 5px;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <h2>{{ $restaurant_name }}</h2>
            <p>{{ $address }}</p>
        </div>

        <hr>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <p class="total">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>

        <div class="footer">
            <p>Thank you for your visit!</p>
        </div>
    </div>
</body>

</html>
