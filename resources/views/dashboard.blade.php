<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>halo</h1>

    <form action="/logout" method="POST">
        <!-- Tambahkan CSRF token jika kamu pakai Blade di Laravel -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Logout</button>
    </form>
</body>

</html>
