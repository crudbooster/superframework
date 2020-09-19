<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? __("AdminAuth_auth") }}</title>
    <link rel="stylesheet" href="{{ asset("assets/bootstrap/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/admin.css") }}">
    <style>
        body {
            background: #eeeeee;
        }
    </style>
    @stack("head")
</head>
<body>
    <div class="container">
        @yield("content")
    </div>
    <script src="{{ asset("assets/bootstrap/js/jquery-3.4.1.min.js") }}"></script>
    <script src="{{ asset("assets/bootstrap/js/popper.min.js") }}"></script>
    <script src="{{ asset("assets/bootstrap/js/bootstrap.min.js") }}"></script>
    @stack("bottom")
</body>
</html>