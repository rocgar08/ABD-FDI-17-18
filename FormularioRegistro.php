<?php
namespace es\ucm\fdi\abd;

class FormularioRegistro extends Form {

  public function __construct() {
    parent::__construct('formRegister');
  }
  
     protected function generaCamposFormulario($datos)
    {
        $nombreUsuario = '';
        $nombre = '';
        if ($datos) {
            $nombreUsuario = isset($datos['nombreUsuario']) ? $datos['nombreUsuario'] : $nombreUsuario;
            $nombre = isset($datos['nombre']) ? $datos['nombre'] : $nombre;
        }
        $html = <<<EOF
    <fieldset>
      <label>Nombre de usuario:</label> <input  type="text" name="nombreUsuario" value="$nombreUsuario" />
      <label>Nombre completo:</label> <input  type="text" name="nombre" value="$nombre" />
      <label>Password:</label> <input  type="password" name="password" />
      <label>Vuelve a introducir el Password:</label> <input  type="password" name="password2" /><br />
      <button type="submit" name="registro">Registrar</button>
    </fieldset>
EOF;
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {
        $result = array();
        
        $nombreUsuario = isset($datos['nombreUsuario']) ? $datos['nombreUsuario'] : null;
        
        if ( empty($nombreUsuario) || mb_strlen($nombreUsuario) < 5 ) {
            $result[] = "El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.";
        }
        
        $nombre = isset($datos['nombre']) ? $datos['nombre'] : null;
        if ( empty($nombre) || mb_strlen($nombre) < 5 ) {
            $result[] = "El nombre tiene que tener una longitud de al menos 5 caracteres.";
        }
        
        $password = isset($datos['password']) ? $datos['password'] : null;
        if ( empty($password) || mb_strlen($password) < 5 ) {
            $result[] = "El password tiene que tener una longitud de al menos 5 caracteres.";
        }
        $password2 = isset($datos['password2']) ? $datos['password2'] : null;
        if ( empty($password2) || strcmp($password, $password2) !== 0 ) {
            $result[] = "Los passwords deben coincidir";
        }
        
        if (count($result) === 0) {
            $user = Usuario::crea($nombreUsuario, $nombre, $password, 'user');
            if ( ! $user ) {
                $result[] = "El usuario ya existe";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $nombreUsuario;
                $result = 'index.php';
            }
        }
        return $result;
    }
  }
}
?>