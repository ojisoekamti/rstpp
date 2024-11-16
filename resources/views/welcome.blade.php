<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .menu-item {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .menu-details {
            padding: 15px;
        }

        .menu-price {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Our Menu</h1>
        <div class="row">
            <!-- Loop through dummy menu items -->
            @foreach (range(1, 6) as $item)
                <div class="col-12 col-md-4">
                    <div class="menu-item">
                        <img src="https://via.placeholder.com/350x200" alt="Menu Item {{ $item }}">
                        <div class="menu-details">
                            <h5>Menu Item {{ $item }}</h5>
                            <p>Category: Category {{ rand(1, 3) }}</p>
                            <p class="menu-price">Rp {{ number_format(rand(10, 100) * 1000, 0, ',', '.') }}</p>
                            <button class="btn btn-primary w-100">Order Now</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
