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

<?php

$tipo_documento = $_POST['tip_ident'];
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$cupo = $_POST['cupo'];
$vigencia = $_POST['vigencia'];

if ($conn_access = odbc_connect ("PCO_CAMPANAS","PCO_CAMP","pco_camp")){
	
$insert = ("INSERT INTO PCO_CAMP.CAMPA (TIPO_DOCUMENTO, NUMERO_DOCUMENTO, NOMBRE, CUPO) VALUES ('".$tipo_documento."','".$cedula."', '".$nombre."', '".$cupo."')");
		
		$resinsert= odbc_exec ($conn_access,$insert);
		
	
$COMMIT = ("COMMIT");
		
		$resCOMMIT= odbc_exec ($conn_access,$COMMIT);
				
		
echo "<CENTER>CLIENTE AGREGADO CORRECTAMENTE</CENTER>";

	$consultar = ("SELECT * FROM PCO_CAMP.CAMPA WHERE NUMERO_DOCUMENTO in ('".$cedula."')");
	
	
	$resconsultar= odbc_exec ($conn_access,$consultar);
	
								echo '<table  border=4 width="99%" style="font-size:20px">';
								echo '	<tr>';
								echo '	<th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>Tipo Identificacion</th></font>';
								echo '	<th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>Cedula Cliente</th></font>';
								echo '	<th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>Campana</th></font>';
								echo '	<th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>Cupo</th></font>';
								echo '	</tr>';
									
								while ($arr = odbc_fetch_array($resconsultar)){
								
								 								
								echo	'<tr>';
								echo	'<td bgcolor="#CEECF5"><center>'.$arr['TIPO_DOCUMENTO'].'</td>';
								echo	'<td bgcolor="#CEECF5"><center>'.$arr['NUMERO_DOCUMENTO'].'</td>';	
								echo	'<td bgcolor="#CEECF5"><center>'.$arr['NOMBRE'].'</td>';	
								echo	'<td bgcolor="#CEECF5"><center>'.$arr['CUPO'].'</td>';
								echo	'</tr>';
								}
							echo	'</table>';
							echo '<br>';
							echo '<center><h2><A HREF="index.php"><IMG SRC="..\riesgocrediticio\img\REGRESAR.png" width="180px" height="60px"></A></h2></center>';

			odbc_close_all();

	
		}
		else 
		{
		echo "no conectado validar con administrador";
	}
	
	if ($conn_access = odbc_connect ("comisiones_2019","NH\flramirezor","")){
	
	$aud_agregar = ("insert into dbo.ww_us_rc_exp_seguimiento (USUARIO, FECHA, CEDULA_CLIENTE,TIPO_DOCUMENTO_CLI,NOMBRE_CAMPANA,CUPO_CAMPANA,ACCION,VIGENCIA) 
															values ('".$user."', getdate(),'".$cedula."','".$tipo_documento."','".$nombre."','".$cupo."','AGREGAR','".$vigencia."')");
	
	$res_aud_agregar= odbc_exec ($conn_access,$aud_agregar);
		}
		else 
		{
		echo "no conectado validar con administrador";
	}
?>



