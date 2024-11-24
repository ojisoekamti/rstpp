<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f5f1;
            /* Light beige background */
            font-family: Candara, Arial, sans-serif;
            /* Apply Calibri with fallback fonts */
            font-size: 0.9rem;
            /* Smaller font */
        }

        .container {
            margin-top: 50px;
            max-width: 400px;
        }

        h3 {
            text-align: center;
            font-size: 16px;
            margin: 5px 0;
        }

        .card {
            border: 1px solid #000;
            font-size: 12px;
        }

        .card-body {
            padding: 10px;
        }

        .table {
            font-size: 8pt;
        }

        .table thead {
            background-color: #4e342e;
            color: white;
            text-transform: uppercase;
            text-align: center;
        }

        .table tbody tr {
            background: #f8f9fa;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .table tbody td,
        .table thead th {
            padding: 5px;
            vertical-align: middle;
        }

        .table tbody td:first-child,
        .table thead th:first-child {
            text-align: left;
            padding-left: 20px;
        }

        .total-cell {
            font-weight: bold;
            color: #007bff;
        }

        .text-right {
            text-align: right;
        }


        .timeline {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #ddd;
            background-color: white;
            border-radius: 10px;
        }

        .timeline-item {
            position: relative;
            text-align: center;
            flex: 1;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: #ddd;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: background-color 0.3s ease-in-out;
        }

        .timeline-item.active::before {
            background-color: #4CAF50;
            /* Green for active status */
        }

        .timeline-item.completed::before {
            background-color: #2196F3;
            /* Blue for completed status */
        }

        .timeline-item.pending::before {
            background-color: #FF9800;
            /* Orange for pending status */
        }

        .timeline-item .label {
            margin-top: 30px;
            font-size: 14px;
            font-weight: bold;
        }

        .timeline-progress {
            width: 100%;
            height: 2px;
            background-color: #ddd;
            margin: 0 5px;
            position: absolute;
            margin-top: 25px;
            left: 0;
            z-index: -1;
            transition: width 0.5s ease;
        }

        .timeline-item.active~.timeline-item .timeline-progress {
            width: 100%;
        }

        .timeline-item.completed~.timeline-item .timeline-progress {
            width: 100%;
        }

        /* Animation class */
        .timeline-item {
            animation: fadeIn 2s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .btn-order {
            background-color: #4e342e;
            border: #4e342e;
        }

        .btn-order:hover {
            background-color: #000;
            border: #000;
        }
    </style>
</head>

<body>
    <div class="container ">
        <h1 class="text-center">Order Details</h1>
        <h3>Refresh for update status</h3>

        <!-- Order Header Section -->
        <div class="mt-4" id="order-header">
            <!-- Order header will be dynamically populated -->
        </div>
        <br>
        <!-- Order Items Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="order-items">
                <!-- Order items will be dynamically populated -->
            </tbody>
        </table>
        <div>
            <a href="/?table_id={{ $table_id }}&name={{ $name }}&phone={{ $phone }}"
                class="btn btn-primary w-100 btn-order">Order Again</a>
        </div>
    </div>

    <script>
        // Fetch order details by ID
        function fetchOrderDetails() {
            fetch(`/api/get-orders/{{ $order_id }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderOrderDetails(data.orders);
                        updateTimelineStatus(data.orders.status);
                    } else {
                        alert('Failed to fetch order details.');
                    }
                })
                .catch(err => console.error('Error fetching order details:', err));
        }

        // Render the order details (header and items)
        function renderOrderDetails(order) {
            let totalAmt = 0;
            order.items.map((item, index) => {
                totalAmt += item.price * item.quantity;
            })
            // Render order header
            const header = `
                <div class="card">
                    <div class="card-body">
                        <h4>Order #${order.id}</h4>

                        <table>
                            <tr>
                                <td><strong>CUSTOMER</strong></td>
                                <td>:</td>
                                <td>${order.customer_name}</td>
                            </tr>
                            <tr>
                                <td><strong>${order.table.type.toUpperCase()}</strong></td>
                                <td>:</td>
                                <td>${order.table.table_name}</td>
                            </tr>
                            <tr>
                                <td><strong>PHONE</strong></td>
                                <td>:</td>
                                <td>${order.phone}</td>
                            </tr>
                            <tr>
                                <td><strong>TOTAL AMOUNT</strong></td>
                                <td>:</td>
                                <td>Rp ${totalAmt.toLocaleString()}</td>
                            </tr>
                        </table>

                        <div class="timeline">
                            <div class="timeline-item" id="pending" data-status="pending">
                                <div class="timeline-progress"></div>
                                <div class="label">Pending</div>
                            </div>
                            <div class="timeline-item" id="prepared" data-status="prepared">
                                <div class="timeline-progress"></div>
                                <div class="label">Prepared</div>
                            </div>
                            <div class="timeline-item" id="served" data-status="served">
                                <div class="timeline-progress"></div>
                                <div class="label">Served</div>
                            </div>
                            <div class="timeline-item" id="complete" data-status="complete">
                                <div class="timeline-progress"></div>
                                <div class="label">Complete</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById("order-header").innerHTML = header;

            // Render order items
            const itemsTable = document.getElementById("order-items");
            itemsTable.innerHTML = order.items.map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.product_item.name}<br><span style="font-size:0.5rem">${item.notes}</span></td>
                    <td style="text-align:center;">${item.quantity}</td>
                    <td style="text-align:right;">Rp ${item.price.toLocaleString()}</td>
                    <td style="text-align:right;">Rp ${(item.quantity * item.price).toLocaleString()}</td>
                </tr>
            `).join('');

        }

        function updateTimelineStatus(status) {
            console.log(status);

            const timelineItems = document.querySelectorAll('.timeline-item');
            console.log(timelineItems);

            // Reset all timeline items
            timelineItems.forEach(item => {
                item.classList.remove('active');
                item.classList.remove('completed');
            });

            console.log(timelineItems);
            // Mark the current and previous steps as active/completed based on status
            let currentStatus = false;
            timelineItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                if (itemStatus === status) {
                    currentStatus = true;
                    item.classList.add('active');
                } else {
                    item.classList.add('completed');
                }
            });
        }


        // Call the function to start the timeline update

        fetchOrderDetails({{ $order_id }});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
