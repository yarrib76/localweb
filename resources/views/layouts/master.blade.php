<html lang="es">
{{--Head--}}
<head>

    <title>Sistema</title>
    <link href="/css/app.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="/css/librerias/font-awesome/font-awesome.css" rel="stylesheet">
    @yield('extra-css')
</head>

{{-- Body --}}
<body>

    @include('partials.header.main')

    <div class="content">
        <li></li>
        <li></li>
        <li></li>
        @yield('contenido')
    </div>

</body>

@include('partials.footer')
@yield('extra-javascript')


</html>