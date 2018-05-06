<?php
use es\ucm\fdi\abd;

function mostrarSaludo() {
  $html = '';
  $app = abd\Aplicacion::getSingleton();
  if ($app->usuarioLogueado()) {
    $nombreUsuario = $app->nombreUsuario();
    $logoutUrl = $app->resuelve('/logout.php');
    $html = "Bienvenido, ${nombreUsuario}.<a href='${logoutUrl}'>(salir)</a>";
  } else {
    $loginUrl = $app->resuelve('/login.php');
    $html = "Usuario desconocido. <a href='${loginUrl}'>Login</a>";
  }

  return $html;
}

?>
<div id="cabecera">
	<h1>E-Vents</h1>
  <div class = "Buscar">
     <form method = "GET" action = "search.php">
     <input name="search" placeholder="Buscar " type="text"> 
      <input type="submit" class="inputSearch" value="Buscar"/>
    </form>
  </div>
	<div class="saludo">
	  <?=	mostrarSaludo() ?>
	</div>
</div>

