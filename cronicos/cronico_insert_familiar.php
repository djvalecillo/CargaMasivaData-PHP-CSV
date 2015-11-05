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
	
	$ced_tit = $data[1];
	$ced_fam = $data[2];

	if ($data[0] != "TITULAR") {
		
		$buscar = mysql_query("SELECT id FROM datos_familiar WHERE  
							cedula = '{$ced_fam}' LIMIT 1 ");
		if (mysql_num_rows($buscar) == 0) {
			
			$titular = mysql_query("SELECT id FROM datos_titular WHERE 
						cedula = '{$ced_tit}' LIMIT 1 ");

				if (mysql_num_rows($titular) == 1) {
						
					$id_titular = mysql_fetch_assoc($titular);
					$insertar="INSERT INTO datos_familiar (tipo_doc, cedula, nombres, apellidos, fecha_nacimiento, telefono, direccion, sexo, email, parentesco, titular_id)
					VALUES (
						'V', 
						'$data[2]', 
						'$data[3]', 
						'$data[4]',
						'$fecha',
						'$data[6]',
						'$data[8]', 
						'$sexo',  
						'$data[7]', 
						'$data[0]', 
						'{$id_titular['id']}'
						)";

					$ejecutar = mysql_query($insertar);
					if($ejecutar){
					 	$count++;
					}
					else{
						$error++;
						echo "Line #".$linea.": ".mysql_error()."<br>";
					}
				}
				else
				{
					//no se encntro al titular
					echo "Line #".$linea.": titular no encontrado ".$data[3]." ".$data[4]."<br>";
				}
		}
		else
		{
			$exist++;
		}
	
	}	
		
	$linea++;
}

fclose($fp);
echo "<hr>";
echo "Datos de titular registrados: ".$count."<br>";
echo "Datos de titular no registrados: ".$error."<br>";
echo "titulares existentes: ".$exist."<br>";
 ?>