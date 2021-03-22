<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="print.css" media="print" />
    <style>

        #fila21columna{
            margin-top: 0px;
            margin-left: 120px;
        }

        h5{
            margin-top: -4px;
            margin-bottom: -20px;
            text-align: center;
        }
        h6{
            margin-top: 15px;
            margin-left: 20px;
        }
    </style>
</head>
<body>

<div class="row">
    <table>
        <tr>
            <td>
                <div id="fila21columna" align="center">
                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($codigos['codigo'], 'EAN13',1,40)}}" alt="barcode" />
                    <h5>{{$codigos['codigo']}}</h5>
                    <h6>{{$codigos['texto']}}</h6>
                </div>

            </td>
            <td>

            </td>
            <td>

            </td>


    </table>
</div>
</body>
</html>