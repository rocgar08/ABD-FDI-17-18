<?php

require_once __DIR__.'/includes/config.php';
use \es\ucm\fdi\abd;
function mostrar(){
	$formEvento;
	if(isset($_POST['submit'])){
		if($_POST['id_delete']== "formDelete"){
			$formEvento = new FormularioBorrarUsuario(); 
			$formEvento->gestiona();
		}
		return $formEvento;
	}
}
	
?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Mensajes</title>
</head>
<body>
<div id="contenedor">
<?php
$app->doInclude('comun/cabecera.php');
$app->doInclude('comun/sidebarIzq.php');
?>
	<div id="contenido">
		<h1>Formulario para Borrar Usuario </h1>
		<?php $formEvento = new FormularioBorrarUsuario(); $formEvento->gestiona();?>
	</div>

<?php
$app->doInclude('comun/pie.php');
?>
</div>
</body>
</html>