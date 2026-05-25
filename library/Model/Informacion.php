<?php 

class Model_Informacion
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

	public function getInformacion($ficha)
	{
		return Model_Mapper_Informacion::getInstance()->getInformacion($ficha);
	}
	
	public function getInformacionById($id)
	{
		return Model_Mapper_Informacion::getInstance()->getInformacionById($id);
	}
	
	public function listarInformacion()
	{
		return Model_Mapper_Informacion::getInstance()->listarInformacion();
	}
	
	public function updateInformacion($fila)
	{
		return Model_Mapper_Informacion::getInstance()->updateInformacion($fila);
	}
	
	public function fichaExiste($ficha)
	{
		if( null != Model_Mapper_Informacion::getInstance()->getInformacion($ficha))
		{
			return true;
		}
		return false;
	}
	
	public function insertInformacion($fila)
	{
		return Model_Mapper_Informacion::getInstance()->insertInformacion($fila);
	}
}
