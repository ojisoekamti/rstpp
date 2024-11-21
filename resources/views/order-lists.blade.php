<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List with Auto Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .order-list {
            margin-top: 20px;
        }

        .order-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .order-title {
            font-weight: bold;
        }

        .order-total {
            margin-top: 10px;
            font-size: 1.2em;
            font-weight: bold;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>

<body>

    <h1>Order List</h1>
    <div id="order-list" class="order-list">
        <!-- Orders will be dynamically added here -->
    </div>

    <script>
        // Function to fetch the latest order from the API
        function fetchLatestOrder() {
            fetch('/api/get-latest-order')
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        console.log(data.message); // No orders found
                        return;
                    }

                    // Process and display the order data
                    displayOrder(data);
                    // Auto-print the order bill
                    printOrderBill(data);
                })
                .catch(error => console.error('Error fetching order:', error));
        }

        // Display the fetched order
        function displayOrder(order) {
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
            orderList.prepend(orderElement); // Add the new order at the top of the list
        }

        // Function to print the order bill
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
            console.log(printWindow);

            printWindow.document.write('<pre>' + orderDetails + '</pre>');
            printWindow.document.close();
            printWindow.print();
        }

        // Call the function to fetch and display the latest order
        fetchLatestOrder();
    </script>

</body>

</html>
