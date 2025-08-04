<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results for "{{ $query }}"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #eee;
        }
        .search-header {
            padding: 1rem;
            background-color: #1e1e1e;
            box-shadow: 0 2px 4px #000;
        }
        input[type="text"] {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
        }
        .product-box {
            background: #1e1e1e;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-header text-center">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" class="form-control form-control-lg" value="{{ $query }}" placeholder="Search again...">
            </form>
        </div>

        <div class="mt-4">
            <h5 class="text-center">Showing {{ $products->count() }} results</h5>

            @foreach($products as $product)
                <div class="product-box">
                    <a href="{{ $product->link }}" target="_blank" class="btn btn-sm btn-outline-light float-start">Visit Site</a>
                    <h5>{{ $product->name }}</h5>
                    <p class="mb-1"><strong>Price:</strong> {{ $product->price }}</p>
                    {{-- Add unit info here later if available --}}
                </div>
            @endforeach

            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(['q' => $query])->links() }}
            </div>
        </div>
    </div>
</body>
</html>
