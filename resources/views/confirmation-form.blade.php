<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts (for a fancy font) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f4e1d2;
            /* Light brown background */
            font-family: Candara, Arial, sans-serif;
            /* Apply Calibri with fallback fonts */
            padding: 50px 0;
        }

        .container {
            max-width: 600px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 30px;
            border: none;
        }

        .card h2 {
            font-weight: 600;
            color: #5d4037;
            /* Dark brown color for headings */
        }

        .form-label {
            font-weight: 500;
            color: #5d4037;
            /* Dark brown color for labels */
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #a1887f;
            /* Light brown border */
            padding: 10px;
            background-color: #fff8e1;
            /* Light brown input background */
            color: #5d4037;
        }

        .form-control:focus {
            border-color: #8d6e63;
            /* Slightly darker brown focus border */
            box-shadow: 0 0 5px rgba(141, 110, 99, 0.5);
            /* Focus effect */
        }

        .btn-primary {
            background-color: #6d4c41;
            /* Dark brown button */
            border-color: #6d4c41;
            border-radius: 25px;
            padding: 12px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #8d6e63;
            /* Hover effect */
            border-color: #8d6e63;
        }

        .alert-danger {
            background-color: #f8d7da;
            /* Light red alert background */
            color: #721c24;
            /* Red text */
            border-color: #f5c6cb;
            /* Border color */
            border-radius: 5px;
            padding: 10px;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 30px;
            color: #5d4037;
        }

        /* Fancy animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .card {
            animation: fadeIn 1s ease-in;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <h2 class="text-center mb-4">Enter Your Information</h2>

            <!-- Displaying validation errors if any -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/" method="GET">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="table_id" class="form-label">Room Number:</label>
                    <select id="table_id" name="table_id" class="form-control" required>
                        <option value="" disabled selected>Select a Room</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table->id }}">
                                {{ $table->table_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Confirmation:</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit</button>
                <br />
                <br />
                <br />
                <br />
                <a type="submit" class="btn btn-primary w-100" href="/?table_id={{ request()->route('id') }}">Skip for
                    later</a>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 D'Bamboo Suites | All rights reserved.</p>
    </div>

    <!-- Bootstrap JS (optional, for some interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
