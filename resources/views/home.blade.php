<!DOCTYPE html>
<html lang="ja">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Selling Account Ads</title>
    @include('_cdn')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">
</head>

<body class="main-body">
    <div id="app">
        <app></app>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

</body>

</html>
