<?php
require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\abd;
function mostrarContenido() {
	//$html = '<div class="cont">';

	$html = '<div class="content-title"><h2> Last E-VENTS created.</h2></div>';
  	$app = abd\Aplicacion::getSingleton();
	$events = abd\Evento::userEvents($app->idUsuario()); 
	if($events){
		foreach ($events as $item => $event){
				$html .= '<div class="miniEvent" ><form method="POST" action="event.php" id="' . $event['nombre'] . '" class="null">
	            <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
	            <input class="imgEventMini" type="image" src="'. abd\Evento::showImageById($event['id_evento']).'" alt="Submit Form" /></form></div>';
				$html .= "<div><h1>" . $event['nombre'] . "</h1><p> Fecha: " . $event['fecha']  . "</p></div>";
				$html .=  '<div><form method="POST" action="updateEvento.php" class="null" enctype="">
	    			<input class="null" name="id_update" value="'. $event['id_evento'] . '" type="hidden" readonly>	
	    			<button class="buttonEdit" type="submit"><span class="button__inner">Edit</span></button>
	    			</form>';
	  			$html .=  '<form method="POST" action="deleteEvento.php" class="null" enctype="">
	    			<input class="null" name="id_delete" value="'. $event['id_evento'] . '" type="hidden" readonly>	
	    			<button class="buttonEdit buttonDelete" type="submit"><span class="button__inner">Delete</span></button>
	    			</form></div>';
				$html .= "</div>";
		  }
		}
	else
		  $html .= '<div class="content-title"><h3>No tienes eventos.</h3></div>';
	$html .= '</div></div>';
	return $html;
  return $html;
}
?><!DOCTYPE html>
<html>
<head>
  	<meta http-equiv="Content-Type" content="text/html" charset=utf-8">
  	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  	<title>Contenido</title>
</head>
<body>

<div id="contenedor">
<?php
$app->doInclude('comun/cabecera.php');
$app->doInclude('comun/sidebarIzq.php');
?>
<div id="contenido">
		<?= mostrarContenido() ?>
</div>
<?php
$app->doInclude('comun/pie.php');
?>
</div>
</body>
</html>