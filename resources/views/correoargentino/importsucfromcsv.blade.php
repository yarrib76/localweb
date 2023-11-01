@extends('layouts.master')
@section('contenido')
    <div>
        <body>
        <br/>
        <br/>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" style="padding:12px 0px;font-size:25px;"><strong>Importar Sucrsales Correo CSV</strong></h3>
                </div>
                <div class="panel-body">


                    @if ($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif


                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif


                    <h3>Importar Archivo Desde:</h3>
                    <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{ URL::to('importCsv') }}" class="form-horizontal" method="post" enctype="multipart/form-data">


                        <input type="file" name="import_file" />
                        {{ csrf_field() }}
                        <br/>
                         <button class="btn btn-primary">Importar EXCEL-CSV</button>
                    </form>
                    <br/>

                </div>
            </div>
        </div>


        </body>
    </div>

@stop