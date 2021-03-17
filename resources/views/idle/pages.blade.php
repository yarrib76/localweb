@section('extra-javascript')
<script type="text/javascript">
    idleTimer = null;
    idleState = false;
    idleWait = 20000;

    (function ($) {

        $(document).ready(function () {

            $('*').bind('mousemove keydown scroll', function () {

                clearTimeout(idleTimer);

                if (idleState == true) {

                    // Reactivated event
                }

                idleState = false;

                idleTimer = setTimeout(function () {

                    // Idle Event
                    window.location.replace("/notasadhesivas");

                    idleState = true; }, idleWait);
            });

            $("body").trigger("mousemove");

        });
    }) (jQuery)
</script>
@stop