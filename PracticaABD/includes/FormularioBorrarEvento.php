<?php

namespace es\ucm\fdi\abd;
use \Datetime;

use es\ucm\fdi\abd\Aplicacion as App;
/**
 * Clase de  de gestión de formularios.
 *
 * Gestión de token CSRF está basada en: https://www.owasp.org/index.php/PHP_CSRF_Guard
 */
class FormularioBorrarEvento extends Form {
  private $event;

  public function __construct($idEvento) {
        $opciones = array('class' => 'formDeleteEvent');
        parent::__construct('formCreaEvento',$opciones);
        $this->event=new Evento($idEvento);
    }


    protected function generaCamposFormulario ($datos) {
        $id=$this->event->id_evento();
      
      $camposFormulario=<<<EOF
      <fieldset class="formDeleteEvents">
        <legend>¿Seguro que quieres eliminar este evento?</legend>
        <p><button  class=fieldDelete type="submit" name="formDelete" value="si">Si</button> 
        <input type="hidden" name="id_delete" value="$id" />
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
        if($this->event->deleteImage() === TRUE)
            ($this->event->deleteEvent() === TRUE) ? $result = \es\ucm\fdi\abd\Aplicacion::getSingleton()->resuelve('/contenido.php') : $result;
        else
            $result[]= 'ERROR 101';
        }

        return $result;
    }

  }//Class
  ?>