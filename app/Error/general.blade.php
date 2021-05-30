<html>
<head>
    <title>{{ $error_name }} | {{ config('app_name') }}</title>
    <style>.title {text-align: center;margin: 18% auto; font-family: 'Tahoma'; color: #999999} a {color: #999999} </style>
</head>
<body>
<div class="title">
    <h2>{{ $error_name }}</h2>
    @if(isset($error_description))
        {!! $error_description !!}
    @else
        <p>Oops something went wrong, click <a href="{{ $_SERVER['HTTP_REFERER']?:url() }}">here</a> to go back</p>
    @endif
</div>
</body>
</html>