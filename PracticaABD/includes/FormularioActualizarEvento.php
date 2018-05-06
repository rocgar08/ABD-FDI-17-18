<?php

use \Datetime;
use es\ucm\fdi\abd\Aplicacion as App;
/**
 * Clase de  de gestión de formularios.
 *
 * Gestión de token CSRF está basada en: https://www.owasp.org/index.php/PHP_CSRF_Guard
 */
class FormActualizarEvento extends Form {
  
  private $event;
  private $TEXTO_REGEXP = ".{3,30}";
  
  public function __construct($idEvento) {
        $opciones= array('enctype'=>'multipart/form-data', 'class' => 'formUpdateEvent');//,'action'=>'includes/uploadImg.php');
        parent::__construct('formActualizaEvento',$opciones);
        $this->fecha= new DateTime('now');
        $this->event=new Evento($idEvento);
    }
    public function __construct(){
      $this->event = $this->event->id_evento();
    }
    protected function generaCamposFormulario ($datos) {
        $this->event=Evento::searchEventById($this->event->id_evento());
        $defaultName = $this->event->nombre_evento();
        $defaultPlace = $this->event->lugar_evento();
        $defaultPrice = $this->event->precio_evento();
        $defaultDes = $this->event->descripcion_evento();
        $defaultDate= $this->event->fecha_evento();
        $today=$this->fecha->format('Y-m-d');
        $id=$this->event->id_evento();
    $camposFormulario=<<<EOF
    <fieldset>
      <legend>Datos del Evento</legend>
      <p><label>Nombre:</label> <input type="text" name="EventName" placeholder="Nombre del evento" pattern="$this->TEXTO_REGEXP" value="$defaultName" required/></p>
      <p><label>Fecha:</label> <input type="date" name="EventDate" value="$defaultDate" min="$today" required/><br /></p>
      <p><label>Lugar:</label> <input type="text" name="EventLugar" placeholder="Lugar" value="$defaultPlace" required/><br /></p>
      <p><label>Descripcion:</label> <input type="text" name="EventDes" placeholder="Comenta de que trata el evento" value="$defaultDes" required/><br /></p>
      <p><label>Precio(euros):</label> <input type="number" name="EventPrice"  value="$defaultPrice" /><br/></p>
      <input type="hidden" name="id_update" value="$id" />
	  <p><label>Imagen:</label><input name="uploadImg" type="file" /><br /></p>
      <p><button type="submit">Actualizar</button></p>
    </fieldset>
EOF;
    
      return $camposFormulario;
    }
    protected function procesaFormulario($datos) {
        $result = array();
        $ok=true;
        $EventName = isset($datos['EventName']) ? $datos['EventName'] : null ;
        if ( mb_strlen($EventName)<5 || $EventName=='Nombre del evento') {
            $result[] = 'Tiene que tener un nombre, min 5 caracteres.';
            $ok=false;
            return FALSE;
        }
	    
    	$EventPrice = isset($datos['EventPrice'])? $datos['EventPrice'] : null ;
        if($EventPrice < 0 || $EventPrice ==NULL){
            $result[] = 'El precio tiene que ser mayor o igual que 0';
            $ok = false;
            return $result;
        }
	    
        $EventDate  = isset($datos['EventDate']) ? $datos['EventDate'] : null ;
        if(strtotime($EventDate) < strtotime ($this->fecha->format('d-m - Y'))){
                $result[] = 'La fecha tiene que ser superior o igual al día de hoy';
                $ok=false;
                return $result;
        }
        
        if($ok===TRUE){
            $check = FALSE;
            $this->event->cambiarName($datos['EventName']); $this->event->cambiaDate($datos['EventDate']); $this->event->cambiaPlace($datos['EventLugar']);
            $this->event->cambiaPrice($datos['EventPrice']); $this->event->cambiarDescripcion($datos['EventDes']);
            $check = $this->event->updateEvent();
            if($check===TRUE){
                if(isset($_FILES['uploadImg'])){
                    if(self::checkFile()===FALSE)
                        $result[]='Tipo de archivo a subir no valido.';
                    else{
                        $nombreOriginal=self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']);
                        $check=$this->event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal);
                    }
                }
                
            }
            else
                $result[]=$check;
        }
        
        $ok===TRUE && $check===TRUE ? $result = \es\ucm\fdi\abd\Aplicacion::getSingleton()->resuelve('/index.php') : $result;
        return $result;
  
    }
    
  private static function checkFile(){
    //Si solo es un unico elemento y no hay errores.
    $ok=TRUE;
    $ok = count($_FILES) == 1 && $_FILES['uploadImg']['error'] == UPLOAD_ERR_OK;
    //$ok=($ok && (mb_ereg_match('/^[0-9A-Z-_\.]+$/i',$_FILES['uploadImg']['name']) === 1) ? true : false );
    $ok=$ok && ($_FILES['uploadImg']['type'] =="image/jpeg"
    || $_FILES['uploadImg']['type'] =="image/jpg"
    || $_FILES['uploadImg']['type'] =="image/png" );
    $ok=$ok && ($uploadedfile_size=$_FILES['uploadImg']['size']<=300000);
    return $ok===TRUE ? self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']) : FALSE;
  }
  private function sanitize_file_uploaded_name($filename) {
      /* Remove anything which isn't a word, whitespace, number
       * or any of the following caracters -_~,;[]().
       * If you don't need to handle multi-byte characters
       * you can use preg_replace rather than mb_ereg_replace
       * Thanks @Łukasz Rysiak!
       */
    $newName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
      // Remove any runs of periods (thanks falstro!)
    $newName = mb_ereg_replace("([\.]{2,})", '', $newName);
    return $newName;
  }
}//Class
?>