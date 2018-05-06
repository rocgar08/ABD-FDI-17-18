<?php
namespace es\ucm\fdi\abd;

use es\ucm\fdi\abd\Aplicacion as App;

class FormularioRegistro extends Form {
  private $HTML5_EMAIL_REGEXP = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$";
  private $HTML5_EMAIL_TITLE = "Debes introducir una dirección de E-mail válida";
  private $TEXTO_REGEXP = ".{0,30}";
  private $TEXTO_TITLE = "Cualquier cosa";
  private $PASS_REGEXP = ".{5,255}";
  private $PASS_TITLE = "Debes introducir una contraseña válida (a partir de 5 caracteres)";
  public function __construct() {
    parent::__construct('formRegister');
  }
  protected function generaCamposFormulario($datos) {
    $app = App::getSingleton();
    if ($app->usuarioLogueado()) {
      $camposFormulario='<p>Debes cerrar sesion para registrar un nuevo usuario.</p>';
    }
    else{
      $email = '';
      $nombre = '';
      $apellidos ='';
      $password = '';
      if ($datos) {
        $email = isset($datos['email']) ? $datos['email'] : $email;
        $nombre = isset($datos['nombre']) ? $datos['nombre'] : $email;
        $apellidos = isset($datos['apellidos']) ? $datos['apellidos'] : $email;
        $password = isset($datos['password']) ? $datos['password'] : $password;
      }
      $camposFormulario=<<<EOF
      <fieldset class="formEvents">
        <legend>Registro</legend>
        <p>E-mail </p>
        <input class="field" type="email" placeholder="ejem@example.com" pattern="$this->HTML5_EMAIL_REGEXP" title="$this->HTML5_EMAIL_TITLE" name="email" value="$email" required/>
        <p>Nombre </p>
        <input class="field" type="text" placeholder="Tu nombre" pattern="$this->TEXTO_REGEXP" title="$this->TEXTO_TITLE" name="nombre" value="$nombre" required/>
        <p>Apellidos </p>
        <input class="field" type="text" placeholder="Tus apellidos" pattern="$this->TEXTO_REGEXP" title="$this->TEXTO_TITLE" name="apellidos" value="$apellidos" required/>
        <p>Password</p>
        <input class="field" type="password" placeholder="Contraseña" pattern="$this->PASS_REGEXP" title="$this->PASS_TITLE" name="password" value="$password" required/>
        <button class="field"  id="submit" type="submit" >Registrarse</button>
    </fieldset>
EOF;
    }
    return $camposFormulario;
  }
  protected function procesaFormulario($datos) {
    $result = array();
    $ok = true;
    $email = isset($datos['email']) ? $datos['email'] : null ;
    if ( !$email || ! mb_ereg_match($this->HTML5_EMAIL_REGEXP, $email) ) {
      $result[] = $this->HTML5_EMAIL_TITLE;
      $ok = false;
    }
    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( ! $password || ! mb_ereg_match($this->PASS_REGEXP, $password) ) {
      $result[] = $this->PASS_TITLE;
      $ok = false;
    }
    $nombre = isset($datos['nombre']) ? $datos['nombre'] : null ;
    if ( ! $nombre || ! mb_ereg_match($this->TEXTO_REGEXP, $nombre) ) {
      $result[] = $this->TEXTO_TITLE;
      $ok = false;
    }
    $apellidos = isset($datos['apellidos']) ? $datos['apellidos'] : null ;
    if ( ! $apellidos || ! mb_ereg_match($this->TEXTO_REGEXP, $apellidos) ) {
      $result[] = $this->TEXTO_TITLE;
      $ok = false;
    }
    if ( $ok ) {
      $user = Usuario::register($email, $nombre, $apellidos, $password);
      if ( $user ) {
        $user = Usuario::login($email, $password);
        if ( $user ) {
          session_regenerate_id(true);
          Aplicacion::getSingleton()->login($user);
          $result = \es\ucm\fdi\abd\Aplicacion::getSingleton()->resuelve('/index.php');
        }
        else {
          $result[] = 'Usuario o contraseña incorrectos';
        }
      }
      else {
        $result[] = "Ese e-mail ya estaba registrado";
      }
    }
    return $result;
  }
}
?>