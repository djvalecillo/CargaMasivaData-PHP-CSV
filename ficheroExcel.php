<?php 
date_default_timezone_set('America/Caracas');

$date = date("Y-m-d h:i a"); //date('Y-m-d h:i a');
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=reporte_web_farmagroup.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo $_POST['datos_a_enviar'];
 ?>