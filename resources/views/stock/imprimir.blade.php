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
				font-size: 0.95em;
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
					<h3 class="text-center"><strong>{{ $sucursal->nombre }}</strong></h3>
				</div>
			</div>
            <div class="row">
                <div class="col">
                    <hr class="lin">
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3">
                    <p class="data">Entrada Stock Nro. <strong>{{ $comprobante[0]['id'] }}</strong></p>
                    <p class="data">Motivo: {{ $comprobante[0]['descripcion'] }}</p>
                    <p class="data">Fecha: {{ Carbon\Carbon::parse($comprobante[0]['fecha'])->format('d/m/y') }} {{ substr($comprobante[0]['hora'], 0, 5) }}</p>
                    <p class="data">Usuario: {{ $comprobante[0]['name'] }}</p>
                    <p>Observac.: {{ $comprobante[0]['observaciones'] }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3 mt-2 mb-3 px-5">
					<table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col" class="cellRight">Cantidad</th>
                                <th scope="col" class="cellRight">U.M.</th>
                                <th scope="col" class="text-center">Artículo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detalle as $detal)
                                <tr>
                                    <td class="cellRight">{{ number_format($detal['cantidad'], 3,',', '.') }}</td>
                                    <td class="text-center">{{ $detal['unidad_medida'] }}</td>
                                    <td>{{ $detal['descripcion'] }}</td>
                                </tr>
                            @endforeach
                      </tbody>
                  </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3">
                    <p>Nombre:</p>
                    <br><br>
                    <p>Firma:</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var _pathImprime = "{{ route('comprobante_stock.imprime', '') }}",
                _idComprob = "{{ $idComprob }}",
                _nombreImpresora = "{{ $nombreImpresora }}";

            // Para boton volver
            const btnVolver = document.querySelector('#btnVolver');
            btnVolver.addEventListener( "click", () => window.close() );
            // Boton Imprimir
            const btnImprimir = document.querySelector('#btnImprimir');
            btnImprimir.addEventListener( "click", () => {

                if (!_nombreImpresora) {
                    window.print();       // Manda a imprimir si no viene nombre de impresora
                } else {
                    const userAgent = navigator.userAgent;
                    const url = _pathImprime + '/' + _idComprob + '?userAgent=' + userAgent;
                    location.assign(url);
                }
            });
        </script>
    </body>
</html>
