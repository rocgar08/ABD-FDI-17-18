<?php
use es\ucm\fdi\abd;

$app = abd\Aplicacion::getSingleton();
?>

<div id="sidebar-left">
	<h3>Menú</h3>
	<ul>
		<li><a href="<?= $app->resuelve('/index.php')?>">Inicio</a></li>
		<li><a href="<?= $app->resuelve('/contenido.php')?>">Ver contenido</a></li>
		
		<?php
			if ($app->usuarioLogueado()) {
		?>
		<li><a href="<?= $app->resuelve('/createEvento.php')?>">Crear Evento</a></li>
		<li><a href="<?= $app->resuelve('/deleteEvento.php')?>">EliminarEvento</a></li>
		<li><a href="<?= $app->resuelve('/deleteUsuario.php')?>">Eliminar Usuario</a></li>
		<?php
			}
		?>
		<li><a href="<?= $app->resuelve('/register.php')?>">Registro</a></li>
		
	</ul>
</div>
