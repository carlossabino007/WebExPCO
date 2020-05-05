<html lang="en" class="no-js">
	<head>
	

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=300, maximum-scale=0.5">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<?php
include_once 'lib/nusopa.php';
$servicio = new soap_server();

$ns = "urn:serviciowsdl";
$servicio->configureWSDL("Serviciowebconsultaoracle",$ns);
$servicio->schemaTargetNamespace =$ns;



set_time_limit(90);
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
									
									$consulta_validacion = ("select nombre, usuario,area from ww_us_rc_exp where usuario = '".$user."'");
									
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
	
	<?php



$usuario = $_POST['cedula'];

if ($conn_access = odbc_connect ("PCO_CAMPANAS","PCO_CAMP","pco_camp")){
	
//$insert = ("INSERT INTO PCO_CAMP.CAMPA (TIPO_DOCUMENTO, NUMERO_DOCUMENTO, NOMBRE, CUPO) VALUES ('CC', '1030573374', 'EXTTEXRIESG', '3000000')");
	$consultar = ("SELECT * FROM PCO_CAMP.CAMPA WHERE NUMERO_DOCUMENTO in ('".$usuario."')");
	
	
	$resconsultar= odbc_exec ($conn_access,$consultar);
								
								echo '<CENTER><IMG SRC="..\riesgocrediticio\img\movistar.PNG" width="80px" height="80px"><CENTER>';
								echo '<br><center><h2>Base de campañas</h2>';
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
	echo "no conectado";
	}
	
//unificacion de tabla
echo '<table>';

echo '<tr>';

echo '<td>';
	
if ($conn_access2 = odbc_connect ("PCO_PRODUCCION","SQL_FLRAMIREZOR","TelefonicA_2041$")){
	

	$consultar_PCO = ("SELECT A.SYS_CREATEDATE AS FECHA_INICIO,
	A.DECISION_FINAL,
	A.ESTADO_CLASIFICACION,
	A.TIPO_EVALUACION,
	A.SOLICITUD_ID,
    A.FULL_STACK_ID,
	C.ASIG_CUPO_CUPO_DISPONIBLE_1 AS CUPO_DIS,
	A.TIPO_TRANSACCION,
	C.POLITICAS_OUTCOME_NAME,
	C.CALCULO_SCORES_SCORE_CARD_1003 AS SCORE_TIPO1_DATA,
    C.CALCULO_SCORES_SCORE_CARD_2003 AS CIFIN,
    C.POLITICAS_SORTED_REASON_COD001,
    C.POLITICAS_DESCRIPCION_REASO001,
    A.TIPO_NEGOCIO,
    E.TIPO_OFERTA,
    E.CANAL_COMERCIAL,
    E.CANAL_NEGOCIO,
    E.CANTIDAD_LINEAS_SOLICITADAS,
    D.TIPO_ID,
	D.NUMERO_ID,
    D.PRIMER_APELLIDO,
    D.CLIENTE_EXISTENTE,
    D.NIVEL_RIESGO_RIESGO AS RIESGO_ENTRANTE,
    C.NIVEL_RIESGO_CLIENTE AS RIESGO_SALIDA,
    D.SEGMENTO,
	D.SUBSEGMENTO,
    D.REGIONAL,
	D.DEPARTAMENTO,
	A.VENDEDOR_CODIGO,
    D.FECHA_NACIMIENTO,
    D.FECHA_EXPEDICION_ID,
    B.ALIST_ENTRADA_AUDIT_LEAF_NODE_,
    B.VALID_CONSULTAR_AUDIT_LEAF_NOD,
    B.VALID_CONSULTAR_PRIMARIO,
    B.VALID_CONSULTAR_SECUNDARIO,
    B.VALID_CONSULTAR_TERCIARIO,
    B.CALCULOS_FINALES_AUDIT_LEAF_NO,
    C.ALIST_ENTRADA_AUDIT_OUTCOME_NA,
    C.CALCULO_SCORES_AUDIT_LEAF_NODE,
    C.CALCULO_SCORES_AUDIT_OUTCOME_N,
    C.ASIG_CUPO_CUPO_OTORGADO_1,
    C.ASIG_CUPO_CUPO_EQUIPO_1,
    C.ASIG_CUPO_CUPO_SERVICIO_1,
    C.ASIG_CUPO_CUPO_LIMITE_CONSU001,
    F.CUPO_LIMITE_CONSUMO,
    F.CUPO_LIMITE_CREDITO,
    E.SUMA_OFERTAS_PRIMARIAS_NUEVAS,
    F.SALDO_ACTUAL_EQUIPO,
    F.SALDO_RESTANTE_VENTA_CUOTA_EN_,
    E.VALOR_EQUIPO,
    E.VALOROFERTAPRIMARIANUEVA,
    E.CLIENTE_EXISTENTE_SECUNDARIA,
    D.VALOR_CUOTA_MES,
    F.EDAD_MORA_MAYOR_1,
    F.SALDO_MORA_1,
	F.CANTIDAD_EQUIPOS_CUOTAS,
    F.CANTIDAD_RENOREPOS,
    C.ASIG_CUPO_VENTA_CUOTAS_1__LEAF,
    C.POLITICAS_LEAF_NODE_ID,
    C.POLITICAS_SORTED_REASON_CODE_2,
    C.CALCULOS_FINALES_LEAF_NODE_ID,
    C.VERIF_APLICAR_AUDIT_LEAF_NODE_,
    C.VERIF_APLICAR_AUDIT_OUTCOME_NA,
    C.VERIF_APLICAR_PRIMARIO_1,
    C.VERIF_APLICAR_FORMULARIO_PR001,
    C.VERIF_APLICAR_SECUNDARIO_1,
    C.VERIF_APLICAR_SECUNDARIO_2,
    C.VERIF_APLICAR_VERIF_APLICAR001,
    C.VERIF_APLICAR_VERIF_APLICAR003,
    J.EDAD_MORA_MAYOR_1,
    J.SALDO_MORA_1,
    J.TOTAL_VALOR_CLIENTE_EXIST_O001,
    J.TOTAL_VALOR_CLIENTE_EXIST_O002,
    J.TOTAL_CLIENTE_OFERTA_EXISTE001,
    C.NUMERO_M_XIMO_CUOTAS_FINANCIAR,
    C.MODALIDAD_PAGO
    FROM EDA_TENANT1.T_SOLICITUD A LEFT JOIN
    EDA_TENANT1.T_DA_VALIDACION B ON A.IDS_T_DA_VALIDACION = B.IDS_T_DA_VALIDACION LEFT JOIN
    EDA_TENANT1.T_DAPOLITICAB2C C ON A.IDS_T_DAPOLITICAB2C = C.IDS_T_DAPOLITICAB2C LEFT JOIN
    EDA_TENANT1.T_SOLICITANTE D ON D.IDS_T_SOLICITUD = A.IDS_T_SOLICITUD LEFT JOIN
    EDA_TENANT1.T_SUSCRIPCIONESNUEVAS E ON E.IDS_T_SUSCRIPCIONESNUEVAS = D.IDS_T_SUSCRIPCIONESNUEVAS LEFT JOIN
    EDA_TENANT1.T_SUSCRIPCIONESACTUALES F ON D.IDS_T_SUSCRIPCIONESACTUALES = F.IDS_T_SUSCRIPCIONESACTUALES     
    LEFT JOIN EDA_TENANT1.T_SUSCRIPCIONESACTUALES J ON A.IDS_T_SUSCRIPCIONESACTUALES = J.IDS_T_SUSCRIPCIONESACTUALES
             WHERE NUMERO_ID IN ('".$usuario."')
ORDER BY A.SYS_CREATEDATE desc;");
	
	
	
	$resconsultar_PCO= odbc_exec ($conn_access2,$consultar_PCO);
								echo '<center><h2>Consultas PCO</center>';
								echo '<table  border=4 width="99%" style="font-size:15px">';
								echo '	<tr>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FECHA CONSULTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>NUMERO_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DECISION FINAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ESTADO CLASIFICACION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO DISPONIBLE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO TRANSACCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_OUTCOME_NAME</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORE DATA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORE CIFIN</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CODIGO PCO 1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CODIGO PCO 2</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DESCRIPCION_CODIGO_PCO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>RIESGO ENTRANTE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>RIESGO SALIDA_PCO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>PRIMER_APELLIDO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FECHA_NACIMIENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FECHA_EXPEDICION_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO_EQUIPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANTIDAD CUOTAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>MODALIDAD_PAGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANTIDAD EQUIPOS_CUOTAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANTIDAD RENOREPOS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO EVALUACION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO NEGOCIO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO OFERTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANAL COMERCIAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANAL NEGOCIO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANTIDAD_LINEAS SOLICITADAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CLIENTE EXISTENTE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SEGMENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SUBSEGMENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>REGIONAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DEPARTAMENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CODIGO VENDEDOR</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ALIST_ENTRADA AUDIT_LEAF_NODE_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALID_CONSULTAR AUDIT_LEAF_NOD</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALID_CONSULTAR PRIMARIO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALID_CONSULTAR SECUNDARIO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALID_CONSULTAR TERCIARIO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CALCULOS_FINALES AUDIT_LEAF_NO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ALIST_ENTRADA_AUDIT_OUTCOME_NA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CALCULO_SCORES AUDIT_LEAF_NODE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CALCULO_SCORES AUDIT_OUTCOME_N</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ASIG_CUPO CUPO_OTORGADO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ASIG_CUPO CUPO_SERVICIO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ASIG_CUPO CUPO_LIMITE_CONSU001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO_LIMITE CONSUMO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO_LIMITE CREDITO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SUMA_OFERTAS PRIMARIAS_NUEVAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SALDO_ACTUAL EQUIPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SALDO_RESTANTE VENTA_CUOTA_EN_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOR_EQUIPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOROFERTA PRIMARIANUEVA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CLIENTE_EXISTENTE SECUNDARIA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOR CUOTA_MES</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>EDAD_MORA MAYOR</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SALDO_MORA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ASIG_CUPO_VENTA CUOTAS_1__LEAF</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_LEAF NODE_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CALCULOS_FINALES LEAF_NODE_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR AUDIT_LEAF_NODE_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_AUDIT_OUTCOME_NA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR PRIMARIO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR FORMULARIO_PR001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>MECANISMO VERIF APLICAR_SECUNDARIO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_SECUNDARIO_2</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_VERIF_APLICAR001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_VERIF_APLICAR003</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SOLICITUD_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FULL_STACK_ID</th></font>';								
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TOTAL_VALOR CLIENTE_EXIST_O001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TOTAL_VALOR CLIENTE_EXIST_O002</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TOTAL_CLIENTE OFERTA_EXISTE001</th></font>';
								echo '	</tr>';
									
								while ($arr = odbc_fetch_array($resconsultar_PCO)){
								
								 								
								echo	'<tr>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['FECHA_INICIO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NUMERO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DECISION_FINAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ESTADO_CLASIFICACION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CUPO_DIS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_TRANSACCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_OUTCOME_NAME'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORE_TIPO1_DATA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CIFIN'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_SORTED_REASON_COD001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_SORTED_REASON_CODE_2'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_DESCRIPCION_REASO001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['RIESGO_ENTRANTE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['RIESGO_SALIDA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['PRIMER_APELLIDO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['FECHA_NACIMIENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['FECHA_EXPEDICION_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_EQUIPO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NUMERO_M_XIMO_CUOTAS_FINANCIAR'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['MODALIDAD_PAGO'].'</td>';	
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CANTIDAD_EQUIPOS_CUOTAS'].'</td>';	
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CANTIDAD_RENOREPOS'].'</td>';	
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_EVALUACION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_NEGOCIO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_OFERTA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CANAL_COMERCIAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CANAL_NEGOCIO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CANTIDAD_LINEAS_SOLICITADAS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CLIENTE_EXISTENTE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SEGMENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SUBSEGMENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['REGIONAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DEPARTAMENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VENDEDOR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ALIST_ENTRADA_AUDIT_LEAF_NODE_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALID_CONSULTAR_AUDIT_LEAF_NOD'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALID_CONSULTAR_PRIMARIO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALID_CONSULTAR_SECUNDARIO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALID_CONSULTAR_TERCIARIO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CALCULOS_FINALES_AUDIT_LEAF_NO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ALIST_ENTRADA_AUDIT_OUTCOME_NA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CALCULO_SCORES_AUDIT_LEAF_NODE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CALCULO_SCORES_AUDIT_OUTCOME_N'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_OTORGADO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_SERVICIO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_LIMITE_CONSU001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CUPO_LIMITE_CONSUMO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CUPO_LIMITE_CREDITO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SUMA_OFERTAS_PRIMARIAS_NUEVAS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SALDO_ACTUAL_EQUIPO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SALDO_RESTANTE_VENTA_CUOTA_EN_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOR_EQUIPO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOROFERTAPRIMARIANUEVA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CLIENTE_EXISTENTE_SECUNDARIA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOR_CUOTA_MES'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['EDAD_MORA_MAYOR_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SALDO_MORA_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_VENTA_CUOTAS_1__LEAF'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_LEAF_NODE_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CALCULOS_FINALES_LEAF_NODE_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_AUDIT_LEAF_NODE_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_AUDIT_OUTCOME_NA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_PRIMARIO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_FORMULARIO_PR001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_SECUNDARIO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_SECUNDARIO_2'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_VERIF_APLICAR001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_VERIF_APLICAR003'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SOLICITUD_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['FULL_STACK_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TOTAL_VALOR_CLIENTE_EXIST_O001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TOTAL_VALOR_CLIENTE_EXIST_O002'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TOTAL_CLIENTE_OFERTA_EXISTE001'].'</td>';
								echo	'</tr>';
								}
							echo	'</table>';


odbc_close_all();

	}
	else 
	{
	echo "no conectado";
	}
	
echo '</td>';
echo '<td>';
	
if ($conn_access2 = odbc_connect ("PCO_PRODUCCION","SQL_FLRAMIREZOR","TelefonicA_2041$")){
	

	$consultar_PCO2 = ("SELECT
    A.SYS_CREATEDATE
    ,A.TIPO_ID
    ,A.NUMERO_ID
    ,A.CLIENTE_EXISTENTE
    ,A.MECANISMO_AUTENTICACION
    ,A.VALOR_CUOTA_MES
    ,A.ESTRATO
    ,B.DECISION_FINAL
    ,B.TIPO_TRANSACCION
    ,E.AFA_ERROR_DESCRIPCION
    ,E.DATAV_ERROR_CODIGO
    ,E.DATAV_ERROR_DESCRIPCION
    ,E.DATAV_RESP_CONSULTARHCRESPO001 AS DAT_FECHA_CONSULTA
    ,E.DATAV_RESP_CONSULTARHCRESPO002 AS DAT_RESPUESTA
    ,E.DATAV_RESP_CONSULTARHCRESPO003 AS DAT_VERSION
    ,E.DATAV_RESP_CONSULTARHCRESPO004 AS DAT_NOMBRES
    ,E.DATAV_RESP_CONSULTARHCRESPO005 AS DAT_PRIMER_APELLIDO
    ,E.DATAV_RESP_CONSULTARHCRESPO006 AS DAT_SEGUNDO_APELLIDO
    ,E.DATAV_RESP_CONSULTARHCRESPO007 AS DAT_NOMBRE_COMPLETO
    ,E.DATAV_RESP_CONSULTARHCRESPO008 AS DAT_GENERO
    ,E.DATAV_RESP_CONSULTARHCRESPO009 AS DAT_ESTADO_CIVIL
    ,E.DATAV_RESP_CONSULTARHCRESPO010 AS DAT_VALIDADA
    ,E.DATAV_RESP_CONSULTARHCRESPO011 AS DAT_ESTADO
    ,E.DATAV_RESP_CONSULTARHCRESPO012 AS DAT_FECHA_EXPEDICION
    ,E.DATAV_RESP_CONSULTARHCRESPO013 AS DAT_CIUDAD
    ,E.DATAV_RESP_CONSULTARHCRESPO014 AS DAT_DEPARTAMENTO
    ,E.DATAV_RESP_CONSULTARHCRESPO015 AS DAT_NUMERO_ID
    ,E.DATAV_RESP_CONSULTARHCRESPO016 AS DAT_EDAD_MIN
    ,E.DATAV_RESP_CONSULTARHCRESPO017 AS DAT_EDAD_MAX
    ,E.DATAV_RESP_CONSULTARHCRESPO018 AS DAT_LLAVE
    ,E.DATAV_RESP_CONSULTARHCRESPO023 AS DAT_PJN_NOMBRE
    ,E.DATAV_RESP_CONSULTARHCRESPO024 AS DAT_PJN_IDENTIFICACION
    ,E.DATAV_RESP_CONSULTARHCRESPO025 AS DAT_PJN_LLAVE
    ,E.HC_PN_DATA_ERROR_CODIGO
    ,E.HC_PN_DATA_ERROR_DESCRIPCION
    ,E.HC_PN_TU_ERROR_CODIGO
    ,E.HC_PN_TU_ERROR_DESCRIPCION 
    ,Z.GENERADAS_TELCOS_REFERENCIA002 AS SCOREDAT1 
    ,Z.GENERADAS_REFERENCIAS_COMERCIA AS SCOREDAT2 
    ,Z.GENERADAS_FINANCIERO_PEOR_ESTA AS SCOREDAT3
    ,Z.GENERADAS_TELCOS_DIAS_DDE_PRIM AS SCOREDAT4
	,Z.GENERADAS_FINANCIERO_DIAS_DDE_ AS SCOREDAT5 
	,Z.GENERADAS_SDO_TOT_DIV_LIM_TOT_ AS SCOREDAT6 
	,Z.GENERADAS_TELCOS_DIAS_MAS_RECI AS SCOREDAT7
	,Z.GENERADAS_TELCOS_REF_SIN_ATRAS AS SCOREDAT8
	,Z.GENERADAS_MAXIMO_ATRASO_ACTUAL AS SCOREDAT9 
	,Z.GENERADAS_REF_CON_CASTIGO_U24M AS SCOREDAT10 
	,Z.GENERADAS_NUM_CREDITOS_CON_MAL AS SCOREDAT11
	,Z.GENERADAS_FINANCIERO_HIST_CRED AS SCOREDAT12
	,A.ESTRATO AS SCOREDAT13
	,Y.GENERADAS_TELCOS_REF_ACTUALIZA AS SCORETUV1 
	,Y.GENERADAS_REF_CONS_SALDO_DIV_L AS SCORETUV2
	,Y.GENERADAS_TELCOS_PEOR_ESTADO_T AS SCORETUV3
	,Y.GENERADAS_D_AS__DDE_PRIMER_TDC AS SCORETUV4
	,Y.GENERADAS_FINANCIERO_DIAS_DDE_ AS SCORETUV5 
	,Y.GENERADAS_TELCOS_DIAS_DESDE_AP AS SCORETUV6
	,Y.GENERADAS_SALDO_TOTAL_DIV_SALD AS SCORETUV7
	,Y.GENERADAS_TELCOS_DIAS_DDE_MAS_ AS SCORETUV8
	,Y.GENERADAS_REFERENCIAS_VIVI_CON AS SCORETUV9 
	,Y.GENERADAS_FINANCIERO_REF_SIN_A AS SCORETUV10
	,Y.GENERADAS_PEOR_COMPORTAMIEN004 AS SCORETUV11
	,Y.GENERADAS_REFERENCIAS_90_O_MAS AS SCORETUV12
	,Y.GENERADAS_FINANCIERO_HIST_CRED AS SCORETUV13 
	,A.ESTRATO AS SCORETUV14   
FROM 
    EDA_TENANT1.T_SOLICITANTE A
        LEFT JOIN
    EDA_TENANT1.T_SOLICITUD B ON A.IDS_T_SOLICITUD = B.IDS_T_SOLICITUD
        LEFT JOIN
    EDA_TENANT1.T_SERVICIOS E ON A.IDS_T_SERVICIOS = E.IDS_T_SERVICIOS
        LEFT JOIN
    EDA_TENANT1.T_DAPOLITICAB2C D ON B.IDS_T_DAPOLITICAB2C = D.IDS_T_DAPOLITICAB2C 
        LEFT JOIN
    EDA_TENANT1.T_SUSCRIPCIONESACTUALES C ON A.IDS_T_SUSCRIPCIONESACTUALES = C.IDS_T_SUSCRIPCIONESACTUALES 
        LEFT JOIN
    EDA_TENANT1.T_SUSCRIPCIONESNUEVAS F ON A.IDS_T_SUSCRIPCIONESNUEVAS = F.IDS_T_SUSCRIPCIONESNUEVAS
        LEFT JOIN
    EDA_TENANT1.T_BUROPNDATA Z ON A.IDS_T_BUROPNDATA = Z.IDS_T_BUROPNDATA
        LEFT JOIN
    EDA_TENANT1.T_BUROPNTU Y ON A.IDS_T_BUROPNTU = Y.IDS_T_BUROPNTU
WHERE NUMERO_ID IN ('".$usuario."')
ORDER BY A.SYS_CREATEDATE desc;");
	
	
	
	$resconsultar_PCO2= odbc_exec ($conn_access2,$consultar_PCO2);
								echo '<center><h2>Consultas PCO</center>';
								echo '<table  border=4 width="99%" style="font-size:15px">';
								echo '	<tr>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FECHA CONSULTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>NUMERO_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CLIENTE EXISTENTE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>MECANISMO AUTENTICACION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOR_CUOTA_MES</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ESTRATO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DECISION_FINAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO_TRANSACCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT2</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT3</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT4</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT5</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT6</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT7</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT8</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT9 COMPORTAMIENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT10</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT11</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT12</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCOREDAT13</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV2</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV3</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV4</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV5</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV6</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV7</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV8</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV9</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV10</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV11</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV12</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV13</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORETUV14</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>AFA_ERROR DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DATAV ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DATAV ERROR_DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_FECHA_CONSULTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT RESPUESTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT VERSION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_NOMBRES</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_PRIMER APELLIDO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_SEGUNDO APELLIDO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_NOMBRE_COMPLETO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT GENERO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT ESTADO_CIVIL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT VALIDADA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT ESTADO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT FECHA_EXPEDICION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_CIUDAD</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_DEPARTAMENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_NUMERO_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT EDAD_MIN</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT EDAD_MAX</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT LLAVE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_PJN_NOMBRE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_PJN IDENTIFICACION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DAT_PJN_LLAVE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_DATA ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_DATA ERROR_DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_TU ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_TU ERROR_DESCRIPCION</th></font>';
								echo '	</tr>';
									
								while ($arr = odbc_fetch_array($resconsultar_PCO2)){
								
								 								
								echo	'<tr>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SYS_CREATEDATE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NUMERO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CLIENTE_EXISTENTE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['MECANISMO_AUTENTICACION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOR_CUOTA_MES'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ESTRATO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DECISION_FINAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_TRANSACCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT2'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT3'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT4'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT5'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT6'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT7'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT8'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT9'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT10'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT11'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT12'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCOREDAT13'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV2'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV3'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV4'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV5'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV6'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV7'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV8'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV9'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV10'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV11'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV12'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV13'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORETUV14'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['AFA_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DATAV_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DATAV_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_FECHA_CONSULTA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_RESPUESTA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_VERSION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_NOMBRES'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_PRIMER_APELLIDO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_SEGUNDO_APELLIDO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_NOMBRE_COMPLETO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_GENERO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_ESTADO_CIVIL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_VALIDADA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_ESTADO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_FECHA_EXPEDICION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_CIUDAD'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_DEPARTAMENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_NUMERO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_EDAD_MIN'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_EDAD_MAX'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_LLAVE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_PJN_NOMBRE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_PJN_IDENTIFICACION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DAT_PJN_LLAVE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_DATA_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_DATA_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_TU_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_TU_ERROR_DESCRIPCION'].'</td>';
								echo	'</tr>';
								}
							echo	'</table>';


odbc_close_all();

	}
	else 
	{
	echo "no conectado";
	}
	
echo '</tr>';

echo '</table>';

	
$usuario = $_POST['cedula'];

if ($conn_access = odbc_connect ("comisiones_2019","NH\flramirezor","")){
		
	$consulta_riesgo = ("select DES_TIPPERSONA, NUM_IDENT, CALIFICACION, MONITOR, SARC_FINAL from MO_BASE_UNION_RIESGO
					where  NUM_IDENT='".$usuario."'");

	$resconsulta_riesgo= odbc_exec ($conn_access,$consulta_riesgo);
	
				echo '<br>';
				echo '<center><h2>Calificacion Base Riesgo</center>';
				echo '<table  border=4 width="60%" >';	
				echo '<tr>';
				echo '<th id="canal" bgcolor="#CEECF5"><CENTER>Tipo Identificacion</th>';
				echo '<th id="canal" bgcolor="#CEECF5"><CENTER>Numero de identificacion</th>';
				echo '<th id="canal" bgcolor="#CEECF5"><CENTER>Calificacion</th>';
				echo '<th id="canal" bgcolor="#CEECF5"><CENTER>Monitor</th>';
				echo '<th id="canal" bgcolor="#CEECF5"><CENTER>Sarc</th>';
				echo '</tr>';
					
				while ($arr = odbc_fetch_array($resconsulta_riesgo)){
						
				echo	'<tr>';
				echo	'<td bgcolor="#CEECF5"><center>'.$arr['DES_TIPPERSONA'].'</td>';
				echo	'<td bgcolor="#CEECF5"><center>'.$arr['NUM_IDENT'].'</td>';	
				echo	'<td bgcolor="#CEECF5"><center>'.$arr['CALIFICACION'].'</td>';	
				echo	'<td bgcolor="#CEECF5"><center>'.$arr['MONITOR'].'</td>';	
				echo	'<td bgcolor="#CEECF5"><center>'.$arr['SARC_FINAL'].'</td>';	
				echo	'</tr>';
				}
				
				
				echo	'</table></center>';
	
								echo '<br>';
							echo '<center><h2><A HREF="index.php"><IMG SRC="..\riesgocrediticio\img\REGRESAR.png" width="180px" height="60px"></A></h2></center>';

	
odbc_close_all();	
	}
	
echo '<br>';

echo '<a href="../riesgocrediticio/detalle_codigos.xlsx" download="Codigos_PCO"><h2>Descargar Detalle Codigos</h2></a>';	


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$servicio->service($HTTP_RAW_POST_DATA);

?>




		</header>
	</body>
</html>

