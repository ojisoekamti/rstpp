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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
                flex-wrap: nowrap;
                /* Prevent stacking */
            }

            .menu-image img {
                width: 185px;
                height: auto;
                object-fit: cover;
            }

            .menu-item {
                padding: 10px;
            }

            .menu-details h4 {
                font-size: 1rem;
            }

            .menu-details {
                font-size: 0.5rem;
            }
        }
    </style>

</head>

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
                                    <div class="menu-image me-2 flex-shrink-0">
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
                                        <button class="btn btn-sm btn-order w-100"
                                            onclick="toggleOrder(this, '{{ $item->id }}', '{{ addslashes($item->name) }}', {{ $item->price }})">Tambahkan</button>
                                        <div
                                            class="order-controls d-none mt-2 d-flex justify-content-center align-items-center">
                                            <button class="btn btn-secondary btn-sm"
                                                onclick="changeOrder(this, -1, '{{ $item->id }}')">-</button>
                                            <span class="order-quantity mx-2">0</span>
                                            <button class="btn btn-secondary btn-sm"
                                                onclick="changeOrder(this, 1, '{{ $item->id }}')">+</button>
                                        </div>

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
                const name = urlParams.get('name'); // "Abdul Ghoji Hanggoro"
                const tableId = urlParams.get('table_id'); // "1"
                const phone = urlParams.get('phone'); // "08111211457"

                // Option 1: Pass via query string
                // location.href = `/confirmation?data=${encodeURIComponent(orderDataString)}`;

                // Option 2: Store in localStorage and redirect
                localStorage.setItem("orderData", orderDataString);
                location.href = `/confirmation?name=${name}&table_id=${tableId}&phone=${phone}`;
            }
        </script>
</body>

</html>
