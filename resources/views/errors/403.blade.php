<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied | VeLearn</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/errors-403.css') }}">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
    </div>
    <div class="container">
        <span class="shield-icon">🛡️</span>
        <h1 class="error-code">403</h1>
        <h1>Access Denied</h1>
        <p>Sorry, you don't have permission to access this page. Please contact your administrator if you believe this is an error.</p>
        <a href="{{ url('/') }}" class="btn-home">Return to Dashboard</a>
    </div>
</body>
</html>
