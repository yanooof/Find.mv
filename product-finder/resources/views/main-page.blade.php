<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e1e1e, #121212, #1b1b1b);
            color: white;
            font-family: Arial, sans-serif;
        }

        .search-container {
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .logo {
            font-size: 5rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, #ffcc00 0%, #ff9900 50%, #ffcc00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(255, 204, 0, 0.25);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .search-box {
            background-color: #222;
            border: 1px solid #444;
            color: #fff;
            font-size: 1.2rem;
            border-radius: 50px;
            padding: 0.8rem 1.5rem;
        }

        .search-box:focus {
            outline: none;
            border-color: #ffcc00;
            box-shadow: 0 0 8px #ffcc00;
        }

        .btn-search {
            margin-top: 1.2rem;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            background: linear-gradient(90deg, #ffcc00 0%, #ff9900 50%, #ffcc00 100%);
            color: black;
            font-weight: bold;
            border: none;
        }

        .btn-search:hover {
            background-color: #e6b800;
        }
    </style>
</head>
<body>

<div class="search-container">
    <div class="logo">FIND.MV</div>
    <form action="{{ route('search') }}" method="GET">
        <input type="text" name="q" class="form-control search-box" placeholder="Search for products...">
        <button type="submit" class="btn btn-search">Search</button>
    </form>
</div>

</body>
</html>
