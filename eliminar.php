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

		
		<title>CONSULTAS VENTA CUOTA</title>
		
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
	<body onLoad="setTimeout(window.close, 500000)">
		<header>
			<form action="conexion_oracle_eliminar.php" method="POST" name="movistar" class="hero" onsubmit="return validar();">
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
									
									$consulta_validacion = ("select nombre, usuario from ww_us_rc_exp where usuario = '".$user."'");
									
									$resconsulta_validacion= odbc_exec ($conn_access,$consulta_validacion);
										$total = odbc_num_rows($resconsulta_validacion);
										$arr = odbc_fetch_array($resconsulta_validacion);
											if($total==0){
												
												header('Location: error.php');
											}else{
												echo '<P align="right">Bienvenido <br>'.$nombre=$arr['nombre'];
											}
										}
									}
								}

							?>
				<div class="container">
					<div>
						<div class="col-md-12">
							<CENTER><h1><IMG SRC="..\riesgocrediticio\img\movistar.PNG" width="180px" height="180px"></h1><CENTER>
						</div>
					</div>
					<div>
					<center>
					<TABLE>
						<tr>
						<center><td><font color="LIME"><strong>TIPO DOCUMENTO:</td>
						<td>
						<select name="tip_ident">
						<option value="CC">CC</option>
						<option value="CE">CE</option>
						<option value="NIT">NIT</option>
						<option value="PS">PS</option>
						</select></td>
						</tr>
						<tr>
						<center><td><font color="LIME"><strong>CEDULA:</td> <td><input type="text" name="cedula"></td>
						</tr>
						<tr>
						<td colspan="2"><center><br><input type="submit" value="Eliminar"></td>
						</tr>
						</table>
					</center>
					<BR>
					<center><h2><A HREF="index.php"><IMG SRC="..\riesgocrediticio\img\REGRESAR.png" width="180px" height="60px"></A></h2></center>';

					<BR>
					</div>
			</form>
</header>
	</body>
</html>

