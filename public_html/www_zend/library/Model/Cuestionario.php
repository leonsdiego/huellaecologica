<?php 

class Model_Cuestionario 
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

	public function getCuestionarios()
	{
		return Model_Mapper_Cuestionario::getInstance()->getCuestionarios();
	}
	
	public function getCuestionario($idCuestionario)
	{
		return Model_Mapper_Cuestionario::getInstance()->getCuestionario($idCuestionario);
	}
	
	public function nuevoCuestionario($idUsuario,$estatus,$idEncuesta,$idIndicadorCabeza,$idIndicadorCola)
	{
		//Para cada nuevo cuestionario, el indicador cabeza es igual al indicador ultimo
		return Model_Mapper_Cuestionario::getInstance()->nuevoCuestionario($idUsuario,$estatus,$idEncuesta,$idIndicadorCabeza,$idIndicadorCola);
	}
	
	public function getEstatus($idCuestionario)
	{
		return Model_Mapper_Cuestionario::getInstance()->getEstatus($idCuestionario);
	}
	
	public function setEstatus($idCuestionario,$estatus)
	{
		return Model_Mapper_Cuestionario::getInstance()->setEstatus($idCuestionario,$estatus);
	}
	
	public function getCuestionariosByUsuario($idUsuario)
	{
		return Model_Mapper_Cuestionario::getInstance()->getCuestionariosByUsuario($idUsuario);
	}
	
	public function getCuestionariosTerminadosByUsuario($idUsuario)
	{
		return Model_Mapper_Cuestionario::getInstance()->getCuestionariosByUsuario($idUsuario);
	}
		
	public function getCuestionarioComenzadoByUsuario($idUsuario)
	{
		foreach( $this->getCuestionariosByUsuario($idUsuario) as $cuestionario )
		{
			if('comenzado' == $cuestionario['estatus_cuestionario'])
			{
				return $cuestionario['id_cuestionario'];
			}
		}
		return false;
	}
	
	public function updateUltimo($idCuestionario, $idIndicador)
	{
		return Model_Mapper_Cuestionario::getInstance()->updateUltimo($idCuestionario, $idIndicador);
	}
	
	public function getUltimoIndicador($idCuestionario)
	{
		return Model_Mapper_Cuestionario::getInstance()->getUltimoIndicador($idCuestionario);
	}
	
	public function getCola($idCuestionario)
	{
		return Model_Mapper_Cuestionario::getInstance()->getCola($idCuestionario);
	}
	
	public function contarCuestionarios()
	{
		return Model_Mapper_Cuestionario::getInstance()->contarCuestionarios();
	}
	
	public function contarCuestionariosByUsuario($idUsuario)
	{
		return Model_Mapper_Cuestionario::getInstance()->contarCuestionariosByUsuario($idUsuario);
	}
	
	public function contarCuestionariosByMes()
	{
		return Model_Mapper_Cuestionario::getInstance()->contarCuestionariosByMes();
	}
	
	public function cuestionarioEsDeUsuario($idUsuario,$idCuestionario)
	{
		foreach ( $this->getCuestionariosByUsuario($idUsuario) as $cuestionarioUsuario)
		{
			if( $idCuestionario == $cuestionarioUsuario['id_cuestionario'] )
			{
				return true;
			}
		}
		return false;	
	}
	
}
