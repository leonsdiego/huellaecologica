<?php 

class Model_Usuario 
{

	private static $_instance;
	
	public static function getInstance()
	{
		if(null == self::$_instance)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	private function __construct(){}

	public function getUsuario($correo)
	{
		return Model_Mapper_Usuario::getInstance()->getUsuario($correo);
	}
	
	public function getIdUsuarioByCorreo($correo)
	{
		return Model_Mapper_Usuario::getInstance()->getIdUsuarioByCorreo($correo);
	}	
	
	public function getUsuarioById($id)
	{
		return Model_Mapper_Usuario::getInstance()->getUsuarioById($id);
	}
	
	public function getCorreoUsuario($id)
	{
		return Model_Mapper_Usuario::getInstance()->getCorreoUsuario($id);
	}
	
	public function getRolUsuario($id)
	{
		return Model_Mapper_Usuario::getInstance()->getRolUsuario($id);
	}
	
	public function cantidadUsuarios($rol)
	{
		return Model_Mapper_Usuario::getInstance()->cantidadUsuarios($rol);
	}
	
	public function ultimoEditor()
	{
		if( Model_Mapper_Usuario::getInstance()->ultimoEditor() == true )
		{
			return true;
		}
		return false;
	}
	
	public function updateUsuario($fila)
	{
		return Model_Mapper_Usuario::getInstance()->updateUsuario($fila);
	}
	
	public function usuarioExiste($correo)
	{
		if( null != Model_Mapper_Usuario::getInstance()->getUsuario($correo))
		{
			return true;
		}
		return false;
	}
	
	public function insertUsuario($info)
	{
		return Model_Mapper_Usuario::insertUsuario($info);
	}
	
	public function selectUsuario($correo)
	{		
		return Model_Mapper_Usuario::selectUsuario($correo);
	}
	
	/*
	*
	*	ESTADISTICAS		
	*
	*/
	
	public function contarUsuariosByEstado()
	{
		return Model_Mapper_Usuario::getInstance()->contarUsuariosByEstado();
	}
	
	public function contarUsuariosByGenero()
	{
		return Model_Mapper_Usuario::getInstance()->contarUsuariosByGenero();
	}
	
	public function contarUsuariosByInstruccion()
	{
		return Model_Mapper_Usuario::getInstance()->contarUsuariosByInstruccion();
	}
	
	public function contarRegistrosByMes()
	{
		return Model_Mapper_Usuario::getInstance()->contarRegistrosByMes();
	}
}
