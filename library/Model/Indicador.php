<?php 

class Model_Indicador
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

	public function getIndicador($id)
	{
		return Model_Mapper_Indicador::getInstance()->getIndicador($id);
	}
	
	public function getIndicadores()
	{
		return Model_Mapper_Indicador::getInstance()->getIndicadores();
	}
	
	public function getIndicadoresByGrupo($idGrupo)
	{
		return Model_Mapper_Indicador::getInstance()->getIndicadorByGrupo($idGrupo);
	}
	
	public function getIndicadorByFicha($ficha)
	{
		return Model_Mapper_Indicador::getInstance()->getIndicadorByFicha($ficha);
	}
	
	public function getGrupoByIndicador($idIndicador)
	{
		return Model_Mapper_Indicador::getInstance()->getGrupoByIndicador($idIndicador);
	}
	
	public function fichaExiste($ficha)
	{
		if( null != Model_Indicador::getIndicadorByFicha($ficha))
		{
			return true;
		}
		return false;
	}
	
	public function setCabeza($idIndicadorCabeza)
	{
		$cabeza = array('tbl_indicador_id_indicador_anterior'=>$idIndicadorCabeza);
		return Model_Mapper_Indicador::getInstance()->setCabeza($idIndicadorCabeza,$cabeza);
	}
	
	public function getCabeza()
	{
		return Model_Mapper_Indicador::getInstance()->getCabeza();
	}
	
	public function getCola()
	{
		return Model_Mapper_Indicador::getInstance()->getCola();
	}
	
	public function setCola()
	{
		return Model_Mapper_Indicador::getInstance()->getCola();
	}
	
	public function getSiguiente($id)
	{
		return Model_Mapper_Indicador::getInstance()->getSiguiente($id);
	}
	
	public function getCabezaDeGrupo($idGrupo)
	{
		return Model_Mapper_Indicador::getInstance()->getCabezaDeGrupo($idGrupo);
	}
	
	public function updateIndicador($fila)
	{
		return Model_Mapper_Indicador::getInstance()->updateIndicador($fila);
	}
	
	public function insertIndicador($fila)
	{
		return Model_Mapper_Indicador::getInstance()->insertIndicador($fila);
	}
	
	public function deleteIndicador($id)
	{
		return Model_Mapper_Indicador::getInstance()->deleteIndicador($id);
	}
	
	public function contarIndicadoresByGrupo()
	{
		return Model_Mapper_Indicador::getInstance()->contarIndicadoresByGrupo();
	}
	
	public function cuentaIndicador($id)
	{
		return Model_Mapper_Indicador::getInstance()->cuentaIndicador($id);
	}
	
	/* FORMULAS */
	
	public function getCantidadHabitantes($idCuestionario)
	{
		$mayores = doubleval(Model_CuestionarioAlternativa::getInstance()->getResuladoAlternativaCuestionarioByFichaIndicador('mayoresvivienda',$idCuestionario));
		$menores = doubleval(Model_CuestionarioAlternativa::getInstance()->getResuladoAlternativaCuestionarioByFichaIndicador('menoresvivienda',$idCuestionario));
		$habitantes = doubleval($mayores)+doubleval($menores);
		return doubleval($habitantes);
	}
	
	public function getCantidadHabitaciones($idCuestionario)
	{
		$habitaciones = Model_CuestionarioAlternativa::getInstance()->getResuladoAlternativaCuestionarioByFichaIndicador('cantidadhabitaciones',$idCuestionario);
		echo '<pre>Habitaciones: '.$habitaciones.'</pre>';
		return double($habitaciones);
	}
	
	
}