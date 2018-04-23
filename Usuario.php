<?php 
class Usuario{
	  private $id;
	  private $email;
	  private $password;
	  private $roles;
	  private $fecha;
	  private $nombre;
	  private $nombreUsuario;

	  private function __construct($id, $email, $password, $nombre, $nombreUsuario, $fecha) {
		$this->id = $id;
		$this->email = $email;
		$this->password = $password;
		$this->nombre = $nombre;
		$this->nombreUsuario=$nombreUsuario;
		$this->fecha = $fecha;
		$this->roles = [];
		}

	  public function id() {
	    return $this->id;
	  }
	  public function addRol($role) {
	    $this->roles[] = $role;
	  }
	  public function roles() {
	    return $this->roles;
	  }
	  public function email() {
	    return $this->email;
	  }
	  public function fecha() {
	    return $this->fecha;
	  }
	  public function nombre() {
	    return $this->nombre;
	  }
	  public function nombreUsuario(){
		return $this->nombreUsuario
	  }
	  public function compruebaPassword($password) {
	    return password_verify($password, $this->password);
	  }
	  public function cambiaPassword($nuevoPassword) {
	    $this->password = password_hash($nuevoPassword, PASSWORD_DEFAULT);
	  }
	}
?>
