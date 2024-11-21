<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Order List</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Pusher and Echo CDN for real-time functionality -->
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.js"></script>
    <style>
        .order-item {
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .order-title {
            font-weight: bold;
        }

        .order-total {
            font-size: 1.2rem;
            color: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Real-Time Order List</h1>

        <!-- Order List Container -->
        <div id="order-list">
            <!-- Orders will be appended here dynamically -->
        </div>
    </div>

    <script>
        // Set up Echo for listening to the 'OrderPlaced' event
        const echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}', // Replace with your Pusher key from .env
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        // Listen for new orders in the 'order-channel'
        echo.channel('order-channel')
            .listen('OrderPlaced', (event) => {
                const order = event.order;
                console.log('New Order:', order); // Debugging

                // Dynamically append the order to the order list
                const orderList = document.getElementById('order-list');
                const orderElement = document.createElement('div');
                orderElement.classList.add('order-item');

                orderElement.innerHTML = `
                    <div class="order-title">${order.customer_name} - Table: ${order.table_id}</div>
                    <div>Phone: ${order.phone}</div>
                    <div>Items:</div>
                    <ul>
                        ${order.items.map(item => `<li>${item.name} - ${item.quantity} x Rp ${item.price.toLocaleString()}</li>`).join('')}
                    </ul>
                    <div class="order-total">Total: Rp ${order.total_amount.toLocaleString()}</div>
                `;

                // Add the order item to the list
                orderList.prepend(orderElement);

                // Call the function to auto-print the bill
                printOrderBill(order);
            });

        // Function to print the order bill (you can customize this to work with a thermal printer)
        function printOrderBill(order) {
            const orderDetails = `
                Order Bill
                Customer: ${order.customer_name}
                Table: ${order.table_id}
                Phone: ${order.phone}
                -------------------------
                Items:
                ${order.items.map(item => `${item.name} x ${item.quantity}`).join('\n')}
                -------------------------
                Total: Rp ${order.total_amount.toLocaleString()}
            `;

            // Trigger print
            const printWindow = window.open('', '', 'width=600,height=400');
            printWindow.document.write('<pre>' + orderDetails + '</pre>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>

</html>
