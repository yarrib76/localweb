<nav class="navbar navbar-{{ Auth::check() ? "inverse" : "default" }} navbar-fixed-top" role="navigation">
<div class="container-fluid">
        @include('partials.header.izquierda')
        @include('partials.header.derecha')
</div>
</nav>
