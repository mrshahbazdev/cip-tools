<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdomain Not Found - 404 Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            font-size: 48px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Subdomain Not Found</h2>
        <p>The subdomain you are trying to access does not exist.</p>
        <p><small>Requested domain: <strong>{{ request()->getHost() }}</strong></small></p>
        <a href="{{ url('/') }}" class="btn">Go to Homepage</a>
    </div>
</body>
</html>
