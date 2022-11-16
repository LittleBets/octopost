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
<script>
    (function(o, m, g) {
        window[o] = window[o] || function() {
            (window[o].q = window[o].q || []).push(arguments)
        }
        var hd = document.getElementsByTagName('HEAD')[0]

        var js = document.createElement('script')
        js.id = o
        js.src = m
        js.async = true
        hd.appendChild(js)

        var link = document.createElement('link')
        link.rel = 'stylesheet'
        link.type = 'text/css'
        link.href = g
        hd.appendChild(link)
    }('Feedmas', 'https://f.feedmas.com/fw.js', 'https://f.feedmas.com/fw.css'))
    Feedmas('init', {'project': '08cb390d-df54-4100-a2b1-1e9c506cb308'})
</script>
</body>
</html>
