<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Desmond Tools</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset("css/style.css") }}">
    <script src="https://get.celcomdigi.com/global-assets/js/lottie.min.js"></script>
    @vite(["resources/css/app.css", "resources/js/app.js"])

    @livewireStyles
</head>

<body>
    <livewire:loading-modal />
    <div class="d-flex">
        <x-sidebar />
        <div class="flex-grow-1 main-wrapper">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
@stack("scripts")

</html>
