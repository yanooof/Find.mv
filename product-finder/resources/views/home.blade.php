<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Local Product Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #eee;
        }
        .search-box {
            max-width: 600px;
            margin: 20% auto;
        }
        input[type="text"] {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <form action="{{ route('search') }}" method="GET" class="search-box">
            <input type="text" name="q" class="form-control form-control-lg" placeholder="Search for a product..." autofocus>
        </form>
    </div>
</body>
</html>
