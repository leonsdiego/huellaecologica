<?php 

class Model_EncuestaIndicador
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
	
	public function getIndicadoresByEncuesta($idEncuesta)
	{
		return Model_Mapper_EncuestaIndicador::getInstance()->getIndicadoresByEncuesta($idEncuesta);
	}
	
	public function getEncuestaByIndicador($idIndicador)
	{
		return Model_Mapper_EncuestaIndicador::getInstance()->getEncuestaByIndicador($idIndicador);
	}
	
	public function insertEncuestaIndicador($idEncuesta,$idIndicador)
	{
		return Model_Mapper_EncuestaIndicador::getInstance()->insertEncuestaIndicador($idEncuesta,$idIndicador);
	}
	
	public function updateEncuestaIndicador($idEncuesta,$idIndicador)
	{
		return Model_Mapper_EncuestaIndicador::getInstance()->updateEncuestaIndicador($idEncuesta,$idIndicador);
	}
}