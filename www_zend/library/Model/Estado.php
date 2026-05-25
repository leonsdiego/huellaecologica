<?php 

class Model_Estado
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

	public function getEstado($nombre)
	{
		return Model_Mapper_Estado::getInstance()->getEstado($nombre);
	}
	public function getEstadoById($id)
	{
		return Model_Mapper_Estado::getInstance()->getEstadoById($id);
	}
	public function getListaEstados()
	{
		return Model_Mapper_Estado::getInstance()->getListaEstados();
	}
}
