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
    

</div>
<h1>El ultimo backup no puede superar los 3 días</h1>
<h3>Hace {{ $diasBackup }} días que no se realizan los backups.</h3>

