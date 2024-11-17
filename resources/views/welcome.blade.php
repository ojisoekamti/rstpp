<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Menu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #3e2723, #6d4c41); /* Dark brown gradient */
            color: #fff;
        }

        .menu-header {
            text-align: center;
            padding: 40px 20px;
            background: rgba(0, 0, 0, 0.6); /* Slightly darker header for contrast */
            border-radius: 15px;
            margin: 20px auto;
            max-width: 900px;
        }

        .menu-header h1 {
            font-size: 3rem;
            font-weight: bold;
            color: #f5f5f5; /* Light text color for contrast */
        }

        .menu-header p {
            font-size: 1.2rem;
            color: #f1e0c5; /* Light beige color for description text */
        }

        .menu-container {
            margin: 40px auto;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.9); /* White background with transparency */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .menu-item {
                flex-direction: row; /* Stack horizontally on larger screens */
            }
        }

        .menu-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }

        @media (min-width: 768px) {
            .menu-item img {
                width: 40%; /* Adjust width for larger screens */
                height: auto;
                border-radius: 15px 0 0 15px;
            }
        }

        .menu-details {
            padding: 15px;
            color: #4e342e; /* Dark brown for text */
            flex: 1; /* Ensure the details take up remaining space */
        }

        .menu-price {
            color: #d32f2f; /* Red for price to stand out */
            font-weight: bold;
        }

        .btn-order {
            background-color: #6d4c41; /* Medium brown button */
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 12px;
        }

        .btn-order:hover {
            background-color: #4e342e; /* Dark brown on hover */
            color: #fff;
        }
        
        footer {
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2); /* Add a shadow to the footer */
            z-index: 10;
        }
        
        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffc107; /* Highlighted color for the price */
        }
        
        .btn-light {
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 25px;
        }

    </style>
</head>

<body>
    <div class="container text-center">
        <div class="menu-header">
            <h1>Welcome to Our Restaurant</h1>
            <p>Explore our delicious menu and treat yourself to something special.</p>
        </div>

        <div class="row menu-container">
            <!-- Loop through dummy menu items -->
            @foreach ($menu_item as $item)
                @php
                    $image = $item->images;
                    $image = json_decode($image);
                    $image = $image[0];
                    $image = Storage::url($image);
                @endphp
                <div class="col-12 col-md-6">
                    <div class="menu-item">
                        <img src="{{ url($image) }}" alt="Menu Item {{ $item->name }}">
                        <div class="menu-details">
                            <h4>{{ $item->name }}</h4>
                            {!! $item->description !!}
                            <p class="menu-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            <button class="btn btn-order w-100" onclick="toggleOrder(this, '{{ $item->id }}', '{{ $item->name }}', {{ $item->price }})">Tambahkan</button>
                            <div class="order-controls d-none">
                                <button class="btn btn-secondary btn-sm" onclick="changeOrder(this, -1, '{{ $item->id }}')">-</button>
                                <span class="order-quantity mx-2">0</span>
                                <button class="btn btn-secondary btn-sm" onclick="changeOrder(this, 1, '{{ $item->id }}')">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <footer class="fixed-bottom bg-dark text-white d-flex justify-content-between align-items-center px-3 py-2">
        <div class="total-price">
            Total: <span id="total-price">Rp 0</span>
        </div>
        <button class="btn btn-light" onclick="goToConfirmation()">Confirm Order</button>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let orderData = {};
        
        // Update the total price dynamically
        function updateTotalPrice() {
            let total = Object.values(orderData).reduce(
                (sum, item) => sum + item.quantity * item.price,
                0
            );
            document.getElementById("total-price").textContent = `Rp ${total.toLocaleString("id-ID")}`;
        }
        
        // Toggle between "Tambahkan" and quantity controls
        function toggleOrder(button, itemId, itemName, itemPrice) {
            const orderControls = button.nextElementSibling;
            if (orderControls.classList.contains("d-none")) {
                button.classList.add("d-none");
                orderControls.classList.remove("d-none");
        
                // Initialize item in orderData
                orderData[itemId] = {
                    name: itemName,
                    price: itemPrice,
                    quantity: 1,
                };
                console.log(orderData);
        
                orderControls.querySelector(".order-quantity").textContent = 1; // Start with 1
                updateTotalPrice(); // Update total
            }
        }
        
        // Change the order quantity dynamically
        function changeOrder(button, delta, itemId) {
            const quantitySpan = button.parentElement.querySelector(".order-quantity");
            console.log(delta)
            let currentQuantity = parseInt(quantitySpan.textContent, 10);
            currentQuantity += delta;
        
            // Prevent negative quantity
            if (currentQuantity < 0) currentQuantity = 0;
        
            quantitySpan.textContent = currentQuantity;
        
            // Update orderData
            if (currentQuantity === 0) {
                delete orderData[itemId]; // Remove item if quantity is 0
                const orderControls = button.parentElement;
                const addButton = orderControls.previousElementSibling;
                orderControls.classList.add("d-none");
                addButton.classList.remove("d-none");
            } else {
                orderData[itemId].quantity = currentQuantity;
            }
        
            updateTotalPrice(); // Update total
        }
        
        // Navigate to confirmation page with order data
        function goToConfirmation() {
            const orderDataString = JSON.stringify(orderData);
            const urlParams = new URLSearchParams(window.location.search);
            
            // Retrieve each parameter
            const name = urlParams.get('name');  // "Abdul Ghoji Hanggoro"
            const tableId = urlParams.get('table_id');  // "1"
            const phone = urlParams.get('phone');  // "08111211457"

            // Option 1: Pass via query string
            // location.href = `/confirmation?data=${encodeURIComponent(orderDataString)}`;
        
            // Option 2: Store in localStorage and redirect
            localStorage.setItem("orderData", orderDataString);
            location.href = `/confirmation?name=${name}&table_id=${tableId}&phone=${phone}`;
        }

    </script>
</body>

</html>
