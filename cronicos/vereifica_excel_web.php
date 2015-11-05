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
$duplicado = 0;
$no_duplicado = 0;
ini_set ('auto_detect_line_endings','1');

$fp = fopen ("Farmagroup_consolidado.csv", "r");

while ($data = fgetcsv ($fp, 1000, ";")){
	
	if ($data[0] == "TITULAR") {
		
		$buscar = mysql_query("SELECT id FROM datos_titular WHERE cedula = '{$data[1]}' LIMIT 1
			");
		if (mysql_num_rows($buscar) == 1) {

			$titular = mysql_fetch_assoc($buscar);
			$buscar2 = mysql_query("SELECT * FROM datos_extras WHERE beneficiario_id = '{$titular['id']}' AND tipo = 'titular' ");
			if (mysql_num_rows($buscar2) == 1) {

				$extra = mysql_fetch_assoc($buscar2);
				$duplicado++;
				echo "<b> LINEA #".$linea." >> Titular ".$data[3]." ".$data[4]." posee dato cronico con ID: ".$extra['id']." </b> <br>";
			}
			else
			{
				$no_duplicado++;
			}
			
		}
		else
		{
			/* el titular no fue encontrado */
			$no_exist++;
			echo "LINEA #".$linea." >> Titular ".$data[3]." ".$data[4]." no encontrado <br>";
			
		}	
	}
	else
	{
		/*el beneficiario es familiar*/
		$buscar = mysql_query("SELECT id FROM datos_familiar WHERE cedula = '{$data[2]}' LIMIT 1
			");
		if (mysql_num_rows($buscar) == 1) {

			$familiar = mysql_fetch_assoc($buscar);
			$buscar2 = mysql_query("SELECT * FROM datos_extras WHERE beneficiario_id = '{$familiar['id']}' AND tipo = 'familiar' ");
			if (mysql_num_rows($buscar2) == 1) {

				$extra = mysql_fetch_assoc($buscar2);
				$duplicado++;
				echo "<b> LINEA #".$linea." >> Familiar ".$data[3]." ".$data[4]." posee dato cronico con ID: ".$extra['id']." </b> <br>";
			}
			else
			{
				$no_duplicado++;
			}
			
		}
		else
		{
			$no_exist++;
			echo "LINEA #".$linea." >> Familiar ".$data[3]." ".$data[4]." no encontrado <br>";
		}	
	}
		
		
	$linea++;
}

fclose($fp);
echo "<hr>";
echo "Datos de cronicos ya registrados: ".$duplicado."<br>";
echo "Datos de cronicos no registrados: ".$no_duplicado."<br>";
echo "Feneficiarios o titulares no encontrados: ".$no_exist."<br>";
 ?>