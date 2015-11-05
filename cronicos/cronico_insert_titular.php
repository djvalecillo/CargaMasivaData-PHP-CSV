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
$exist = 0;
ini_set ('auto_detect_line_endings','1');

$fp = fopen ("Farmagroup_consolidado.csv", "r");

while ($data = fgetcsv ($fp, 1000, ";")){

	$f = explode("/", $data[5]);
	$fecha = $f[2]."-".$f[1]."-".$f[0];
	if ($data[9] == "M") {
		$sexo = "Masculino";
	}
	else if ($data[9] == "F")
	{
		$sexo = "Femenino";
	}
	
	if ($data[0] == "TITULAR") {
		
		$buscar = mysql_query("SELECT id FROM datos_titular WHERE cedula = '{$data[1]}' LIMIT 1
			");
		if (mysql_num_rows($buscar) == 1) {

			$exist++;
			
		}
		else
		{
			$sql="INSERT INTO datos_titular (tipo_doc, cedula, nombres, apellidos, fecha_nacimiento, telefono, direccion, sexo, email, rif_contratante, nombre_contratante, sfsaseg)
				VALUES (
					'V', 
					'$data[1]', 
					'$data[3]', 
					'$data[4]', 
					'$fecha', 
					'$data[6]',
					'$data[8]', 
					'$sexo',  
					'$data[7]', 
					'G-200106263',
					'SIDOR, C.A.',
					'I'
					)";

			$insertar = mysql_query($sql);
			
			if ($insertar) {
				
				$count++;
			}
			else
			{
				$error++;
				echo "LINEA #".$linea." Error al registrar titular: ".mysql_error()."<br>";
			}
		}	
	}
	else
	{
		
	}
		
		
	$linea++;
}

fclose($fp);
echo "<hr>";
echo "Datos de titular registrados: ".$count."<br>";
echo "Datos de titular no registrados: ".$error."<br>";
echo "titulares existentes: ".$exist."<br>";
 ?>