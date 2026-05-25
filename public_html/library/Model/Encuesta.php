<?php 

class Model_Encuesta
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
	
	public function getEncuestas()
	{
		return Model_Mapper_Encuesta::getInstance()->getEncuestas();
	}
	
	public function getEncuestaActiva()
	{
		return Model_Mapper_Encuesta::getInstance()->getEncuestaActiva();
	}
}