<?php

namespace es\ucm\fdi\abd;
use es\ucm\fdi\abd\Aplicacion as App;

class Evento {
  private $id_evento;
  private $nombre;
  private $fecha;
  private $lugar;
  private $precio;
  private $id_usuario;
  private $descripcion;

  public function __construct($id_evento, $nombre=null, $fecha=null, $lugar=null, $precio=null, $id_usuario=null, $descripcion=null) {
    $this->id_evento = $id_evento;
    $this->nombre = $nombre;
    $this->fecha = $fecha;
    $this->lugar = $lugar;
    $this->precio = $precio;
    $this->id_usuario = $id_usuario;
    $this->descripcion=$descripcion;
  }
  /*Borra una evento y la imagen asociado a ese evento*/
  public function deleteEvent(){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("DELETE FROM Imagen WHERE id_evento=$this->id_evento");
    $rs = $conn->query($query);
    $query = sprintf("DELETE FROM Eventos WHERE id_evento=$this->id_evento");
    $rs = $conn->query($query);
    if ($rs===TRUE) 
        return true;
    else
        return 'Error al eliminar el evento.';    
   }
   
  public static function createEvent($EventName, $EventDate, $EventLugar, $precio, $idUser, $EventDes) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("INSERT INTO Eventos (nombre,fecha,lugar,precio,id_usuario,descripcion) VALUES('%s','%s','%s',$precio,$idUser,'%s')",$conn->real_escape_string($EventName),$conn->real_escape_string($EventDate),
            $conn->real_escape_string($EventLugar),$conn->real_escape_string($EventDes) );
    $rs = $conn->query($query);
    if ($rs===TRUE) {
      $event=new Evento($conn->insert_id, $EventName, $EventDate, $EventLugar, $precio, $idUser, $EventDes);
      return $event;
    }   
    else 
      return false;//'Error al crear el evento, fallo en la BD.';     
  }
  
  public static function searchEventById($id_evento) { //nombre
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos WHERE id_evento='%s'", $conn->real_escape_string($id_evento));
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $event = new Evento($fila['id_evento'], $fila['nombre'], $fila['fecha'], $fila['lugar'], $fila['precio'], $fila['id_usuario'],$fila['descripcion']);
      $rs->free();
      return $event;
    }
    return false;
  }
  
public static function searchEventByName($EventName,$idUser) { //nombre
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos WHERE nombre='%s' and id_usuario=$idUser", $conn->real_escape_string($EventName));
    $rs = $conn->query($query);
    if ($rs and $rs->num_rows > 0) {
      $rs->free();
      return true;
    }
    return false;
  }

  public static function searchEvents($value) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $value = htmlspecialchars($value);
    $value = $conn->real_escape_string($value);
    $query = sprintf("SELECT * FROM Eventos WHERE nombre LIKE '%%".$value."%%' OR lugar LIKE '%%".$value."%%'");
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows > 0) {
      $events = array();
      while($row = $rs->fetch_assoc()){
        $events[] = $row;
      }
      $rs->free();
      return $events;
    }
    else
      return false;
  }

  public function deleteImage(){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT ruta FROM Imagen WHERE id_evento=$this->id_evento");
    if($rs = $conn->query($query)){
      $fila = $rs->fetch_assoc();
      //$rs-<free;
      $rutaArchivo =$fila['ruta']; //Obtengo la dir.
      if(unlink($rutaArchivo)){
        $query = sprintf("DELETE FROM Imagen WHERE id_evento=$this->id_evento");
      return  $conn->query($query) ? true : 'Error al borrar el archivo. ';
      }
    }
    return 'Error al borrar el archivo. ';
  }
  

  public function addImage($rutaTemporal​, $nombreOriginal){
    if(file_exists($rutaTemporal​)){
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $extension = substr(strrchr($nombreOriginal, "."), 1); //Obtengo la extension del archivo.
      $rutaArchivo = dirname(__DIR__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'fotos-eventos'.DIRECTORY_SEPARATOR.$this->id_evento.'.'.$extension;
      if (file_exists($rutaArchivo) )
        $query = sprintf("UPDATE Imagen SET ruta='%s' WHERE id_evento=$this->id_evento",$conn->real_escape_string($rutaArchivo));
      else
        $query = sprintf("INSERT INTO Imagen (id_evento,ruta) VALUES($this->id_evento,'%s')",$conn->real_escape_string($rutaArchivo));
    if(move_uploaded_file($rutaTemporal​, $rutaArchivo)){
        $rs = $conn->query($query);
        return $rs;
      }
      else
        return 'No se ha podido mover la imagen';
    }
    return 'No exsiste el archivo tmp. ';
  }

public  function showImageById($id_evento){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT ruta 
                      FROM Imagen
                      WHERE id_evento=$id_evento");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      while($row = $rs->fetch_assoc())
        $foto = $row['ruta'];
      $rs->free();
      return substr($foto,15);
    }   
  }
  public function updateEvent() {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("UPDATE Eventos SET nombre='%s',fecha='%s',lugar='%s',precio=$this->precio,descripcion='%s'  WHERE id_evento=$this->id_evento",
      $conn->real_escape_string($this->nombre),$conn->real_escape_string($this->fecha),$conn->real_escape_string($this->lugar),$conn->real_escape_string($this->descripcion));
    $rs = $conn->query($query);
    return $rs===TRUE ? true : 'Error al actualizar el evento. ';
  }

  public function allEvents() {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $events = array();
      while($row = $rs->fetch_assoc()){
        //$events[] = $row;
        array_push($events,$row);
      }
      $rs->free();
      return $events;
    }
    else
      return false;
  }
 
  public function userEvents($id, $num = null) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    if($num == null)
    $query = sprintf("SELECT * FROM Eventos WHERE id_usuario = $id");
    else
    $query = sprintf("SELECT * FROM Eventos WHERE id_usuario = $id ORDER BY id_evento DESC LIMIT %s", $conn->real_escape_string($num));
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $events = array();
      while($row = $rs->fetch_assoc()){
        if($row['id_usuario'] == $id){
          array_push($events,$row);
        }
      }
      $rs->free();
      return $events;
    }
    else
      return false;
  }
  
  public function showAllEvents() {
    $html = "Lista de eventos: <br>";
    $events = array();
    $events = Evento::allEvents();
    if($events){
      foreach( $events as $item => $event) 
        $html .= "<h1>" . $event['nombre'] . "</h1> <br>" . "<p> Fecha:" . $event['fecha'] . " Precio:"  . $event['precio'] . "€ Lugar: " . $event['lugar'] . "</p> <br>";
   }
   else  $html .= "No hay eventos.";
   return $html;
  }

  public function showUserEvents($id) {
    $html = 'Lista de eventos: ' . "\n";
    $events = Evento::userEvents($id); 
    if($events){
      foreach ($events as $item => $event){
        //$html .= "<1h>" . $item->nombre_evento() . "</1h> \n" . "<p>" . $item->fecha_evento() . " "  . $item->precio_evento() . "</p> \n";
        $html .= "<h1>" . $event['nombre'] . "</h1> <br>" . "<p> Fecha:" . $event['fecha'] . " Precio:"  . $event['precio'] . "€ Lugar: " . $event['lugar'] . "</p> <br>";
      }
    }
    else{
      $html .= "No hay eventos.";
    }
    return $html;
  }

  //--
  public function id_evento() {
    return $this->id_evento;
  }
  public function id_usuario() {
    return $this->id_usuario;
  }
  public function nombre_evento() {
    return $this->nombre;
  }
  public function fecha_evento() {
    return $this->fecha;
  }
  public function lugar_evento() {
    return $this->lugar;
  }
  public function precio_evento() {
    return $this->precio;
  }
  public function descripcion_evento(){
    return $this->descripcion;
  }
//--
  public function cambiarId($nuevo_nombre) {
    $this->id_evento = $nuevo_nombre;
  }
  public function cambiarName($nuevo_nombre) {
    $this->nombre = $nuevo_nombre;
  }
  public function cambiaDate($nueva_fecha) {
    $this->fecha = $nueva_fecha;
  }
  public function cambiaPlace($nuevo_lugar) {
    $this->lugar = $nuevo_lugar;
  }
  public function cambiaPrice($nuevo_precio) {
    $this->precio = $nuevo_precio;
  }
  public function cambiarDescripcion($des){
    $this->descripcion=$des;
  }
//--
}//Class
?>