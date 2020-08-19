<ul class="nav navbar-nav navbar-right">
    <li class="dropdown ">
        <a href="#" class="dropdown-toggle fa fa-user" data-toggle="dropdown">
            {{' ' . Auth::user()->name }} <b class="caret "></b>
        </a>
        <ul class="dropdown-menu">
            <li><a href="/auth/logout" id="salir" name="salir"><i class="fa fa-btn fa-sign-out"></i>Salir</a></li>
        </ul>
    </li>
</ul>