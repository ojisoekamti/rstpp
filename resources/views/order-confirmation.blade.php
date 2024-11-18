<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f5f1;
            /* Light beige background */
            font-family: 'Courier New', Courier, monospace;
            /* Monospaced font for thermal look */
            font-size: 0.9rem;
            /* Smaller font */
        }

        .container {
            margin-top: 50px;
            max-width: 400px;
        }

        .order-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .order-item {
            background-color: #f5e1d0;
            /* Light brown background for order items */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px dashed #6d4c41;
            /* Dashed border to mimic a receipt */
        }

        .order-item h5 {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .order-price {
            font-size: 1.1rem;
            color: #d32f2f;
            /* Red color for price */
        }

        .order-total {
            font-size: 1rem;
            color: #6d4c41;
            font-weight: bold;
        }

        .total-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #6d4c41;
            /* Medium brown for total price */
        }

        .btn-custom {
            background-color: #6d4c41;
            /* Medium brown color */
            color: white;
            border-radius: 15px;
            font-weight: bold;
            height: 45px;
        }

        .btn-custom:hover {
            background-color: #4e342e;
            /* Dark brown on hover */
            color: white;
        }

        /* Small adjustments for a receipt-like format */
        .order-item p {
            margin: 2px 0;
            font-size: 0.9rem;
            /* Even smaller font for item details */
        }

        .order-header p {
            font-size: 0.85rem;
            /* Smaller font for the header description */
        }

        /* Spacer between items */
        .spacer {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="order-header">
            <h1>Order Confirmation</h1>
            <p>Please review your order before confirming.</p>
        </div>

        <!-- Order Items List -->
        <div id="order-list" class="mb-4">
            <!-- Order items will be listed here dynamically -->
        </div>

        <div class="text-center lh-sm" style="font-size: 0.8rem">
            <p> Your order <b>CAN NOT</b> be cancelled</p>
            <p>Our staff will contact you by telephone immediately.</p>

            <p>Thank you for your order.</p>
            --
            <p>Pesanan Anda <b>TIDAK DAPAT</b> dibatalkan.</p>
            <p>Petugas kami akan segera menghubungi Anda melalui telepon.</p>
            <p>Terima kasih atas pesanannya ya</p>
        </div>
        <!-- Total Price Section -->
        <div class="d-flex justify-content-between">
            <p class="total-price">Total: Rp 0</p>
            <button class="btn btn-custom" onclick="confirmOrder()">Confirm Order</button>
        </div>
    </div>

    <!-- Bootstrap JS and custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to load the order data from localStorage
        function loadOrderData() {
            const orderDataString = localStorage.getItem("orderData");

            if (orderDataString) {
                const orderData = JSON.parse(orderDataString);
                const orderList = document.getElementById("order-list");
                let total = 0;

                // Loop through the orderData and display items
                for (let itemId in orderData) {
                    const item = orderData[itemId];
                    const itemTotal = item.quantity * item.price;
                    total += itemTotal;

                    // Create the order item element
                    const listItem = document.createElement("div");
                    listItem.classList.add("order-item");
                    listItem.innerHTML = `
                        <h5>${item.name}</h5>
                        <p>Qty: ${item.quantity} x Rp ${item.price.toLocaleString("id-ID")}</p>
                        <p class="order-total">Total: Rp ${itemTotal.toLocaleString("id-ID")}</p>
                        <label for="notes-${itemId}" class="form-label mt-2">Add Notes:</label>
                        <textarea id="notes-${itemId}" class="form-control item-notes" rows="2" placeholder="E.g., No onions, extra cheese"></textarea>
                    `;
                    orderList.appendChild(listItem);
                }

                // Update total price
                document.querySelector(".total-price").textContent = `Total: Rp ${total.toLocaleString("id-ID")}`;
            } else {
                // If no order data found
                document.getElementById("order-list").innerHTML = "<p>No items ordered.</p>";
            }
        }

        // Function to confirm the order
        function confirmOrder() {
            // Retrieve the order data from localStorage
            const orderDataString = localStorage.getItem("orderData");
            const urlParams = new URLSearchParams(window.location.search);

            // Retrieve each parameter
            const name = urlParams.get('name'); // "Abdul Ghoji Hanggoro"
            const tableId = urlParams.get('table_id'); // "1"
            const phone = urlParams.get('phone'); // "08111211457"

            if (orderDataString) {
                const orderData = JSON.parse(orderDataString);

                // Prepare data to send to the backend
                const orderDetails = [];
                let totalAmount = 0;

                // Loop through the order data and create the order details array
                for (let itemId in orderData) {
                    const item = orderData[itemId];
                    const itemTotal = item.quantity * item.price;
                    totalAmount += itemTotal;

                    orderDetails.push({
                        itemId: itemId, // Assuming each item has an `itemId` or unique identifier
                        name: item.name,
                        quantity: item.quantity,
                        price: item.price,
                        total: itemTotal
                    });
                }

                // Send the order details to the server using fetch
                fetch('/order/confirm', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content') // Laravel CSRF token
                        },
                        body: JSON.stringify({
                            orderDetails: orderDetails,
                            totalAmount: totalAmount,
                            name: name,
                            tableId: tableId,
                            phone: phone
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data)
                        if (data.success) {
                            alert('Order confirmed and saved!');
                            localStorage.removeItem("orderData"); // Clear order data after confirmation
                            window.location.href = "/"; // Redirect to success page
                        } else {
                            alert('Error saving order. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            } else {
                alert('No order data found.');
            }
        }


        // Load the order data on page load
        window.onload = loadOrderData;
    </script>
</body>

</html>
