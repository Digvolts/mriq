<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '2DAY - For Today')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">    
    @stack('styles')
</head>

<body class="bg-white text-gray-900">
    
    @include('partials.header')
    
    @yield('content')

    @include('partials.footer')
    
    @stack('scripts')

</body>

</html>