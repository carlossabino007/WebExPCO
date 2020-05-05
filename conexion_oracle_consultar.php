<html lang="en" class="no-js">
	<head>
	

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=300, maximum-scale=0.5">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<?php

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
	
	
if ($conn_access2 = odbc_connect ("PCO_PRODUCCION","SQL_HFCARRILLOSI","TelefonicA_2040$")){
	

	$consultar_PCO = ("SELECT TO_CHAR(A.SYS_CREATEDATE,'DD/MM/YYYY HH24:MI:SS') as SYS_CREATEDATE
,B.TIPO_SOLICITANTE
,A.TIPO_ID
,A.NUMERO_ID
,A.PRIMER_APELLIDO
,A.NIVEL_RIESGO_RIESGO AS NIVEL_RIESGO_FS
,D.POLITICAS_SORTED_REASON_COD001
,D.POLITICAS_DESCRIPCION_REASO001
,A.FECHA_EXPEDICION_ID
,A.MECANISMO_AUTENTICACION
,A.SUBSEGMENTO
,A.ESTRATO
,B.VENDEDOR_DEPARTAMENTO
,B.VENDEDOR_CODIGO
,A.DEPARTAMENTO
,I.SALDO_MORA_1 AS SALDO_CARTERA
,I.EDAD_MORA_MAYOR_1 AS EDAD_CARTERA
,I.CANTIDAD_CUOTAS_PENDIENTES
,I.CANTIDAD_EQUIPOS_CUOTAS
,I.CANTIDAD_RENOREPOS
,L.NOMBRE_PRODUCTO AS NOMBRE_PRODUCTO_QLINEAS
,L.CANTIDAD_SUSCRIPCIONES AS QLINEAS
,L.MODALIDAD_PAGO AS MODALIDAD_PAGO_QLINEAS
,E.TIPO_OFERTA
,E.CANAL_COMERCIAL
,B.DECISION_FINAL
,B.ESTADO_CLASIFICACION
,B.ESTADO_NAME
,B.FULL_STACK_ID
,B.SOLICITUD_ID
,B.TIPO_TRANSACCION
,D.MODALIDAD_PAGO
,D.POLITICAS_OUTCOME_NAME
,D.NIVEL_RIESGO_CLIENTE AS NIVEL_RIESGO_PCO
,D.CALCULO_SCORES_AUDIT_OUTCOME_N
,D.CALCULO_SCORES_SCORE_CARD_1003 AS SCORE_TIPO1_DATA
,D.CALCULO_SCORES_SCORE_CARD_2003 AS SCORE_TUV
,D.CALCULO_SCORES_SCORE_CARD_3003 AS SCORE_GCC
,D.ASIG_CUPO_CUPO_OTORGADO_1
,D.ASIG_CUPO_CUPO_DISPONIBLE_1
,D.ASIG_CUPO_CUPO_EQUIPO_1
,D.ASIG_CUPO_CUPO_SERVICIO_1
,D.ASIG_CUPO_CUPO_LIMITE_CONSU001 AS CUPO_LIMITE_CONSUMO_PCO
,E.SUMA_OFERTAS_PRIMARIAS_NUEVAS
,E.SUMATORIACARRITOVENTA_RECURSOS
,I.SALDO_ACTUAL_EQUIPO
,I.SALDO_RESTANTE_VENTA_CUOTA_EN_
,I.NEW AS TOTAL_OFERTA_EXISTENTE
,E.VALOR_EQUIPO
,E.VALOROFERTAPRIMARIANUEVA
,E.CLIENTE_EXISTENTE_SECUNDARIA
,A.VALOR_CUOTA_MES
,I.CUPO_LIMITE_CONSUMO AS CUPO_LIMITE_CONSUMO_FS
,I.CUPO_LIMITE_CREDITO AS CUPO_LIMITE_CREDITO_BDRIESGO
,D.POLITICAS_DECISION_CATEGORY
,D.POLITICAS_SORTED_REASON_COD001
,D.POLITICAS_SORTED_REASON_CODE_2
,D.POLITICAS_SORTED_REASON_CODE_3
,D.MENSAJE_USUARIO
,F.PAP_ERROR_CODIGO
,F.PAP_RESP_QUOTASQUERYRESPONS002 AS BD_CAMPA_CUPO
,F.PAP_RESP_QUOTASQUERYRESPONSE_F AS BD_CAMPA_NOMBRE
,F.DATAV_ERROR_CODIGO
,F.DATAV_ERROR_DESCRIPCION
,F.HC_PN_DATA_ERROR_CODIGO
,F.HC_PN_DATA_ERROR_DESCRIPCION
,G.GENERADAS_TELCOS_REFERENCIA002
,G.GENERADAS_TELCOS_REF_SIN_ATRAS
,G.GENERADAS_TELCOS_DIAS_MAS_RECI
,G.GENERADAS_TELCOS_DIAS_DDE_PRIM
,G.GENERADAS_SDO_TOT_DIV_LIM_TOT_
,G.GENERADAS_REFERENCIAS_COMERCIA
,G.GENERADAS_REF_CON_CASTIGO_U24M
,G.GENERADAS_REAL_HIST_CRED_MENOR
,G.GENERADAS_NUM_CREDITOS_CON_MAL
,G.GENERADAS_MAXIMO_ATRASO_ACTUAL
,G.GENERADAS_FINANCIERO_PEOR_ESTA
,G.GENERADAS_FINANCIERO_DIAS_DDE_
,F.TUV_ERROR_CODIGO
,F.TUV_ERROR_DESCRIPCION
,F.HC_PN_TU_ERROR_CODIGO
,F.HC_PN_TU_ERROR_DESCRIPCION
,H.GENERADAS_TELCOS_REF_ACTUALIZA
,H.GENERADAS_TELCOS_DIAS_DESDE_AP
,H.GENERADAS_TELCOS_DIAS_DDE_MAS_
,H.GENERADAS_SALDO_TOTAL_DIV_SALD
,H.GENERADAS_REFERENCIAS_VIVI_CON
,H.GENERADAS_REFERENCIAS_90_O_MAS
,H.GENERADAS_REF_CONS_SALDO_DIV_L
,H.GENERADAS_PEOR_COMPORTAMIEN004
,H.GENERADAS_FINANCIERO_REF_SIN_A
,H.GENERADAS_FINANCIERO_PEOR_ESTA
,H.GENERADAS_FINANCIERO_HIST_CRED
,H.GENERADAS_FINANCIERO_DIAS_DDE_
,H.GENERADAS_D_AS__DDE_PRIMER_TDC
,D.VERIF_APLICAR_AUDIT_OUTCOME_NA
,D.VERIF_APLICAR_PRIMARIO_1
,D.VERIF_APLICAR_FORMULARIO_PR001
,D.VERIF_APLICAR_SECUNDARIO_1
,D.VERIF_APLICAR_FORMULARIO_SE001
,D.NUMERO_M_XIMO_CUOTAS_FINANCIAR
FROM EDA_TENANT1.T_SOLICITANTE A
 ,EDA_TENANT1.T_SOLICITUD B
 ,EDA_TENANT1.T_DAPOLITICAB2C D
,EDA_TENANT1.T_SUSCRIPCIONESNUEVAS E
,EDA_TENANT1.T_SERVICIOS F
    ,EDA_TENANT1.T_BUROPNDATA G
,EDA_TENANT1.T_BUROPNTU H
,EDA_TENANT1.T_SUSCRIPCIONESACTUALES I LEFT JOIN EDA_TENANT1.T_CANTIDAD L 
ON I.IDS_T_SUSCRIPCIONESACTUALES = L.IDS_T_SUSCRIPCIONESACTUALES
WHERE  A.IDS_T_SOLICITUD = B.IDS_T_SOLICITUD 
AND B.IDS_T_DAPOLITICAB2C = D.IDS_T_DAPOLITICAB2C
AND A.IDS_T_SUSCRIPCIONESNUEVAS = E.IDS_T_SUSCRIPCIONESNUEVAS
AND A.IDS_T_SERVICIOS = F.IDS_T_SERVICIOS
AND A.IDS_T_BUROPNDATA = G.IDS_T_BUROPNDATA
AND A.IDS_T_BUROPNTU = H.IDS_T_BUROPNTU
AND A.IDS_T_SUSCRIPCIONESACTUALES = I.IDS_T_SUSCRIPCIONESACTUALES
AND E.TIPO_OFERTA IN ('0002','0003')
AND A.NUMERO_ID IN ('".$usuario."')
ORDER BY A.SYS_CREATEDATE desc;");
	
	
	
	$resconsultar_PCO= odbc_exec ($conn_access2,$consultar_PCO);
								echo '<center><h2>Consultas PCO</center>';
								echo '<table  border=4 width="99%" style="font-size:15px">';
								echo '	<tr>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FECHA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>NUMERO ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO TRANSACCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>NIVEL RIESGO_FS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>NIVEL RIESGO_PCO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DECISION FINAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CODIGO RESPUESTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DESCRIPCION RESPUESTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORE DATA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORE TUV</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO DISPONIBLE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO EQUIPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO OTORGADO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO LIMITE CREDITO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>PRIMER APELLIDO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FECHA EXPEDICION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SUBSEGMENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ESTRATO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DEPARTAMENTO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SALDO CARTERA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>EDAD CARTERA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TIPO OFERTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CANAL COMERCIAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>FULL_STACK_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SOLICITUD_ID</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>MODALIDAD PAGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_OUTCOME_NAME</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CALCULO_SCORES_AUDIT_OUTCOME_N</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>ASIG_CUPO_CUPO_SERVICIO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO_LIMITE_CONSUMO_PCO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SUMA_OFERTAS_PRIMARIAS_NUEVAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SUMATORIACARRITOVENTA_RECURSOS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SALDO_ACTUAL_EQUIPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SALDO_RESTANTE_VENTA_CUOTA_EN_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TOTAL_OFERTA_EXISTENTE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOR_EQUIPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOROFERTAPRIMARIANUEVA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CLIENTE_EXISTENTE_SECUNDARIA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VALOR_CUOTA_MES</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>CUPO_LIMITE_CONSUMO_FS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_DECISION_CATEGORY</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_SORTED_REASON_COD001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_SORTED_REASON_CODE_2</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>POLITICAS_SORTED_REASON_CODE_3</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>PAP_ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>BD_CAMPA_CUPO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>BD_CAMPA_NOMBRE</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DATAV_ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>DATAV_ERROR_DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_DATA_ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_DATA_ERROR_DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_REFERENCIA002</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_REF_SIN_ATRAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_DIAS_MAS_RECI</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_DIAS_DDE_PRIM</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_SDO_TOT_DIV_LIM_TOT_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_REFERENCIAS_COMERCIA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_REF_CON_CASTIGO_U24M</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_REAL_HIST_CRED_MENOR</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_NUM_CREDITOS_CON_MAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_MAXIMO_ATRASO_ACTUAL</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_FINANCIERO_PEOR_ESTA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_FINANCIERO_DIAS_DDE_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TUV_ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>TUV_ERROR_DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_TU_ERROR_CODIGO</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>HC_PN_TU_ERROR_DESCRIPCION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_REF_ACTUALIZA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_DIAS_DESDE_AP</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_TELCOS_DIAS_DDE_MAS_</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_SALDO_TOTAL_DIV_SALD</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_REFERENCIAS_VIVI_CON</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_REFERENCIAS_90_O_MAS</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_REF_CONS_SALDO_DIV_L</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_PEOR_COMPORTAMIEN004</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_FINANCIERO_REF_SIN_A</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_FINANCIERO_PEOR_ESTA_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_FINANCIERO_HIST_CRED</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_FINANCIERO_DIAS_DDE__1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>GENERADAS_D_AS__DDE_PRIMER_TDC</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_AUDIT_OUTCOME_NA</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_PRIMARIO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_FORMULARIO_PR001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_SECUNDARIO_1</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>VERIF_APLICAR_FORMULARIO_SE001</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>NUMERO_M_XIMO_CUOTAS_FINANCIAR</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>MECANISMO AUTENTICACION</th></font>';
								echo ' <th id="canal" bgcolor="#0B0B61"><font color="#F8FBEF"><CENTER>SCORE_GCC</th></font>';



								echo '	</tr>';
									
								while ($arr = odbc_fetch_array($resconsultar_PCO)){
								
								 								
								echo	'<tr>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SYS_CREATEDATE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NUMERO_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_TRANSACCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NIVEL_RIESGO_FS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NIVEL_RIESGO_PCO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DECISION_FINAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_SORTED_REASON_COD001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_DESCRIPCION_REASO001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORE_TIPO1_DATA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORE_TUV'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_DISPONIBLE_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_EQUIPO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_OTORGADO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CUPO_LIMITE_CREDITO_BDRIESGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['PRIMER_APELLIDO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['FECHA_EXPEDICION_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SUBSEGMENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ESTRATO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DEPARTAMENTO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SALDO_CARTERA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['EDAD_CARTERA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TIPO_OFERTA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CANAL_COMERCIAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['FULL_STACK_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SOLICITUD_ID'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['MODALIDAD_PAGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_OUTCOME_NAME'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CALCULO_SCORES_AUDIT_OUTCOME_N'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['ASIG_CUPO_CUPO_SERVICIO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CUPO_LIMITE_CONSUMO_PCO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SUMA_OFERTAS_PRIMARIAS_NUEVAS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SUMATORIACARRITOVENTA_RECURSOS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SALDO_ACTUAL_EQUIPO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SALDO_RESTANTE_VENTA_CUOTA_EN_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TOTAL_OFERTA_EXISTENTE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOR_EQUIPO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOROFERTAPRIMARIANUEVA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CLIENTE_EXISTENTE_SECUNDARIA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VALOR_CUOTA_MES'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['CUPO_LIMITE_CONSUMO_FS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_DECISION_CATEGORY'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_SORTED_REASON_COD001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_SORTED_REASON_CODE_2'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['POLITICAS_SORTED_REASON_CODE_3'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['PAP_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['BD_CAMPA_CUPO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['BD_CAMPA_NOMBRE'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DATAV_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['DATAV_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_DATA_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_DATA_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_REFERENCIA002'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_REF_SIN_ATRAS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_DIAS_MAS_RECI'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_DIAS_DDE_PRIM'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_SDO_TOT_DIV_LIM_TOT_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_REFERENCIAS_COMERCIA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_REF_CON_CASTIGO_U24M'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_REAL_HIST_CRED_MENOR'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_NUM_CREDITOS_CON_MAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_MAXIMO_ATRASO_ACTUAL'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_FINANCIERO_PEOR_ESTA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_FINANCIERO_DIAS_DDE_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TUV_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['TUV_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_TU_ERROR_CODIGO'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['HC_PN_TU_ERROR_DESCRIPCION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_REF_ACTUALIZA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_DIAS_DESDE_AP'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_TELCOS_DIAS_DDE_MAS_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_SALDO_TOTAL_DIV_SALD'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_REFERENCIAS_VIVI_CON'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_REFERENCIAS_90_O_MAS'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_REF_CONS_SALDO_DIV_L'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_PEOR_COMPORTAMIEN004'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_FINANCIERO_REF_SIN_A'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_FINANCIERO_PEOR_ESTA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_FINANCIERO_HIST_CRED'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_FINANCIERO_DIAS_DDE_'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['GENERADAS_D_AS__DDE_PRIMER_TDC'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_AUDIT_OUTCOME_NA'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_PRIMARIO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_FORMULARIO_PR001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_SECUNDARIO_1'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['VERIF_APLICAR_FORMULARIO_SE001'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['NUMERO_M_XIMO_CUOTAS_FINANCIAR'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['MECANISMO_AUTENTICACION'].'</td>';
								echo '<td bgcolor="#CEECF5"><center>'.$arr['SCORE_GCC'].'</td>';
								echo	'</tr>';
								}
							echo	'</table>';


odbc_close_all();

	}
	else 
	{
	echo "no conectado";
	}
	
	
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
	
	

?>




		</header>
	</body>
</html>

