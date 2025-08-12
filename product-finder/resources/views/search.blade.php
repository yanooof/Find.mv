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
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background: #222;
            border-radius: 5px;
            flex-shrink: 0;
        }
        .product-details {
            flex-grow: 1;
        }
        .price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #4CAF50;
            min-width: 100px;
            text-align: right;
        }
        .store-name {
            font-size: 0.9rem;
            color: #bbb;
            min-width: 100px;
            text-align: center;
        }
        .visit-btn {
            min-width: 120px;
        }
        .pagination {
            justify-content: center;
        }
        .pagination .page-link {
            background-color: #1e1e1e;
            border: 1px solid #333;
            color: #fff;
        }
        .pagination .page-link:hover {
            background-color: #333;
        }
        .pagination .active .page-link {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Search Header -->
        <div class="search-header text-center">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" class="form-control form-control-lg" value="{{ $query }}" placeholder="Search again...">
            </form>
        </div>

        <!-- Results -->
        <div class="mt-4">
            <h5 class="text-center mb-4">Showing {{ $products->total() }} results</h5>

            @foreach($products as $product)
                <div class="product-box">
                    <!-- Product Image -->
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-img">
                    @else
                        <img src="https://via.placeholder.com/80?text=No+Image" alt="No Image" class="product-img">
                    @endif

                    <!-- Product Details -->
                    <div class="product-details">
                        <h6 class="mb-1">{{ $product->name }}</h6>
                    </div>

                    <!-- Price -->
                    <div class="price">
                        {{ 'MVR ' . preg_replace('/[^0-9.,]/', '', $product->price) }}
                    </div>

                    <!-- Store Name -->
                    <div class="store-name">
                        {{ $product->store ?? 'Unknown' }}
                    </div>

                    <!-- Visit Button -->
                    <a href="{{ $product->link }}" target="_blank" class="btn btn-sm btn-outline-light visit-btn">View Product</a>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->appends(['q' => $query])->links() }}
            </div>
        </div>
    </div>
</body>
</html>
