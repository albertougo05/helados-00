<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" href="/favicon.ico">
	    <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<title>Mix de ventas</title>
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
            .cellRight { text-align: right; }
			.data { font-size: 0.875em; }
            .data:hover {
                background: #d2d3d5;
            }
            .data_res { 
                font-size: 0.875em; 
                font-weight: bolder;
                background-color: #5fe9c0;
            }
            .data_res:hover { 
                background-color: #1f9a75; 
                color: white;
            }
            .totalGral { 
                line-height: 40px;
                font-size: 0.875em; 
                font-weight: bolder;
                background-color: #cbbe47;
            }
			hr.lin { margin: 20px; }
            button {
                width: 120px;
                margin-right: 10px;
            }
            table {
                position: relative;
                border-collapse: separate;
            }
            thead { 
                background: #5c8bd5;
                color: white;
                padding: 8px 1px;
                position: sticky;
                top: 0;
                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
                line-height: 40px;
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
		<div class="container-fluid">
			<div class="row">
				<div class="col mt-3">
					<h2 class="text-center"><strong>Mix de ventas: {{ $sucursal }}</strong></h2>
				</div>
			</div>
            <div class="row">
                <div class="col-10 offset-1 mt-2">
                    <h5 class="text-center">{{ $titulo }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-10 offset-1 mt-3 mb-5 px-5">
					<table class="table table-sm ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Grupo</th>
                                <th scope="col" class="text-center">Artículo</th>
                                <th scope="col" class="cellRight">Cantidad</th>
                                <th scope="col" class="cellRight">Precio Prom.</th>
                                <th scope="col" class="cellRight">Total</th>
                                <th scope="col" class="cellRight">% Total</th>
                                <th scope="col" class="cellRight">Kilos</th>
                                <th scope="col" class="cellRight">% Kilos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $prod)
                                @if ($prod['total_grupo'])
                                    <tr class="data_res fw-semibold">
                                        <td class="text-left">{{ $prod['grupo'] }}</td>
                                        <td class="text-left"></td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['cant_vtas'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['precio_prom'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['total'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['porc_total'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['kilos'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['porc_kilos'], 2, ',', '.') }}</td>
                                    </tr>
                                @else
                                    <tr class="data">
                                        <td class="text-left">{{ $prod['grupo'] }}</td>
                                        <td class="text-left">{{ $prod['descripcion'] }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['cant_vtas'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['precio_prom'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['total'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['porc_total'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['kilos'], 2, ',', '.') }}</td>
                                        <td class="cellRight">&nbsp; {{number_format($prod['porc_kilos'], 2, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="totalGral">
                                <td class="text-left">Total general</td>
                                <td></td>
                                <td class="cellRight">&nbsp; {{number_format($totgral['cant_vtas'], 2, ',', '.') }}</td>
                                <td class="cellRight">&nbsp; {{number_format($totgral['precio_prom'], 2, ',', '.') }}</td>
                                <td class="cellRight">&nbsp; {{number_format($totgral['total'], 2, ',', '.') }}</td>
                                <td class="cellRight">&nbsp; {{number_format($totgral['porc_total'], 2, ',', '.') }}</td>
                                <td class="cellRight">&nbsp; {{number_format($totgral['kilos'], 2, ',', '.') }}</td>
                                <td class="cellRight">&nbsp; {{number_format($totgral['porc_kilos'], 2, ',', '.') }}</td>
                            </tr>
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
        </script>
    </body>
</html>
