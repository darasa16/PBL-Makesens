<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MakeSens</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tempat CSS Spesifik Halaman -->
    @stack('styles')
</head>
<body>
    @yield('content')

    <script>
        lucide.createIcons();
    </script>
    <!-- Tempat JS Spesifik Halaman -->
    @stack('scripts')
</body>
</html>