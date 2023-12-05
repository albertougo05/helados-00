<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" href="/favicon.ico">
	   <!-- Bootstrap CSS -->
	   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<title>Informe Movim. Caja</title>
        <!-- Mis estilos css -->
        <style type="text/css">
			body { font-size: 100%; }
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
            .ticket { font-family: 'Courier New', monospace; }
            .cellRight { text-align: right; }
			p#razon { font-size: 1.1em; }
			.data {
				font-size: 0.875em;
				/* margin: 1px; */
			}
			hr.lin { margin: 20px; }
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
					<div class="col-5 mt-4 mr-0">
                        <button id="btnExcel" type="button" class="btn btn-warning">Excel</button>
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
		<div class="container-fluid">
			<div class="row">
				<div class="col mt-4">
					<h3 class="text-center"><strong>Informe movimientos caja: {{ $sucursal }}</strong></h3>
				</div>
			</div>
            <div class="row">
                <div class="col-10 offset-1 mt-2">
                    <h5 class="text-center">{{ $titulo }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-8 offset-2 mt-3 mb-0 px-5">
					<table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Fecha hora</th>
                                <th scope="col" class="text-left">Sucursal</th>
                                <th scope="col" class="text-left">Usuario</th>
                                <th scope="col" class="cellRight">Turno</th>
                                <th scope="col" class="text-center">Descripción</th>
                                <th scope="col" class="cellRight">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($informe as $info)
                                <tr class="data">
                                    <td class="text-center">{{ $info['fechahora'] }}</td>
                                    <td class="text-left">{{ $info['nombre'] }}</td>
                                    <td class="text-left">{{ $info['nombre_usuario'] }}</td>
                                    <td class="cellRight">{{ $info['turno_cierre_id'] }}</td>
                                    <td class="text-left">{{ $info['concepto'] }}</td>
                                    <td class="cellRight">&nbsp; {{number_format($info['importe'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                      </tbody>
                  </table>
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
            // Para boton excel
            const btnExcel = document.querySelector('#btnExcel');
            btnExcel.addEventListener( "click", () => {
                let url = window.location.href;
                url = url.replace('show', 'excel');
                location.replace(url);

                return null;
            });
        </script>
    </body>
</html>
