<?php 
$server="localhost";
$username="root";
$password="";
$db='sidor';
$con=mysql_connect($server,$username,$password)or die("no se ha podido establecer la conexion");
$datos=mysql_select_db($db,$con)or die("la base de datos no existe");

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php 
$sql = mysql_query("SELECT * FROM datos_extras");
?>

<form action="ficheroExcel.php" method="POST" target="_blank" id="FormularioExportacion">
	<button type="button" class="botonExcel btn btn-success btn-sm pull-right"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>	

<table class="table" border="1" id="table">
	<thead>
		<tr>
			<th>Parentesco</th>
			<th>T.Doc titular</th>
			<th>Cedula Titular</th>
			<th>T.Doc Feneficiario </th>
			<th>Cedula Feneficiario</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Fecha Nac</th>
			<th>Telefono</th>
			<th>Correo</th>
			<th>Direccion</th>
			<th>Sexo</th>
			<th>Fecha Vence</th>
			<th>Patologia</th>
			<th>Medicmento</th>
			<th>Frecuencia</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			while ($data = mysql_fetch_assoc($sql)) { ?>
			<tr>
				<?php 
				if ($data['tipo'] == "titular") {
					$titular = mysql_query("SELECT * FROM datos_titular WHERE id = '{$data['beneficiario_id']}' LIMIT 1 ");
					$tit = mysql_fetch_assoc($titular);?>

						<td>Titular</td>
						<td> <?php echo $tit['tipo_doc']; ?> </td>
						<td> <?php echo $tit['cedula']; ?> </td>
						<td> <?php echo $tit['tipo_doc']; ?> </td>
						<td> <?php echo $tit['cedula']; ?> </td>
						<td> <?php echo $tit['nombres']; ?> </td>
						<td> <?php echo $tit['apellidos']; ?> </td>	
						<td> <?php echo $tit['fecha_nacimiento']; ?> </td>
						<td> <?php echo $tit['telefono']; ?> </td>
						<td> <?php echo $tit['email']; ?> </td>
						<td> <?php echo $tit['direccion']; ?> </td>
						<td> <?php echo $tit['sexo']; ?> </td>
			<?php	}
				else if($data['tipo'] == "familiar"){
					$familiar = mysql_query("SELECT * FROM datos_familiar WHERE id = '{$data['beneficiario_id']}' LIMIT 1 ");
					$fam = mysql_fetch_assoc($familiar);

					$titu = mysql_query("SELECT tipo_doc, cedula FROM datos_titular WHERE id = '{$fam['titular_id']}' LIMIT 1 ");
					$titulares = mysql_fetch_assoc($titu);
				?>
					<td><?php echo $fam['parentesco']; ?></td>
					<td> <?php echo $titulares['tipo_doc']; ?> </td>
					<td> <?php echo $titulares['cedula']; ?> </td>
					<td> <?php echo $fam['tipo_doc']; ?> </td>
					<td> <?php echo $fam['cedula']; ?> </td>
					<td> <?php echo $fam['nombres']; ?> </td>
					<td> <?php echo $fam['apellidos']; ?> </td>	
					<td> <?php echo $fam['fecha_nacimiento']; ?> </td>
					<td> <?php echo $fam['telefono']; ?> </td>
					<td> <?php echo $fam['email']; ?> </td>
					<td> <?php echo $fam['direccion']; ?> </td>
					<td> <?php echo $fam['sexo']; ?> </td>

			<?php	}

			?>
					<td> <?php echo $data['fecha_vencimiento']; ?> </td>
					<td> <?php echo $data['patologias']; ?> </td>
					<td> <?php echo $data['medicamento']; ?> </td>
					<td> <?php echo $data['frecuencia_tratamiento']; ?> </td>
				

			</tr>
			<?php }
		 ?>
	</tbody>
</table>



</body>
</html>
<script src="jquery.js" type="text/javascript" ></script>
<script>
	$(document).ready(function() {
    $(".botonExcel").click(function(event) {
      $("#datos_a_enviar").val( $("<div>").append( $("#table").eq(0).clone()).html());
      $("#FormularioExportacion").submit();
    });
  });
</script>