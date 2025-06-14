<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Supermarket POS</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom Global CSS -->
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            background: linear-gradient(135deg, #f0f2f5 0%, #e0e5ec 100%); /* Soft gradient background */
        }
        .welcome-container {
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .welcome-title {
            font-weight: 700;
            color: #FFD700; /* Gold for main title */
            margin-bottom: 1rem;
        }
        .welcome-text {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        .btn-login {
            background-color: #FFD700; /* Gold button */
            border-color: #FFD700;
            color: #333; /* Darker text for contrast on gold */
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.2s ease;
        }
        .btn-login:hover {
            background-color: #e0b400; /* Slightly darker gold on hover */
            border-color: #e0b400;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 215, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="welcome-container">
                    <h1 class="welcome-title">Welcome to Supermarket POS System!</h1>
                    <p class="welcome-text">
                        Your efficient solution for managing sales, inventory, and users in a modern retail environment.
                        Please log in to access the system functionalities.
                    </p>
                    <a href="login.php" class="btn btn-login">Proceed to Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom Global JS -->
    <script src="public/js/main.js"></script>
</body>
</html>
