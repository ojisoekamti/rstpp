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
            font-family: Candara, Arial, sans-serif;
            /* Apply Calibri with fallback fonts */
            background-color: #f5f5f5;
            color: #4e342e;
        }

        .menu-header {
            text-align: center;
            padding: 30px 20px;
            background-color: #6d4c41;
            color: #fff;
            border-radius: 15px;
            margin: 20px auto;
            max-width: 800px;
        }

        .menu-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .menu-header p {
            font-size: 1.1rem;
        }

        .menu-container {
            margin: 30px auto;
            max-width: 800px;
        }

        .menu-item {
            background-color: #ffffff;
            border: 1px solid #d7ccc8;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .menu-item:hover {
            transform: scale(1.02);
        }

        .menu-item img {
            width: 350px;
            height: auto;
            object-fit: cover;
        }

        .menu-details {
            padding: 15px;
            flex-grow: 1;
        }

        .menu-details h4 {
            color: #3e2723;
            margin-bottom: 10px;
        }

        .menu-price {
            color: #795548;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn-order {
            background-color: #8d6e63;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-order:hover {
            background-color: #5d4037;
            color: #fff;
        }

        .order-controls button {
            width: 30px;
            height: 30px;
            padding: 0;
            font-size: 1rem;
        }

        footer {
            background-color: #6d4c41;
            color: #fff;
            padding: 15px;
        }

        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Tab Navigation */
        .nav-tabs .nav-link {
            font-size: 14px;
            color: #795548;
        }

        .nav-tabs .nav-link.active {
            background-color: #795548;
            color: white;
        }

        @media (max-width: 576px) {
            .menu-item {
                flex-direction: column;
                /* Stack elements vertically */
            }

            .menu-image img {
                width: 100%;
                /* Full width for small screens */
                height: auto;
                /* Fixed height for small screens */
                object-fit: cover;
                /* Maintain aspect ratio and crop */
            }

            .menu-details {
                text-align: center;
                /* Center-align text for mobile view */
                padding-top: 10px;
                /* Add some spacing from the image */
            }
        }
    </style>

</head>
@php
    $table_id = session('table_id', null); // Retrieve the order ID or return null if not set
    $name = session('name', null); // Retrieve the order ID or return null if not set
    $phone = session('phone', null); // Retrieve the order ID or return null if not set
    $order_id = session('order_id', null); // Retrieve the order ID or return null if not set
@endphp

<body>
    <div class="container ">
        <div class="menu-header">
            <h1>Welcome to Our Restaurant</h1>
            <p>Explore our delicious menu and treat yourself to something special.</p>
        </div>

        <div class="row menu-container">
            <div class="container mt-4">
                <ul class="nav nav-tabs justify-content-center" id="categoryTabs" role="tablist">
                    @foreach ($categories as $index => $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $category->id }}"
                                data-bs-toggle="tab" data-bs-target="#category-{{ $category->id }}" type="button"
                                role="tab" aria-controls="category-{{ $category->id }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                {{ $category->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3 justify-content-center" id="categoryTabsContent">
                    @foreach ($categories as $index => $category)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                            id="category-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                            @foreach ($menu_items->where('category_id', $category->id) as $item)
                                @php
                                    $images = $item->images ? json_decode($item->images) : [];
                                    $imageUrl = isset($images[0])
                                        ? Storage::url($images[0])
                                        : 'https://via.placeholder.com/750';
                                @endphp
                                <div class="menu-item d-flex mb-2">
                                    <div class="menu-image flex-shrink-0">
                                        <img src="{{ url($imageUrl) }}" alt="{{ $item->name }}"
                                            class="img-fluid rounded"
                                            onerror="this.src='https://via.placeholder.com/150';">
                                    </div>
                                    <div class="menu-details flex-grow-1 justify-content-center">
                                        <h4 class="mb-1 " style="">{{ $item->name }}</h4>
                                        <p class="text-muted small mb-1" style="text-align: justify">
                                            {!! $item->description !!}</p>
                                        <p class="menu-price mb-2">Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                        @if ($item->stock > 0)
                                            <button
                                                class="btn btn-sm btn-order w-100"onclick="toggleOrder(this, '{{ $item->id }}', '{{ addslashes($item->name) }}', {{ $item->price }}, {{ $item->stock }})">Tambahkan</button>
                                            <div
                                                class="order-controls d-none mt-2 d-flex justify-content-center align-items-center">
                                                <button class="btn btn-secondary btn-sm"
                                                    onclick="changeOrder(this, -1, '{{ $item->id }}')">-</button>
                                                <span class="order-quantity mx-2">0</span>
                                                <button class="btn btn-secondary btn-sm"
                                                    onclick="changeOrder(this, 1, '{{ $item->id }}', {{ $item->stock }})">+</button>
                                            </div>
                                        @else
                                            <div style="font-style: italic; color: red;">Out of Stock</div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <footer class="fixed-bottom bg-dark text-white d-flex justify-content-between align-items-center px-3 py-2">
            <div class="total-price">
                Total: <span id="total-price">Rp 0</span>
            </div>
            <div>
                @if ($order_id !== null)
                    <a href="/order-lists" class="btn btn-light">Order List</a>
                @endif
                <button class="btn btn-light" onclick="goToConfirmation()">Confirm Order</button>
            </div>
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
            function toggleOrder(button, itemId, itemName, itemPrice, maxStock) {
                const orderControls = button.nextElementSibling;

                if (orderControls.classList.contains("d-none")) {
                    // Hide the "Tambahkan" button and show the quantity controls
                    button.classList.add("d-none");
                    orderControls.classList.remove("d-none");

                    // Store the stock limit and initialize the quantity
                    orderControls.dataset.maxStock = maxStock;
                    orderControls.dataset.quantity = 1;

                    // Initialize item in orderData
                    orderData[itemId] = {
                        name: itemName,
                        price: itemPrice,
                        quantity: 1,
                    };

                    // Update the displayed quantity and total price
                    orderControls.querySelector(".order-quantity").textContent = 1;
                    updateTotalPrice();
                }
            }

            // Change the order quantity dynamically
            function changeOrder(button, delta, itemId) {
                const orderControls = button.parentElement;
                const quantitySpan = orderControls.querySelector(".order-quantity");

                // Get the current quantity and stock limit
                let currentQuantity = parseInt(quantitySpan.textContent, 10);
                const maxStock = parseInt(orderControls.dataset.maxStock, 10);

                // Update the quantity
                currentQuantity += delta;

                // Prevent negative quantities or exceeding max stock
                if (currentQuantity < 0) currentQuantity = 0;
                if (currentQuantity > maxStock) {
                    currentQuantity = maxStock;
                    alert("Stock limit reached!");
                }

                // Update the UI
                quantitySpan.textContent = currentQuantity;

                // Update the order data or remove the item if quantity is zero
                if (currentQuantity === 0) {
                    delete orderData[itemId];

                    // Reset controls to "Tambahkan" state
                    const addButton = orderControls.previousElementSibling;
                    orderControls.classList.add("d-none");
                    addButton.classList.remove("d-none");
                } else {
                    orderData[itemId].quantity = currentQuantity;
                }

                // Update the total price
                updateTotalPrice();
            }

            // Navigate to confirmation page with order data
            function goToConfirmation() {
                const orderDataString = JSON.stringify(orderData);
                const urlParams = new URLSearchParams(window.location.search);

                // Retrieve each parameter
                const tableId = '{{ $table_id }}'
                const name = '{{ $name }}'
                const phone = '{{ $phone }}'

                // Option 1: Pass via query string
                // location.href = `/confirmation?data=${encodeURIComponent(orderDataString)}`;

                // Option 2: Store in localStorage and redirect
                localStorage.setItem("orderData", orderDataString);
                location.href = `/confirmation?name=${name}&table_id=${tableId}&phone=${phone}`;
            }
        </script>
</body>

</html>
