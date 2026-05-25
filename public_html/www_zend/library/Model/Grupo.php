<?php 

class Model_Grupo
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

	public function getGrupos()
	{
		return Model_Mapper_Grupo::getInstance()->getGrupos();
	}
	
	public function getGrupoByFicha($ficha)
	{
		return Model_Mapper_Grupo::getInstance()->getGrupoByFicha($ficha);
	}
	
	public function getGrupoById($id)
	{
		return Model_Mapper_Grupo::getInstance()->getGrupoById($id);
	}
	
	public function fichaExiste($ficha)
	{
		if( null != Model_Mapper_Grupo::getGrupoByFicha($ficha))
		{
			return true;
		}
		return false;
	}
	
	public function getNombreGrupo($id)
	{
		return Model_Mapper_Grupo::getInstance()->getNombreGrupo($id);
	}
	
	public function getIdGrupo($nombre)
	{
		return Model_Mapper_Grupo::getInstance()->getNombreGrupo($nombre);
	}
	
	public function updateGrupo($fila)
	{
		return Model_Mapper_Grupo::getInstance()->updateGrupo($fila);
	}
	
	public function insertGrupo($fila)
	{
		return Model_Mapper_Grupo::getInstance()->insertGrupo($fila);
	}
	
	public function hacerMatriz()
	{
		$cuestionario = array();
		foreach($this->getGrupos() as $grupo)
		{	
			$i = 0;
			foreach(Model_Indicador::getIndicadoresByGrupo($grupo['id_grupo']) as $indicador)
			{
				$a = 0;
				foreach(Model_Alternativa::getAlternativasByIndicador($indicador['id_indicador']) as $alternativa)
				{
					//$cuestionario[$i][$a] = 
					$a++;
				}
				$i++;
			}
		}
	}
}
