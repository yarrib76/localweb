@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">WhatsApp Marketing</i></div>
                    <div class="panel-body">
                        <!--Div where the WhatsApp will be rendered-->
                        <div id="WAButton"></div>
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('extra-javascript')
        <!--JQuery-->
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <!--Floating WhatsApp css-->
    <link rel="stylesheet" href="floating-wpp.min.css">
    <!--Floating WhatsApp javascript-->
    <script type="text/javascript" src="floating-wpp.min.js"></script>
    <script type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $('#WAButton').floatingWhatsApp({
                phone: 'WHATSAPP-PHONE-NUMBER', //WhatsApp Business phone number
                headerTitle: 'Chat with us on WhatsApp!', //Popup Title
                popupMessage: 'Hello, how can we help you?', //Popup Message
                showPopup: true, //Enables popup display
                buttonImage: '<img src="whatsapp.svg" />', //Button Image
                //headerColor: 'crimson', //Custom header color
                //backgroundColor: 'crimson', //Custom background button color
                position: "right" //Position: left | right

            });
        });
    </script>
@stop