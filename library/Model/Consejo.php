<?php 

class Model_Consejo
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

	public function getConsejo($id)
	{
		return Model_Mapper_Consejo::getInstance()->getConsejo($id);
	}
	
	public function getConsejos()
	{
		return Model_Mapper_Consejo::getInstance()->getConsejos();
	}
	
	public function getConsejoByFicha($ficha)
	{
		return Model_Mapper_Consejo::getInstance()->getConsejoByFicha($ficha);
	}
	
	public function fichaExiste($ficha)
	{
		if( null != Model_Mapper_Consejo::getConsejoByFicha($ficha))
		{
			return true;
		}
		return false;
	}
	
	public function getIdConsejo($nombre)
	{
		return Model_Mapper_Consejo::getInstance()->getNombreConsejo($nombre);
	}
	
	public function updateConsejo($fila)
	{
		return Model_Mapper_Consejo::getInstance()->updateConsejo($fila);
	}
	
	public function insertConsejo($fila)
	{
		return Model_Mapper_Consejo::getInstance()->insertConsejo($fila);
	}	
}
