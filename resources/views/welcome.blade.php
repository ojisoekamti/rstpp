<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fancy Menu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f39c12, #8e44ad);
            color: #fff;
        }

        .menu-header {
            text-align: center;
            padding: 40px 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            margin: 20px auto;
            max-width: 900px;
        }

        .menu-header h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .menu-container {
            margin: 40px auto;
        }

        .menu-item {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .menu-details {
            padding: 15px;
            color: #333;
        }

        .menu-price {
            color: #27ae60;
            font-weight: bold;
        }

        .btn-order {
            background-color: #8e44ad;
            color: #fff;
            border: none;
        }

        .btn-order:hover {
            background-color: #732d91;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <div class="menu-header">
            <h1>Welcome to Our Restaurant</h1>
            <p>Discover the most delicious dishes crafted with love and perfection.</p>
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
                <div class="col-12 col-md-4">
                    <div class="menu-item">
                        <img src="{{ url($image) }}" alt="Menu Item {{ $item->name }}">
                        <div class="menu-details">
                            <h5>Menu Item {{ $item->name }}</h5>
                            <p>Category: Category {{ $item->category_id }}</p>
                            <p class="menu-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            <button class="btn btn-order w-100">Tambahkan</button>
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
