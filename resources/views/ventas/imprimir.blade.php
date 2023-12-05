<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" href="/favicon.ico">
        <!-- Bootstrap CSS -->
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<title>Imprime Ticket</title>
        <!-- Mis estilos css -->
        <style type="text/css">
			body {
				font-size: 100%;
			}
            /* override styles when printing */
            @media print {
                body {
                    margin: 0;
                    color: #000;
                    font-family: 'Courier New', monospace;
                }
                /* Esconder secciones al imprimir */
                header, footer {
                    display: none!important;
                }
            }
            @page {
                margin-top: 1cm;
                margin-bottom: 1cm;
                margin-left: 2cm;
                margin-right: 1cm;
            }
            .ticket {
                font-family: 'Courier New', monospace;
            }
            .cellRight {
			  text-align: right;
			}
			p#razon {
				font-size: 1.1em;
			}
			p.data {
				font-size: 0.875em;
				margin: 1px;
			}
			hr.lin {
				margin: 20px;
			}
            button {
                width: 120px;
                margin-right: 10px;
            }
		</style>
	</head>
	<body>
		<header>
			<div class="container-fluid">
				<div class="row justify-content-end">
					<div class="col-3 mt-4">
						<button id="btnImprimir" type="button" class="btn btn-primary">Imprimir</button>
                        <button id="btnVolver" type="button" class="btn btn-info">Volver</button>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<hr class="lin">
					</div>
				</div>
			</div>
		</header>

		<div class="container-fluid ticket">
			<div class="row">
				<div class="col mt-4">
					<h3 class="text-center"><strong>{{ $sucursal }}</strong></h3>
				</div>
			</div>
            <div class="row">
                <div class="col-10 offset-1">
                    <h4 class="text-center"><strong>Pedido Nro. {{ $pedido_nro }}</strong></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3 mt-2 px-5 d-flex justify-content-between">
                    <p class="mb-0">Turno: {{ $turno_sucursal }}</p>
                    <p class="mb-0">S: {{ $serial }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-6 offset-3 mt-2 mb-0 px-5">
					<table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col" class="cellRight">Cant</th>
                                <th scope="col" class="text-center">Articulo</th>
                                <th scope="col" class="cellRight">Precio</th>
                                <th scope="col" class="cellRight">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detalle as $detal)
                                <tr>
                                    <td class="cellRight">{{ number_format($detal['cantidad'], 0,',', '.') }}</td>
                                    <td>{{ $detal['descripcion'] }}</td>
                                    <td class="cellRight">{{ number_format($detal['precio_unitario'], 2, ',', '.') }}</td>
                                    <td class="cellRight">&nbsp; {{number_format($detal['subtotal'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                      </tbody>
                  </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3 mt-0 px-5 d-flex justify-content-end">
                    <h3 class="m-0">$ {{ number_format($comprobante['total'], 2, ',', '.') }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="d-flex justify-content-center mt-1">
                    <p>Documento no valido como factura</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            // Para boton volver
            const btnVolver = document.querySelector('#btnVolver');
            btnVolver.addEventListener( "click", () => window.close() );

            // Boton Imprimir
            const btnImprimir = document.querySelector('#btnImprimir');
            btnImprimir.addEventListener( "click", () => window.print() );
        </script>
    </body>
</html>
