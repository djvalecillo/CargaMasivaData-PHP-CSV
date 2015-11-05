<?php 
$server="localhost";
$username="root";
$password="";
$db='sidor_completo';
$con=mysql_connect($server,$username,$password)or die("no se ha podido establecer la conexion");
$datos=mysql_select_db($db,$con)or die("la base de datos no existe");

$count = 0;
$error = 0;
ini_set ('auto_detect_line_endings','1');

$fp = fopen ("original_data.csv", "r");

while ($data = fgetcsv ($fp, 1000, ";")){
	
	$f = explode("/", $data[6]);
	$fecha = $f[2]."-".$f[1]."-".$f[0];
	if ($data[9] == "M") {
		$sexo = "Masculino";
	}
	elseif($data[9] == "F")
	{
		$sexo = "Femenino";
	}
	
		$insertar="INSERT INTO data_original_sidor (
			TIPO_DE_DOCUMENTO,
			CEDULA_DE_IDENTIDAD,
			NOMBRE_DEL_TITULAR,
			SEGUNDO_NOMBRE,
			APELLIDO_DEL_TITULAR,
			SEGUNDO_APELLIDO,
			FECHA_NACIMIENTO_TITULAR,
			TELEFONO,
			DIRECCION,
			SEXO_DEL_TITULAR,
			EMAIL,
			PARENTESCO,
			TIPO_DE_DOCUMENTO_TITULAR,
			CEDULA_DE_IDENTIDAD_TITULAR,
			RIF_CONTRATANTE,
			NOMBRE_CONTRATANTE,
			STSASEG
			)
			VALUES (
				'$data[0]', 
				'$data[1]', 
				'$data[2]', 
				'$data[3]', 
				'$data[4]', 
				'$data[5]',
				'$fecha',
				'$data[7]',  
				'$data[8]', 
				'$sexo',
				'$data[10]',
				'$data[11]',
				'$data[12]',
				'$data[13]',
				'$data[14]',
				'$data[15]',
				'$data[16]'
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

fclose($fp);

echo "registros exitosos: ".$count."<br>";
echo "registros no guardados: ".$error."<br>";
 ?>
