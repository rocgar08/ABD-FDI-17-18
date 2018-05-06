<?php

namespace es\ucm\fdi\abd;

use es\ucm\fdi\abd\Aplicacion as App;

/**
 * Clase de  de gestión de formularios.
 *
 * Gestión de token CSRF está basada en: https://www.owasp.org/index.php/PHP_CSRF_Guard
 */
class FormularioBorrarEvento extends Form {
 private $user;

  public function __construct($iduser) {
        $opciones = array('class' => 'formDeleteUser');
        parent::__construct('formRegister',$opciones);
        $this->user=new Evento($iduser);
    }


    protected function generaCamposFormulario ($datos) {
      $id=$this->user->id();
      $camposFormulario=<<<EOF
      <fieldset class="formDeleteUsuario">
        <legend>¿Seguro que quieres darte de baja ?</legend>
        <p><button  class=fieldDelete type="submit" name="formDelete" value="si">Si</button> 
        <input type="hidden" name="id_delete" value="id" />
        <button class=fieldDelete  type="submit" name="formDelete" value="no">No</button></p>
      </fieldset>
EOF;
      return $camposFormulario;
    }

    protected function procesaFormulario($datos) {
        $result = array();
        $Eliminar = isset($datos['formDelete']) ? $datos['formDelete'] : null ;  
        if($Eliminar && strcmp($Eliminar,'no')===0){
            $result = \es\ucm\fdi\abd\Aplicacion::getSingleton()->resuelve('/contenido.php');
        }
        else {
        if($this->user->deleteUser() === TRUE)
            $result = \es\ucm\fdi\abd\Aplicacion::getSingleton()->resuelve('/contenido.php');
        else
            $result[]= 'ERROR 101';
        }

        return $result;
    }

  }//Class
  ?>