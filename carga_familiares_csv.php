<?php 

$server="localhost";
$username="root";
$password="";
$db='sidor';
$con=mysql_connect($server,$username,$password)or die("no se ha podido establecer la conexion");
$datos=mysql_select_db($db,$con)or die("la base de datos no existe");

$count = 0;
$error = 0;
$nofound = 0;
$line = 0;
ini_set ('auto_detect_line_endings','1');

$fp = fopen ("FAMILIARES_MAT.csv", "r");

while ($data = fgetcsv ($fp, 1000, ";")){
	$line++;
	$tip = $data[0];
	$ced = $data[1];
	$f = explode("/", $data[4]);
	$fecha = $f[2]."-".$f[1]."-".$f[0];
	if ($data[7] == "M") {
		$sexo = "Masculino";
	}
	else
	{
		$sexo = "Femenino";
	}

	$buscar = mysql_query("SELECT id FROM datos_familiar WHERE 
							tipo_doc = '{$tip}' AND 
							cedula = '{$ced}' LIMIT 1 ");
	if (mysql_num_rows($buscar) == 0) {
		
		$titular = mysql_query("SELECT id FROM datos_titular WHERE 
					tipo_doc = '$data[10]' AND
					cedula = '$data[11]' LIMIT 1 ");

			if (mysql_num_rows($titular) == 1) {
					
				$id_titular = mysql_fetch_assoc($titular);
				$insertar="INSERT INTO datos_familiar (tipo_doc, cedula, nombres, apellidos, fecha_nacimiento, telefono, direccion, sexo, email, parentesco, titular_id)
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
					'{$id_titular['id']}'
					)";

				$ejecutar = mysql_query($insertar);
				if($ejecutar){
				 	$count++;
				}
				else{
					$error++;
					echo "Line #".$line.": ".mysql_error()."<br>";
				}
				}	
		
	}
	else{
			$nofound++;
			
		}

}

fclose($fp);

echo "registros exitosos: ".$count."<br>";
echo "registros no guardados por error: ".$error."<br>";
echo "registro ya insertado: ".$nofound."<br>";
 ?>