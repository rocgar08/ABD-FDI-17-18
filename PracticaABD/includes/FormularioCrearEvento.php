<?php
namespace es\ucm\fdi\abd;

use \Datetime;

use es\ucm\fdi\abd\Aplicacion as App;
/**
 * Clase de  de gestión de formularios.
 *
 * Gestión de token CSRF está basada en: https://www.owasp.org/index.php/PHP_CSRF_Guard
 */
class FormularioCrearEvento extends Form {
  
  private $event;
  private $TEXTO_REGEXP = ".{3,30}";
  /** $PHP_DATE_REGEX creada por https://regex101.com
  **/
  private $PHP_DATE_REGEXP = "^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$";
  public function __construct() {
        $opciones= array('enctype'=>'multipart/form-data', 'class' => 'formNewEvent');//,'action'=>'includes/uploadImg.php');
        parent::__construct('formCreaEvento',$opciones);
        $this->fecha= new DateTime('now');
        $this->event=null;
    }
    protected function generaCamposFormulario ($datos) {
        $defaultName = '';
        $defaultPlace = '';
        $defaultPrice = '';
        $defaultDes = '';
        $defaultPrice='';
        $defaultDate=$this->fecha->format('Y-m-d');
        $today=$defaultDate;
            if ($datos) {
            $defaultName = isset($datos['EventName']) ? $datos['EventName'] : $defaultName;
            $defaultPlace = isset($datos['EventLugar']) ? $datos['EventLugar'] : $defaultPlace;
            $defaultPrice = isset($datos['EventPrice']) ? $datos['EventPrice'] :$defaultPrice;
            $defaultDes = isset($datos['EventDes']) ? $datos['EventDes'] : $defaultDes;
            $defaultDate = isset($datos['EventDate']) ? $datos['EventDate'] : $defaultDate;
            }
            $camposFormulario=<<<EOF
        <fieldset>
            <legend>Datos del Evento</legend>
            <p><label>Nombre:</label> <input type="text" name="EventName" placeholder="Nombre del evento" pattern="$this->TEXTO_REGEXP" value="$defaultName" required/></p>
            <p><label>Fecha:</label> <input type="date" name="EventDate" value="$defaultDate" min="$today" required/><br /></p>
            <p><label>Lugar:</label> <input type="text" name="EventLugar" placeholder="Lugar" value="$defaultPlace" required/><br /></p>
            <p><label>Descripcion:</label> <input type="textarea" name="EventDes" placeholder="Comenta de que trata el evento" value="$defaultDes" required/><br /></p>
            <p><label>Precio(euros):</label> <input type="number" name="EventPrice" placeholder="Si es gratis mejor" value="$defaultPrice" /><br/></p>
            <p><label>Imagen:</label><input name="uploadImg" type="file" required /><br /></p>
            <p><button type="submit" name=submit>Crear</button></p>
        </fieldset>
EOF;
        return $camposFormulario;
    }
    protected function procesaFormulario($datos) {
        $result = array();
        $ok=true;
        $EventName = isset($datos['EventName']) ? $datos['EventName'] : null ;
        if (mb_strlen($EventName)<5 || $EventName=='Nombre del evento') {
            $result[] = 'Tiene que tener un nombre, min 5 caracteres.';
            $ok=false;
            return $result;
        }
      
        $EventPrice = isset($datos['EventPrice'])? $datos['EventPrice'] : null ;
        if($EventPrice < 0 || $EventPrice ==NULL){
            $result[] = 'El precio tiene que ser mayor o igual que 0';
            $ok = false;
            return $result;
        }
        $EventDate = isset($datos['$EventDate'])? $datos['$EventDate'] : null ;
        if(strtotime($EventDate)  >  strtotime ($this->fecha->format('d-m-Y'))){
                $result[] = 'La fecha tiene que ser superior o igual al día de hoy';
                $ok=false;
                return $result;
        }
        if($ok===TRUE){
            $app = App::getSingleton();
            $datos['id_usuario']=$app->idUsuario();
            $this->event=Evento::createEvent($datos['EventName'], $datos['EventDate'], $datos['EventLugar'], $datos['EventPrice'], $datos['id_usuario'],$datos['EventDes']);
            $check = FALSE;
            if(is_object($this->event) === TRUE){
                if(self::checkFile() === FALSE){
                    $this->event->deleteEvent();
                    $result[] = 'Tipo de archivo a subir no valido. ';
                }
                else{
                    $nombreOriginal=self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']);
                    if($this->event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal)!==TRUE)
                        $event->deleteEvent();
                        
                    $check = TRUE;
                }
            }
            else
                $result[]='Fallo en la BD. ';
        }
        
        $ok===TRUE && $check===TRUE ? $result = \es\ucm\fdi\abd\Aplicacion::getSingleton()->resuelve('/index.php') : $result;
        return $result;
    }
    
  private function checkFile(){
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