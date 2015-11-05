<?php 

$server="localhost";
$username="root";
$password="";
$db='farmagroup';
$con=mysql_connect($server,$username,$password)or die("no se ha podido establecer la conexion");
$datos=mysql_select_db($db,$con)or die("la base de datos no existe");

$count = 0;
$error = 0;
$linea = 1;
$no_exist = 0;
$cron = 0;
ini_set ('auto_detect_line_endings','1');

$fp = fopen ("Farmagroup_consolidado.csv", "r");

while ($data = fgetcsv ($fp, 1000, ";")){

	/* FORMATEA FECHA */
	$f = explode("/", $data[10]);
	$fecha = $f[2]."-".$f[1]."-".$f[0];
	
	if ($data[0] == "TITULAR") {
		
		$buscar = mysql_query("SELECT id FROM datos_titular WHERE cedula = '{$data[2]}' LIMIT 1
			");
		if (mysql_num_rows($buscar) == 1) {

			$titular = mysql_fetch_assoc($buscar);
			/* ****ACTUALIZO NUMERO DE TELEFONO EN DATOS DEL TITULAR**** */
			$update = mysql_query("UPDATE datos_titular SET 
						telefono = '{$data[6]}',
						direccion = '{$data[8]}',
						email = '{$data[7]}' 
						WHERE id = '{$titular['id']}' LIMIT 1 ");

			$buscar_cronico = mysql_query("SELECT * FROM datos_extras WHERE tipo = 'titular' AND beneficiario_id = '{$titular['id']}' ");
			if (mysql_num_rows($buscar_cronico) == 0) {
				/* ***GUARDA REGISTRO CRONICO EN DATOS EXTRAS**** */ 

				$registra = mysql_query("INSERT INTO datos_extras VALUES(
							NULL,
							'$fecha',
							'$data[11]',
							'$data[12]',
							'$data[13]',
							'$data[14]',
							'titular',
							'{$titular['id']}'
					) ");
				if ($registra) {
					$count++;
				}
				else
				{
					$error++;
					echo "LINEA #".$linea." Error al registrar dato extra: ".mysql_error()."<br>";
				}
			}
			else
			{
				$cron++;
			}
		}
		else
		{
			/* el titular no fue encontrado */
			$no_exist++;
			echo "LINEA #".$linea." >> Titular ".$data[3]." ".$data[4]." no encontrado<br>";
		}	
	}
	else
	{
		/*el beneficiario es familiar*/
		$buscar = mysql_query("SELECT id FROM datos_familiar WHERE cedula = '{$data[2]}' LIMIT 1
			");
		if (mysql_num_rows($buscar) == 1) {

			$familiar = mysql_fetch_assoc($buscar);
			/* ****ACTUALIZO NUMERO DE TELEFONO EN DATOS DEL TITULAR**** */
			$update = mysql_query("UPDATE datos_familiar SET 
						telefono = '{$data[6]}',
						direccion = '{$data[8]}',
						email = '{$data[7]}' 
						WHERE id = '{$familiar['id']}' LIMIT 1 ");

			$buscar_cronico = mysql_query("SELECT * FROM datos_extras WHERE tipo = 'familiar' AND beneficiario_id = '{$familiar['id']}' ");
			if (mysql_num_rows($buscar_cronico) == 0) {
				# code...
				/* ***GUARDA REGISTRO CRONICO EN DATOS EXTRAS**** */ 
				$registra = mysql_query("INSERT INTO datos_extras VALUES(
							NULL,
							'$fecha',
							'$data[11]',
							'$data[12]',
							'$data[13]',
							'$data[14]',
							'familiar',
							'{$familiar['id']}'
					) ");
				if ($registra) {
					$count++;
				}
				else
				{
					$error++;
					echo "LINEA #".$linea." Error al registrar dato extra: ".mysql_error()."<br>";
				}
			}
			else
			{
				$cron++;
			}
			
		}
		else
		{
			/* el titular no fue encontrado */
			$no_exist++;
			echo "LINEA #".$linea." >> Familiar ".$data[3]." ".$data[4]." no encontrado. <br>";
		}	
	}
		
	$linea++;	

}

fclose($fp);
echo "<hr>";

echo "Registros de datos cronicos exitosos: ".$count."<br>";
echo "Registros no guardados por error: ".$error."<br>";
echo "Feneficiarios o titulares no encontrados: ".$no_exist."<br>";
echo "Datos cronicos duplicados(no registrados): ".$cron."<br>";
 ?>