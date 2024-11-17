<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Use monospaced font for receipt */
            font-size: 12px;
            margin: 0;
            padding: 0;
            width: 72mm; /* Set width to printable area */
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
            <h2>Restaurant Name</h2>
            <p>Address Line 1<br>Address Line 2</p>
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
                <tr>
                    <td>Item 1</td>
                    <td>2</td>
                    <td>Rp 10,000</td>
                </tr>
                <tr>
                    <td>Item 2</td>
                    <td>1</td>
                    <td>Rp 5,000</td>
                </tr>
            </tbody>
        </table>

        <hr>

        <p class="total">Total: Rp 25,000</p>

        <div class="footer">
            <p>Thank you for your visit!</p>
        </div>
    </div>
</body>

</html>
