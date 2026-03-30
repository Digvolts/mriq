@extends('layouts.home')

@section('content')
<main class="max-w-7xl mx-auto px-4 md:px-8 py-8 md:py-12">

    <!-- HERO SLIDESHOW -->
    @include('partials.hero-slideshow', ['newArrivals' => $newArrivals])

    <!-- COLLECTIONS -->
    @include('partials.collections-section', ['collections' => $collections])

    <!-- SEARCH BAR -->
    @include('partials.search-bar', [
        'groupedProducts' => $groupedProducts
    ])

    <!-- PRODUCTS -->
    @include('partials.products-section', [
        'products' => $products,
        'groupedProducts' => $groupedProducts
    ])

</main>
@endsection