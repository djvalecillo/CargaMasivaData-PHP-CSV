<?php 

$server="localhost";
$username="root";
$password="";
$db='sidor';
$con=mysql_connect($server,$username,$password)or die("no se ha podido establecer la conexion");
$datos=mysql_select_db($db,$con)or die("la base de datos no existe");

$count = 0;
$error = 0;
$duplicados = 0;
ini_set ('auto_detect_line_endings','1');

$fp = fopen ("TITULARES_MAT.csv", "r");

while ($data = fgetcsv ($fp, 1000, ";")){
	
	$f = explode("/", $data[4]);
	$fecha = $f[2]."-".$f[1]."-".$f[0];
	if ($data[7] == "M") {
		$sexo = "Masculino";
	}
	else
	{
		$sexo = "Femenino";
	}
		
		$confirma = mysql_query("SELECT id FROM datos_titular WHERE tipo_doc = '$data[0]' AND cedula = '$data[1]' ");
		if (mysql_num_rows($confirma) > 0 ) {

			$duplicados++;
			
		}
		else
		{
			$insertar="INSERT INTO datos_titular (tipo_doc, cedula, nombres, apellidos, fecha_nacimiento, telefono, direccion, sexo, email, rif_contratante, nombre_contratante, sfsaseg)
				VALUES (
					'$data[0]', 
					'$data[1]', 
					'$data[2]', 
					'$data[3]', 
					'$fecha', 
					'$data[5]',
					'$data[6]', 
					'$sexo',  
					'$data[8]', 
					'$data[9]',
					'$data[10]',
					'$data[11]'
					)";

			$ejecutar = mysql_query($insertar);
			if($ejecutar){
			 	$count++;
			}
			else{
				$error++;
				echo "error al registrar: ".mysql_error()."<br>";
			}
		}	

}

fclose($fp);

echo "registros exitosos: ".$count."<br>";
echo "registros no guardados por error: ".$error."<br>";
echo "Registros que ya estar registrados: ".$duplicados."<br>";
 ?>