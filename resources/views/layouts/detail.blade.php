<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '2DAY - For Today')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
    
    <style>
        * {
            transition: all 0.3s ease;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
        }

        .gallery-main {
            aspect-ratio: 1;
            object-fit: cover;
        }

        .variant-option {
            padding: 10px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .variant-option:hover:not(:disabled) {
            border-color: #1f2937;
        }

        .variant-option.selected {
            border-color: #1f2937;
            background: #1f2937;
            color: white;
        }

        .variant-option:disabled {
            opacity: 0.5;
            cursor-not-allowed;
        }

        .spec-item {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .product-card {
            border: 1px solid #f0f0f0;
        }

        .product-card:hover {
            border-color: #e0e0e0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .slide-fade-in {
            animation: slideFadeIn 0.6s ease-out;
        }

        @keyframes slideFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-white text-gray-900">
    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    @include('partials.scripts')
    @stack('scripts')
    
    <!-- Load product detail script -->
    <script src="{{ asset('js/product-detail.js') }}"></script>
</body>

</html>