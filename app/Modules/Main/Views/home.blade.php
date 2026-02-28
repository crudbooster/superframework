<html>
    <head>
        <title>{{ config('app_name') }}</title>
        <style>.title {text-align: center;margin: 18% auto; font-family: 'Tahoma'; color: #999999} a {color: #999999} </style>
    </head>
    <body>
        <div class="title">
            <h1>{{ $message }}</h1>
            <p>{{ config('Main.tag_line') }}</p>
            <p>I Love PHP &hearts;</p>
            
            @if(count($users) > 0)
                <h3>Users list:</h3>
                <ul>
                    @foreach($users as $user)
                        <li>{{ $user['name'] }} ({{ $user['email'] }})</li>
                    @endforeach
                </ul>
            @endif

            <p><a href="https://github.com/crudbooster/superframework">{{ __("default_documentation")  }}</a></p>
        </div>
    </body>
</html>