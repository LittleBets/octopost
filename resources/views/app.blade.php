<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class='h-full'>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Octopost') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Scripts -->
    @routes
{{--    @if(config('app.env') === 'local')--}}
{{--        <script>--}}
{{--            Ziggy.url = '{{ config('APP_URL') }}'--}}
{{--        </script>--}}
{{--    @endif--}}
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased overflow-y-hidden h-full">
@inertia
</body>
</html>
