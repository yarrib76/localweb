<div id="navbar" class="navbar-collapse collapse">
    @if (Auth::check())
        @include('partials.header.usuarioRegistrado')
    @else
        @include('partials.header.usuarioInvitado')
    @endif
</div>