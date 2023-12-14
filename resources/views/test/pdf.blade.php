@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Principal</i></div>
                        <div class="panel-body">
                            <button onclick="cargoModalArticulos()">Llamo Modal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('extra-javascript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    </head>
    <body>
    <button onclick="generatePDF()">Generate PDF</button>

    <script>
        window.jsPDF = window.jspdf.jsPDF;
        function generatePDF() {
            // Create a new jsPDF instance
            const doc = new jsPDF();

            // Add content to the PDF
            doc.text('Hello, World!', 10, 10);

            // Save the PDF
            doc.save('sample.pdf');
        }
    </script>
@stop
