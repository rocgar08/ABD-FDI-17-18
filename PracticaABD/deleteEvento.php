<?php
require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\abd;
?><!DOCTYPE html>
<html>
<head>
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  	<title>Borrar Evento</title>
</head>
<body>
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div class="col-10" id="contenido">
		<h1>Formulario para Borrar Evento </h1>
		<?php $formEvento = new FormularioBorrarEvento($_POST['id_delete']); $formEvento->gestiona(); ?> 
	</div>

	<?php
		$app->doInclude('comun/sidebarDer.php');
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>