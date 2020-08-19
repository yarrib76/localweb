<!-- Static navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {!! HTML::linkRoute('home', 'Tala Flota Admin', null, array('class' => 'navbar-brand') ) !!}
        </div>
        <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="/auth/login"><i class="fa fa-btn fa-sign-in"></i>Ingresar</a></li>
            </ul>

        </div>
    </div>
</nav>