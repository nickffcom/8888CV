<!DOCTYPE html>
<html lang="vn">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="Hệ thống bán BM, Via & Clone VN uy tính">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="{{ LOGO_TEXT }} - Hệ thống bán BM, Via & Clone VN uy tín - giá rẻ">
    <meta property="og:description" content="Hệ thống bán BM, Via & Clone VN uy tính">
    <meta property="og:image" content="{{ asset('/images/banner.png') }}">

    <link rel="shortcut icon" href="{{ asset('images/fb.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" id="css-main"
        href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
    <link rel="stylesheet" href="{{ mix('user/css/application.css') }}">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
    </style>
</head>

<body class="main-body">
    <div id="app">
        <app></app>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script> --}}
    <script src="{{ mix('user/js/app.js') }}"></script>

</body>

</html>
