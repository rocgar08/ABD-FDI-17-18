<?php
// Varios defines para los parámetros de configuración de acceso a la BD y la URL desde la que se sirve la aplicación
define('BD_HOST', 'localhost');
define('BD_NAME', 'events');
define('BD_USER', 'root');
define('BD_PASS', '');
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '/PracticaABD/'); 
define('RUTA_CSS', RUTA_APP.'css/');
define('INSTALADA', true );

if (! INSTALADA) {
  echo "La aplicación no está configurada";
  exit();
}

/* */
/* Configuración del charset */
/* */

/* Enlaces de interés para el soporte de UTF-8:
 * https://www.toptal.com/php/a-utf-8-primer-for-php-and-mysql
 * https://allseeing-i.com/how-to-setup-your-php-site-to-use-utf8
 * http://www.instantshift.com/2014/10/29/mbstring-and-php/
 *
 * Una vez configurado hay que asegurarse de especificar la codificación 'UTF-8' en las funciones 
 * que tengan un parámetro charset (en PHP >= 5.6 suelen tomar el valor 'UTF-8' por defecto, 
 * pero en versiones anteriores no) y utilizar las funciones http://php.net/manual/en/book.mbstring.php.
 */

/*
 * PHP >= 5.6
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
/**
 * Función para autocargar clases PHP.
 *
 * @see http://www.php-fig.org/psr/psr-4/
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'es\\ucm\\fdi\\abd\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/* */
/* Inicialización del objeto aplicacion */
/* */
$app = \es\ucm\fdi\abd\Aplicacion::getSingleton();
$app->init(array('host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS), RUTA_APP, RAIZ_APP);
