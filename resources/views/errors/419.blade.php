<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired | VeLearn</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/errors-419.css') }}">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
    </div>
    <div class="container">
        <span class="icon">⌛</span>
        <h1 class="error-code">419</h1>
        <h1>Page Expired</h1>
        <p>Sorry, your session has expired. Please refresh the page and try again.</p>
        <a href="{{ url()->previous() }}" class="btn-home">Try Again</a>
    </div>
</body>
</html>
