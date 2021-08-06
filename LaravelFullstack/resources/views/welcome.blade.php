<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="/css/all.css" rel="stylesheet">
        <script>
            window.Laravel = {"csrfToken":"{{ csrf_token() }}"}
        </script>
    </head>
    <body>
        <div id="app">
            @if(Auth::check())
                <main-app :user="{{Auth::user()}}" :permission=
                "{{Auth::user()->role->permission}}"></main-app>
            @else
                <main-app :user="false"></main-app>
            @endif
        </div>
    </body>
    <script src="{{mix('js/app.js')}}"></script>
</html>
