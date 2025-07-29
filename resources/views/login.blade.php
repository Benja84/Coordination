<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/login.js'])   {{-- single entry point --}}
</head>
<body>
    <div id="login"></div>
</body>
</html>