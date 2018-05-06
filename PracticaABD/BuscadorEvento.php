<?php
require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\abd;
function mostrarContenido() {
    $value = $_GET['search'];
    $min_length = 3;
    $html ='<div class="search-switch"><div><form method = "GET" action = "searchEvents.php" class="formSearchEvent">
        <input class="null" name="search" value="'. $value . '" type="hidden" readonly>	
        <button class="button-switch"><span>E-vents</span></button>
        </form></div>';
    $html .='<div><form method = "GET" action = "searchUsers.php" class="formSearchUser">
        <input class="null" name="search" value="'. $value . '" type="hidden" readonly>	
        <button class="button-switch"><span>Users</span></button>
        </form></div></div>';
    $html .= '<div class="search-title"><h1> Resultados busqueda E-vents: </h1></h1></div>';
	if(strlen($value) >= $min_length){
        $events = \es\ucm\fdi\abd\Evento::searchEvents($value);
        if($events){
            $html .= '<div class="search-title"><h3> Events found: </h3></div>';
            $html .= '<div class="content-search">';
            foreach( $events as $item => $event) {
                $html .= '<form method="POST" action="event.php" id="' . $event['nombre'] . '" class="null" enctype="">
                <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
                <button type="submit" id="searchButton"><h2>&#9724; ' . $event['nombre'] . '</h2></button>
                </form>';
            }
            $html .= '</div>';
        }
        
        else
            $html .= '<div class="search-title"><h3>No events found</h3></div>';
    }
    else{
		$html .= '<div class="search-title"><h3>Minimum length is ' . $min_length . '</h3></div>';
    }
    
    $html .= '</div>';
    return $html;
  }
?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
    <title>SearchResults</title>
</head>
<body>
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div class="col-10" id="contenido">
		<?= mostrarContenido() ?>
	</div>
	
	<?php
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>