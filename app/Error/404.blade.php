<html>
    <head>
        <title>404 Page Not Found | {{ config('app_name') }}</title>
        <style>.title {text-align: center;margin: 18% auto; font-family: 'Tahoma'; color: #999999} a {color: #999999} </style>
    </head>
    <body>
        <div class="title">
            <h2>404 Page Not Found</h2>
            <p>Please check your url, or click <a href="{{ url() }}">here</a> to go back</p>
        </div>
    </body>
</html>