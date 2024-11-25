<!-- resources/views/orders/index.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-button {
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .card-button:hover {
            transform: scale(1.02);
        }

        .card-pending {
            background-color: #f0ad4e;
        }

        .card-preparing {
            background-color: blue;
        }

        .card-served {
            background-color: green;
        }
    </style>
</head>

<body>
    <div class="container mt-3">
        <h1 class="text-center">Order List</h1>
        <div id="order-notifications" class="row">
            <!-- New orders will be added here -->
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>

    <script>
        let lastOrderId = 0;
        let userInteracted = false;
        const audio = new Audio('/notification.wav');

        // Listen for user interaction
        document.addEventListener('click', () => {
            userInteracted = true;
        });

        function fetchNewOrders() {
            axios.get('/api/orders/new', {
                    params: {
                        last_order_id: lastOrderId
                    }
                })
                .then(response => {
                    const orders = response.data.orders;
                    if (orders && orders.length > 0) {
                        orders.forEach(order => {
                            if (order.status == 'pending')
                                autoPrintOrder(order)
                            order.items.forEach(item => {
                                displayNewOrder(item, order);
                            })
                            lastOrderId = Math.max(lastOrderId, order.id);
                        });
                    }
                })
                .catch(error => console.error('Error fetching orders:', error));
        }

        function displayNewOrder(item, order) {
            const orderNotifications = document.getElementById('order-notifications');
            const newOrder = document.createElement('div');
            newOrder.className = 'col-12 col-md-3 mb-4';
            let cardClass = 'card-pending';
            if (order.item.status == 'served') {
                cardClass = 'card-preparing';
            } else if (order.item.status == 'preparing') {
                cardClass = 'card-served';
            }
            // Insert relevant information into the new card
            newOrder.innerHTML = `
                <div class="card card-button border-primary p-3 ${cardClass}" onclick="handleCardClick(this, ${item.id})">
                    <div class="card-body text-center">
                        <h5 class="card-title">Order ID: ${order.id}</h5>
                        <p class="card-text"><strong>Room ID:</strong> ${order.table.table_name}</p>
                        <p class="card-text"><strong>Customer:</strong> ${order.customer_name}</p>
                        <p class="card-text"><strong>Phone:</strong> ${order.phone}</p>
                        <p class="card-text"><strong>Item:</strong> ${item.product_item.name}</p>
                        <p class="card-text"><strong>Quantity:</strong> ${item.quantity}</p>
                        <p class="card-text"><strong>Notes:</strong> ${item.notes ? item.notes : 'No notes'}</p>
                    </div>
                </div>
            `;
            // Prepend the new order card to the top of the list
            orderNotifications.prepend(newOrder);
            console.log(order.status);

            if (order.status == 'pending') {
                audio.play().catch(err => console.error('Audio playback failed:', err));
            }

        }

        function autoPrintOrder(order, item) {
            try {

                const printContent = `
                Order ID: 
                Room: 
                Customer: 
                Phone: 
                Item: 
                Quantity: 
                Notes: 
            `;

                qz.websocket.connect().then(() => {
                    return qz.printers.find(); // Automatically find the default printer
                }).then(printer => {
                    const config = qz.configs.create(printer); // Create a printer config
                    const data = [{
                        type: 'raw',
                        format: 'plain',
                        data: printContent
                    }];
                    return qz.print(config, data);
                }).catch(err => console.error('Error printing order:', err));
            } catch (error) {
                console.error(error);
            }
        }

        function handleCardClick(cardElement, orderId) {
            // Determine the current status based on the card's class
            let currentStatus = 'pending'; // Default status

            if (cardElement.classList.contains('card-preparing')) {
                currentStatus = 'preparing';
            } else if (cardElement.classList.contains('card-served')) {
                currentStatus = 'served';
            }

            // Define the next status and corresponding class based on the current status
            let nextStatus = '';
            let nextClass = '';
            let nextColor = ''; // Optional: Add specific color changes for each status

            if (currentStatus === 'pending') {
                nextStatus = 'preparing';
                nextClass = 'card-preparing';
                nextColor = 'yellow'; // Change to yellow for preparing
            } else if (currentStatus === 'preparing') {
                nextStatus = 'served';
                nextClass = 'card-served';
                nextColor = 'green'; // Change to green for served
            } else {
                // If already served, do nothing
                console.log(`Order #${orderId} is already served.`);
                return;
            }

            // Update the status via API
            fetch(`/api/update-order-status/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: nextStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the card's classes and style based on the new status
                        cardElement.classList.remove('card-pending', 'card-preparing', 'card-served');
                        cardElement.classList.add(nextClass);
                        cardElement.style.backgroundColor = nextColor; // Optional: Apply color directly
                        console.log(`Order #${orderId} updated to '${nextStatus}' successfully.`);
                    } else {
                        console.log(`Failed to update Order #${orderId}.`);
                    }
                })
                .catch(err => {
                    console.error('Error updating order status:', err);
                    alert('An error occurred while updating the order status.');
                });
        }

        function viewOrder(orderId) {
            window.location.href = `/order/${orderId}`;
        }

        setInterval(fetchNewOrders, 5000);
    </script>
</body>

</html>
