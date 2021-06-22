<h1>Hola, Verificar que ambos servicios esten en ON</h1>
<div class="panel-body">
    <table id="estado"  border="1">
        <thead>
        <tr>
            <th>Servicio</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Slave_IO_Running</td>
            <td>{{$Slave_IO_Running}}</td>
        </tr>
        <tr>
            <td>Slave_SQL_Running</td>
            <td>{{$Slave_SQL_Running}}</td>

        </tr>
        </tbody>
    </table>
    <table id="reporte" class="table table-striped table-bordered records_list" border="1">
        <thead>
        <tr>
            <th>Campo</th>
            <th>RegistroProd</th>
            <th>RegistroConti</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resFinal as $data)
            <tr>
                <td>{{$data['Campo']}}</td>
                <td>{{$data['RegistroProd']}}</td>
                <td>{{$data['RegistroConti']}}</td>
                <td>{{$data['Estado']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
<h1>El ultimo backup no puede superar los 3 días</h1>
<h3>Hace {{ $diasBackup }} días que no se realizan los backups.</h3>

