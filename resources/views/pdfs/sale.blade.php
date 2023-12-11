<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        html {
            font-family: sans-serif;
        }

        body {
            font-size: 16px;
        }

        hr {
            border: .1px solid #000;
        }

        .header {
            text-align: center
        }

        table {
            width: 100%
        }

        tr {
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body>
    <h2>FERRETERIA LEYTON</h2>
    <p>RUC: <b>20550001234</b></p>
    <p>DIRECCIÓN: <b>CALLE.BERNARDO ALCEDO NRO. 236 INT. 01 (CERCA A LA IMPRENTA EL RELICARIO) PIURA - SULLANA -
            SULLANA</b></p>
    <p>TELEFONO: <b>944 010 839</b></p>
    <hr />
    <p>CLIENTE: <b>{{ $customer }}</b></p>
    <p>DNI: <b>{{ $dni }}</b></p>
    <div>
        <header class="header">
            <h3>BOLETA</h3>
            <p>FECHA EMISIÓN: <span>{{ $date }}</span></p>
        </header>
        <p>MEDIO DE PAGO: <span>EFECTIVO</span></p>
        <hr />
        <table>
            <thead>
                <th>PRODUCTO</th>
                <th>CANTIDAD</th>
                <th>PRECIO UNIT</th>
                <th>TOTAL</th>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ $product['price'] }}</td>
                        <td>{{ $product['total'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3">TOTAL</td>
                    <td colspan="1">{{ $total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
