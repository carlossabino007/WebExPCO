<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html lang="en" class="no-js">
	<head>
	
	

						
	
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=300, maximum-scale=0.5">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<script languaje="javascript">
		function validar(){
			if(document.movistar.cedula.value==''){
			alert('Para realizar la consulta por favor ingrese numero de cedula');
			document.movistar.cedula.focus();
			return false;
			}
			if(document.movistar.cod_cliente.value==''){
			alert('Para realizar la consulta por favor ingrese el codigo cliente');
			document.movistar.cod_cliente.focus();
			return false;
			}
			if(document.movistar.nivel_riesgo.value==''){
			alert('Para realizar la consulta por favor ingrese nivel de riesgo');
			document.movistar.nivel_riesgo.focus();
			return false;
			}
			if(document.movistar.cant_cuotas.value==''){
			alert('Para realizar la consulta por favor ingrese cantidad de cuotas');
			document.movistar.cant_cuotas.focus();
			return false;
			}
			if(document.movistar.fec_ini.value==''){
			alert('Para realizar la consulta por favor ingrese fecha ingreso');
			document.movistar.fec_ini.focus();
			return false;
			}
			if(document.movistar.fec_fin.value==''){
			alert('Para realizar la consulta por favor ingrese fecha final');
			document.movistar.fec_fin.focus();
			return false;
			}
			if(document.movistar.causal.value==''){
			alert('Para realizar la consulta por favor ingrese causal');
			document.movistar.causal.focus();
			return false;
			}
		}
		</script>
		
		<Script language="javascript">
		function checkKeyCode(evt)
			{

				var evt = (evt) ? evt : ((event) ? event : null);
				var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
			if(event.keyCode==116)
			{
				evt.keyCode=0;
				return false
			}
			}
				document.onkeydown=checkKeyCode;
		</script>
		
		
		<title>CLIENTES</title>
		
		<!-- Bootstrap -->
		<script src="js/modernizr.custom.js"></script>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/jquery.fancybox.css" rel="stylesheet">
		<link href="css/flickity.css" rel="stylesheet" >
		<link href="css/animate.css" rel="stylesheet">
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'>
		<link href="css/styles.css" rel="stylesheet">
		<link href="css/queries.css" rel="stylesheet">
</head>
	<body>
		<header>
		
			<form action="index.php" method="POST" name="movistar" class="hero" onsubmit="return validar();">
										<?php
								//validar usuario de red autorizado
								$headers = apache_request_headers();

								if (!isset($headers['Authorization'])){
									header('HTTP/1.1 401 Unauthorized');
									header('WWW-Authenticate: NTLM');
									exit;
								}

								$auth = $headers['Authorization'];

								if (substr($auth,0,5) == 'NTLM ') {
									$msg = base64_decode(substr($auth, 5));
									if (substr($msg, 0, 8) != "NTLMSSP\x00")
										die('error header not recognised');

									if ($msg[8] == "\x01") {
										$msg2 = "NTLMSSP\x00\x02\x00\x00\x00".
											"\x00\x00\x00\x00". // target name len/alloc
											"\x00\x00\x00\x00". // target name offset
											"\x01\x02\x81\x00". // flags
											"\x00\x00\x00\x00\x00\x00\x00\x00". // challenge
											"\x00\x00\x00\x00\x00\x00\x00\x00". // context
											"\x00\x00\x00\x00\x00\x00\x00\x00"; // target info len/alloc/offset

										header('HTTP/1.1 401 Unauthorized');
										header('WWW-Authenticate: NTLM '.trim(base64_encode($msg2)));
										exit;
									}
									else if ($msg[8] == "\x03") {
										function get_msg_str($msg, $start, $unicode = true) {
											$len = (ord($msg[$start+1]) * 256) + ord($msg[$start]);
											$off = (ord($msg[$start+5]) * 256) + ord($msg[$start+4]);
											if ($unicode)
												return str_replace("\0", '', substr($msg, $off, $len));
											else
												return substr($msg, $off, $len);
										}
										$user = get_msg_str($msg, 36);
										$domain = get_msg_str($msg, 28);
										$workstation = get_msg_str($msg, 44);
										
									if ($conn_access = odbc_connect ("comisiones_2019","NH\flramirezor","")){
									
									$consulta_validacion = ("select nombre, usuario, area from ww_us_rc_exp where usuario = '".$user."'");
									
									$resconsulta_validacion= odbc_exec ($conn_access,$consulta_validacion);
										$total = odbc_num_rows($resconsulta_validacion);
										$arr = odbc_fetch_array($resconsulta_validacion);
											if($total==0){
												
												header('Location: error.php');
											}else{
												echo '<P align="right">Bienvenido <br>'.$nombre=$arr['nombre'];
												echo '<br>'.$nombre=$arr['area'];
											}
										}
									}
								}

							?>
				<div class="container">
					<div>
						<div class="col-md-12">
							<CENTER><h5><font color="white">EXCEPCIONES RIESGO CREDITICIO</font></h5>
							<CENTER><IMG SRC="..\riesgocrediticio\img\movistar.PNG" width="100px" height="100px"></h1><CENTER>
							
						<br>
						</div>
					</div>
				<div>
					<center>
					<TABLE>
						
						<TR>
						<td>
							<center>
								<h2><A HREF="consultar_p.php"><IMG SRC="..\riesgocrediticio\img\USUARIOS.png" width="100px" height="80px"></A></h2>
								<h2><font color="BLACK">CONSULTAR</font></h2>
								<h2><A HREF="consultar_p.php"><IMG SRC="..\riesgocrediticio\img\INGRESAR1.png" width="100px" height="30px"></A></h2>
							</center>
						</td>
						<td>
							<center>
								______________
							</center>
						</td>
						<td>
							<center>
								<h2><A HREF="insertar.php"><IMG SRC="..\riesgocrediticio\img\USUARIOS.png" width="100px" height="80px"></A></h2>
								<h2><font color="BLACK">AGREGAR</font></h2>
								<h2><A HREF="insertar.php"><IMG SRC="..\riesgocrediticio\img\INGRESAR2.png" width="100px" height="30px"></A></h2>
							</center>
						</td>
						<td>
							<center>
								______________
							</center>
						</td>
						<td>
							<center>
								<h2><A HREF="eliminar.php"><IMG SRC="..\riesgocrediticio\img\USUARIOS.png" width="100px" height="80px"></A></h2>
								<h2><font color="BLACK">ELIMINAR</font></h2>
								<h2><A HREF="eliminar.php"><IMG SRC="..\riesgocrediticio\img\INGRESAR1.png" width="100px" height="30px"></A></h2>
							</center>
						</td>
						</TR>
										
					</table>
				</div>
			</form>
			<BR>
			<BR>
			<BR>
		</header>
	</body>
</html>