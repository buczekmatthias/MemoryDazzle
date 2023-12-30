<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">

        @inertiaHead
        @vite(["resources//js/app.js", "resources/css/app.css"])
    </head>

    <body>
        @inertia
    </body>

</html>
