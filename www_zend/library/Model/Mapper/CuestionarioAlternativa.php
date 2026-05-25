<?php 

class Model_Mapper_CuestionarioAlternativa
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

	public function insertRespuesta($idCuestionario,$idAlternativa,$resultado,$idIndicador,$grupo,$idConsejo,$cuenta)
	{
		$fila = array(
						'tbl_cuestionario_id_cuestionario' => $idCuestionario,
						'tbl_alternativa_id_alternativa' => $idAlternativa,
						'resultado_cuestionario_alternativa' => doubleval($resultado),
						'tbl_grupo_id_grupo' => $grupo,
						'tbl_consejo_id_consejo' => $idConsejo,
						'resultado_cuenta_cuestionario_alternativa' => $cuenta,
						'tbl_indicador_id_indicador' => $idIndicador);
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_cuestionario_has_tbl_alternativa',$fila);
	}

	
	public function getCuestionarioByAlternativa($idAlternativa)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(('tbl_cuestionario_has_tbl_alternativa'),'tbl_cuestionario_id_cuestionario')
											->where('tbl_alternativa_id_alternativa = ?',$idAlternativa));
	}
	
	public function contarAlternativas($idCuestionario)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from(array("c"=>"tbl_cuestionario_has_tbl_alternativa"),array("cant"=>"COUNT(c.tbl_alternativa_id_alternativa)"))
											->where('c.tbl_cuestionario_id_cuestionario = ?',$idCuestionario));
	}
	
	public function getResultadoByIndicadorCuestionario($idCuestionario,$idIndicador)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$resultado = $db->fetchRow($db->select()	->from('tbl_cuestionario_has_tbl_alternativa',array('resultado'=>											'resultado_cuestionario_alternativa'))
													->where('tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
													->where('tbl_indicador_id_indicador = ?',$idIndicador));
		return doubleval($resultado['resultado']);
	}
	
	public function getConsejosByCuestionario($idCuestionario)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->distinct()
											->from(array("ca"=>"tbl_cuestionario_has_tbl_alternativa"),'ca.tbl_consejo_id_consejo')
											->where('ca.tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->join(array('c'=>'tbl_consejo'),'c.id_consejo = ca.tbl_consejo_id_consejo')
											->join(array('g'=>'tbl_grupo'),'c.tbl_grupo_id_grupo = g.id_grupo'));
	}
	
	public function resultadoExiste($idCuestionario,$idIndicador)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(array("c"=>"tbl_cuestionario_has_tbl_alternativa"),array("cant"=>"COUNT(c.tbl_alternativa_id_alternativa)"))
											->where('c.tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->where('c.tbl_indicador_id_indicador = ?',$idIndicador)
											->order('g.orden_grupo ASC'));
	}
	
	public function getAlternativa($idCuestionario,$idIndicador)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(array('c'=>'tbl_cuestionario_has_tbl_alternativa'))
											->where('c.tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->where('c.tbl_indicador_id_indicador = ?',$idIndicador)
											->join(array('a' => 'tbl_alternativa'),'c.tbl_alternativa_id_alternativa = a.id_alternativa'));
	}
	
	/*
	*
	*	RESULTADOS
	*
	*/
	
	public function getHuella($idCuestionario)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from('tbl_cuestionario_has_tbl_alternativa',
												array('resultado'=>'resultado_cuestionario_alternativa'))
											->where('tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->where('resultado_cuenta_cuestionario_alternativa = 1'));
	}
	
	public function getResultadosByCuestionario($idCuestionario)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from('tbl_cuestionario_has_tbl_alternativa',
												array('resultado'=>'resultado_cuestionario_alternativa','grupo'=>'tbl_grupo_id_grupo'))
											->where('tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->order('tbl_grupo_id_grupo ASC'));
	}
	
	public function getResultadosByGrupo($idCuestionario,$idGrupo)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from('tbl_cuestionario_has_tbl_alternativa',array('resultado'=>'resultado_cuestionario_alternativa'))
											->where('tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->where('tbl_grupo_id_grupo = ?',$idGrupo));
	}
	
	
	public function getAlternativasByCuestionario($idCuestionario)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from(	array('c' => 'tbl_cuestionario_has_tbl_alternativa'),'c.tbl_alternativa_id_alternativa')
											->where('c.tbl_cuestionario_id_cuestionario = ?',$idCuestionario)
											->join(array('a' => 'tbl_alternativa'),'c.tbl_alternativa_id_alternativa = a.id_alternativa'));
	}
	
	//No se usa (verificar)
	public function getUltimaAlternativa()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->query('SELECT tbl_alternativa_id_alternativa, max(orden_cuestionario_alternativa) FROM tbl_cuestionario_has_tbl_alternativa GROUP BY tbl_alternativa_id_alternativa'));
            
	}
	
	//No se usa (verficar)
	public function getAlternativaByOrden($orden)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(('tbl_cuestionario_has_tbl_alternativa'),'orden_cuestionario_alternativa')
											->where('tbl_alternativa_id_alternativa = ?',$orden));
	}
	
	//No se usa (verificar)
	public function getSiguiente($idCuestionario)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(('tbl_cuestionario_has_tbl_alternativa'),'tbl_encuesta_id_encuesta')
											->where('tbl_indicador_id_indicador = ?',$idIndicador));
	}
}
