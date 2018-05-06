<?php

namespace es\ucm\fdi\abd;

class FormularioLogin extends Form {

  private $HTML5_EMAIL_REGEXP = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$";
  private $HTML5_EMAIL_TITLE = "Debes introducir una dirección de E-mail válida";
  private $TEXTO_REGEXP = ".{0,30}";
  private $TEXTO_TITLE = "Cualquier cosa";
  private $PASS_REGEXP = ".{5,255}";
  private $PASS_TITLE = "Debes introducir una contraseña válida (a partir de 5 caracteres)";

  public function __construct() {
    parent::__construct('formLogin');
  }
  protected function generaCamposFormulario($datos) {
    $email = 'user@example.org';
    $password = '';
    if ($datos) {
      $email = isset($datos['email']) ? $datos['email'] : $email;
      $password = isset($datos['password']) ? $datos['password'] : $password;
    }
    $camposFormulario=<<<EOF
    <fieldset>
      <legend>Login</legend>
      <p><label>E-mail:</label> <input type="email" pattern="$this->HTML5_EMAIL_REGEXP" title="$this->HTML5_EMAIL_TITLE" name="email" value="$email" required/></p>
      <p><label>Password:</label> <input type="password" name="password" value="$password" required/><br /></p>
      <button id="submit" type="submit">Entrar</button>
    </fieldset>
EOF;
    return $camposFormulario;
  }

  /*Compruebo los campos del formulario
   *Miro que no coincidan con las expresiones regulares definidas nateriormente
  **/
  protected function procesaFormulario($datos) {
    $result = array();
    $ok = true;
    $email = isset($datos['email']) ? $datos['email'] : null ;
    if (!$email || ! mb_ereg_match($this->HTML5_EMAIL_REGEXP, $email) ) {
      $result[] = $this->HTML5_EMAIL_TITLE;
      $ok = false;
    }
    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( !$password || !mb_ereg_match($this->PASS_REGEXP, $password) ) {
      $result[] = 'Usuario o contraseña incorrectos';
      $ok = false;
    }
    if ( $ok ) {
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
    return $result;
  }
}
?>