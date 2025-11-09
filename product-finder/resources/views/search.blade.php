<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Search Results for "{{ $query }}"</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{
      --bg:#121212; --panel:#1e1e1e; --muted:#bbb; --border:#333; --field:#222; --accent:#4CAF50;
    }
    body{background:var(--bg); color:#eee;}
    .search-header{padding:1rem; background:var(--panel); box-shadow:0 2px 4px #000;}
    .search-header input[type="text"]{background:var(--field); color:#fff; border:1px solid var(--border);}
    .toolbar{display:flex; gap:.5rem; align-items:center; justify-content:space-between; margin:1rem 0;}
    .chip{border:1px solid var(--border); background:var(--panel); color:#fff; padding:.35rem .6rem; border-radius:999px; text-decoration:none;}
    .chip.active{border-color:var(--accent); color:#fff;}
    .product-row{
      background:var(--panel); border:1px solid var(--border); border-radius:10px;
      padding:.75rem; margin-bottom:.75rem; display:flex; align-items:center; gap:1rem;
    }
    .product-img{width:80px; height:80px; object-fit:contain; background:#222; border-radius:6px; flex-shrink:0;}
    .product-details{flex:1 1 auto; min-width:0;}
    .product-details h6{margin:0 0 .25rem 0; font-weight:600; line-height:1.2;}
    .store{min-width:110px; text-align:center; color:var(--muted);}
    .price{min-width:140px; text-align:right; font-weight:700; color:var(--accent);}
    .visit-btn{min-width:130px;}
    /*ADDING PAGINATION*/
    .pagination{justify-content:center;}
    .page-link{background:var(--panel); border:1px solid var(--border); color:#fff;}
    .page-link:hover{background:#2a2a2a;}
    .page-item.active .page-link{background:var(--accent); border-color:var(--accent);}
  </style>
</head>
<body>
  <div class="container">
    <div class="search-header position-sticky top-0" style="z-index:100;">
      <form action="{{ route('search') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="q" class="form-control form-control-lg" value="{{ $query }}" placeholder="Search products..." autofocus>
        @if($store)<input type="hidden" name="store" value="{{ $store }}">@endif
      </form>
    </div>

    <div class="toolbar">
      <div class="small text-muted">Showing {{ number_format($products->total()) }} results</div>
      <div class="d-flex gap-2 flex-wrap">
        <a class="chip {{ !$store ? 'active' : '' }}" href="{{ route('search', ['q'=>$query]) }}">All</a>
        @foreach($stores as $s)
            <a class="chip {{ mb_strtolower($store) === mb_strtolower($s) ? 'active' : '' }}"
            href="{{ route('search', ['q'=>$query, 'store'=>$s]) }}">{{ $s }}</a>
        @endforeach
      </div>
    </div>

    @forelse($products as $product)
      <div class="product-row">
        <img class="product-img"
             src="{{ $product->image ?: 'https://via.placeholder.com/80?text=No+Image' }}"
             alt="{{ $product->name }}">
        <div class="product-details">
          <h6 class="text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
        </div>
        <div class="price">{{ $product->formatted_price }}</div>
        <div class="store">{{ $product->store ?? 'Unknown' }}</div>
        <a href="{{ $product->link }}" target="_blank" class="btn btn-sm btn-outline-light visit-btn">View Product</a>
      </div>
    @empty
      <div class="alert alert-dark border-0">No results found.</div>
    @endforelse

    <div class="mt-3">
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  </div>
</body>
</html>
