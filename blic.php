<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html lang="en" class="no-js">
<head>


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
										
									if ($conn_access = odbc_connect ("riesgo_crediticio","NH\aaforeromo","")){
									
									$consulta_validacion = ("select nombre, usuario from ww_us_rc where usuario = '".$user."'");
									
									$resconsulta_validacion= odbc_exec ($conn_access,$consulta_validacion);
										$total = odbc_num_rows($resconsulta_validacion);
										$arr = odbc_fetch_array($resconsulta_validacion);
											if($total==0){
												
												header('Location: error.php');
											}else{
												
											}
										}
									}
								}


?>

							<table  border=4 width="100%" >	
						<tr>
						<th id="canal" bgcolor="#0404B4"><font color="WHITE"><CENTER>codigo</font></th>
						<th id="canal" bgcolor="#0404B4"><font color="WHITE"><CENTER>detalle</font></th>
						</tr>
						
							<tr><td bgcolor="#CEECF5"><center>P01</td><td bgcolor="#CEECF5"><center>Cliente No activo</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P02</td><td bgcolor="#CEECF5"><center>Antiguedad Menor 2 Meses</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P03</td><td bgcolor="#CEECF5"><center>Cambio de plan reciente</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P04</td><td bgcolor="#CEECF5"><center>Alto Riesgo Comportamental</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P05</td><td bgcolor="#CEECF5"><center>El Cliente Presenta Mora  </td></tr>
							<tr><td bgcolor="#CEECF5"><center>P06</td><td bgcolor="#CEECF5"><center>Mora para realizar el traspaso</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P07</td><td bgcolor="#CEECF5"><center>Supera Limite Lineas Prepago</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P08</td><td bgcolor="#CEECF5"><center>Supera Limite lineas Pospago</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P09</td><td bgcolor="#CEECF5"><center>Supera Lineas Solicit+Activas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P10</td><td bgcolor="#CEECF5"><center>Cliente Menor a 18 años</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P11</td><td bgcolor="#CEECF5"><center>Cliente en Protección</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P12</td><td bgcolor="#CEECF5"><center>Cliente Presenta Mora</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P13</td><td bgcolor="#CEECF5"><center>El cliente presenta cupo cero</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P14</td><td bgcolor="#CEECF5"><center>Antiguedad Menor 3 Meses</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P15</td><td bgcolor="#CEECF5"><center>Marcado con flujo saliente</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P16</td><td bgcolor="#CEECF5"><center>Cliente Con Mora</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P17</td><td bgcolor="#CEECF5"><center>Antiguedad Menor a 60 Dias</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P18</td><td bgcolor="#CEECF5"><center>Cliente Presenta Mora Post</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P19</td><td bgcolor="#CEECF5"><center>Cliente Presenta Mora Prep</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P20</td><td bgcolor="#CEECF5"><center>Cupo Disponible Menor a Cero</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P21</td><td bgcolor="#CEECF5"><center>Cliente Presenta Mora Posv</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P22</td><td bgcolor="#CEECF5"><center>Supera Lineas Solicit+Activas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P23</td><td bgcolor="#CEECF5"><center>Antiguedad Menor 1 año</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P24</td><td bgcolor="#CEECF5"><center>Estado linea SCL</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P25</td><td bgcolor="#CEECF5"><center>Clientes Reventa</td></tr>
							<tr><td bgcolor="#CEECF5"><center>1000</td><td bgcolor="#CEECF5"><center>Cliente aprobado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>1005</td><td bgcolor="#CEECF5"><center>Tipo reclasificación inválida</td></tr>
							<tr><td bgcolor="#CEECF5"><center>1008</td><td bgcolor="#CEECF5"><center>Cliente en Reclasificacion</td></tr>
							<tr><td bgcolor="#CEECF5"><center>1012</td><td bgcolor="#CEECF5"><center>Tipo de Venta Invalida</td></tr>
							<tr><td bgcolor="#CEECF5"><center>1013</td><td bgcolor="#CEECF5"><center>Tipo de Posventa invalida</td></tr>
							<tr><td bgcolor="#CEECF5"><center>1014</td><td bgcolor="#CEECF5"><center>Tipo de Evaluación inválida</td></tr>
							<tr><td bgcolor="#CEECF5"><center>C001</td><td bgcolor="#CEECF5"><center>Cliente aprobado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>C002</td><td bgcolor="#CEECF5"><center>Cliente pendiente por estudio</td></tr>
							<tr><td bgcolor="#CEECF5"><center>C003</td><td bgcolor="#CEECF5"><center>Cliente rechazado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>C004</td><td bgcolor="#CEECF5"><center>Pendiente decision credito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF01</td><td bgcolor="#CEECF5"><center>Cliente no existe en centrales</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF02</td><td bgcolor="#CEECF5"><center>Consulta No Exitosa Cifin</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF03</td><td bgcolor="#CEECF5"><center>Error en la consulta a Cifin</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF04</td><td bgcolor="#CEECF5"><center>Cliente Cancelado Cifin</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF05</td><td bgcolor="#CEECF5"><center>Respuesta Cifin Inconsistencia</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF06</td><td bgcolor="#CEECF5"><center>Cliente Fallecido Cifin</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D001</td><td bgcolor="#CEECF5"><center>Error con Data crédito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D002</td><td bgcolor="#CEECF5"><center>Cliente sin historial credito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D003</td><td bgcolor="#CEECF5"><center>Cliente fallecido en D.crédito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D004</td><td bgcolor="#CEECF5"><center>Apellido no coincide</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D005</td><td bgcolor="#CEECF5"><center>No vigente en data credito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D006</td><td bgcolor="#CEECF5"><center>Resp. en DC es inconsistente</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D007</td><td bgcolor="#CEECF5"><center>Clave de datacredito es errada</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D008</td><td bgcolor="#CEECF5"><center>Identificacion no coincide</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D009</td><td bgcolor="#CEECF5"><center>Respuesta de DC desconocida</td></tr>
							<tr><td bgcolor="#CEECF5"><center>D010</td><td bgcolor="#CEECF5"><center>Vigencia de Id no reconocido</td></tr>
							<tr><td bgcolor="#CEECF5"><center>M001</td><td bgcolor="#CEECF5"><center>Cliente aprobado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>M002</td><td bgcolor="#CEECF5"><center>Cliente pendiente por estudio</td></tr>
							<tr><td bgcolor="#CEECF5"><center>M003</td><td bgcolor="#CEECF5"><center>Cliente rechazado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P001</td><td bgcolor="#CEECF5"><center>Cliente aprobado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P002</td><td bgcolor="#CEECF5"><center>Cliente pendiente por estudio</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P003</td><td bgcolor="#CEECF5"><center>Cliente rechazado por buro</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P004</td><td bgcolor="#CEECF5"><center>Pendiente Reclasificacion Lineas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P005</td><td bgcolor="#CEECF5"><center>Supera la cantidad de líneas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P006</td><td bgcolor="#CEECF5"><center>Canal Restringido para Transacción</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P007</td><td bgcolor="#CEECF5"><center>No tiene cupo disponible</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P008</td><td bgcolor="#CEECF5"><center>Supera la cantidad de líneas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P009</td><td bgcolor="#CEECF5"><center>Canal Restringido para Upgrade</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P010</td><td bgcolor="#CEECF5"><center>Canal Restringido Downgrade</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P011</td><td bgcolor="#CEECF5"><center>Canal Restringido Traspaso</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P012</td><td bgcolor="#CEECF5"><center>Canal Restringido Extratiempos</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P013</td><td bgcolor="#CEECF5"><center>Canal Restringido Recargas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P014</td><td bgcolor="#CEECF5"><center>Canal Restringido Reno Repo</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P015</td><td bgcolor="#CEECF5"><center>Canal Restringido para SS o PA</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P016</td><td bgcolor="#CEECF5"><center>Supera la cantidad de líneas</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P017</td><td bgcolor="#CEECF5"><center>Pendiente decision credito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P018</td><td bgcolor="#CEECF5"><center>Cliente en reclasificación</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P019</td><td bgcolor="#CEECF5"><center>Prepago tipo7 Enviar Datos</td></tr>
							<tr><td bgcolor="#CEECF5"><center>SC01</td><td bgcolor="#CEECF5"><center>Rechazado por score</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P020</td><td bgcolor="#CEECF5"><center>Cliente Tipo7 Ingresar por Web</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P021</td><td bgcolor="#CEECF5"><center>Rechazo Tipo7 Equip Financiado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P022</td><td bgcolor="#CEECF5"><center>Rechazo score Equip Financiado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P023</td><td bgcolor="#CEECF5"><center>Cliente Aprobado EQ Financiado</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P26</td><td bgcolor="#CEECF5"><center>Cliente Mora EF</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P024</td><td bgcolor="#CEECF5"><center>Rechazado Cliente Tipo 5 Datacredito</td></tr>
							<tr><td bgcolor="#CEECF5"><center>P062</td><td bgcolor="#CEECF5"><center>Mora Telcos</td></tr>
							<tr><td bgcolor="#CEECF5"><center>CF07</td><td bgcolor="#CEECF5"><center>Rechazado Cliente Tipo 1 Cifin</td></tr>
							
						</table>
						
						<BR>
					
					
			
	</body>
</html>