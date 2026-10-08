<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Eligible | VeLearn</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/errors-403.css') }}">
    
</head>
<body>
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
    </div>
    <div class="container">
        <span class="shield-icon">🛑</span>
        <h1 class="error-code" style="font-size: 4rem; margin-bottom: 0;">Notice</h1>
        <h1>Access Restricted</h1>
        <p>You are not eligible to view the dashboard because your staff record was not found.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</body>
</html>
