<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">

        @vite(["resources//js/app.js", "resources/css/app.css"])
        @inertiaHead
    </head>

    <body>
        @inertia
    </body>

</html>
