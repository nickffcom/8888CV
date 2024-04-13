<!DOCTYPE html>
<html lang="ja">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>チャットミーティング</title>
    <link rel="stylesheet" href="/css/adminlte.3.2.0.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/application.css') }}">

    @include('_cdn')
</head>

<body class="main-body">
    <div id="auth">
        <auth></auth>
    </div>
    <script src="{{ mix('js/auth.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

</body>

</html>
