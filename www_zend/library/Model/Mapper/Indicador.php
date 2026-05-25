<?php 

class Model_Mapper_Indicador
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
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_indicador')->where('id_indicador = ?',$id));
	}
	
	public function getIndicadores()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from(	array('i' => 'tbl_indicador'))
											->join(	array('g' => 'tbl_grupo'),'i.tbl_grupo_id_grupo = g.id_grupo')
											->join(	array('f' => 'tbl_factorConversion'),'i.tbl_factorConversion_id_factorConversion = f.id_factorConversion')
											->order('i.orden_indicador ASC'));
	}
	
	public function getIndicadorByFicha($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from('tbl_indicador')
											->where('ficha_indicador = ?',$ficha));
	}
	
	public function getIndicadoresByGrupo($idGrupo)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from('tbl_indicador')->where('tbl_grupo_id_grupo = ?',$idGrupo));
	}
	
	public function getGrupoByIndicador($idIndicador)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(('tbl_indicador'),'tbl_grupo_id_grupo')
											->where('id_indicador = ?',$idIndicador));
	}
	
	public function setCabeza($idIndicadorCabeza,$cabeza)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_indicador',$cabeza,$db->quoteInto('id_indicador=?',$idIndicadorCabeza));
	}
	
	public function getCabeza()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from('tbl_indicador')
											->where('tbl_indicador_id_indicador_anterior = id_indicador'));
	}
	
	public function getSiguiente($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(('tbl_indicador'),'id_indicador')
											->where('tbl_indicador_id_indicador_anterior = ?', $id));
	}
	
	public function getCabezaDeGrupo($idGrupo)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from('tbl_indicador','id_indicador')
											->where('tbl_grupo_id_grupo = ?',$idGrupo)
											->where('id_indicador = tbl_indicador_id_indicador_anterior'));
	}
	
	public function cuentaIndicador($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$cuenta = $db->fetchRow($db->select()	->from(('tbl_indicador'),array('val'=>'cuenta_indicador'))
											->where('id_indicador = ?',$id));
		return $cuenta['val'];
	}
	
	public function updateIndicador($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_indicador',$fila,$db->quoteInto('id_indicador=?',$fila['id_indicador']));
	}
	
	public function insertIndicador($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_indicador',$fila);
	}
	
	public function contarIndicadoresByGrupo()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(array("i"=>"tbl_indicador"),array("cant"=>"COUNT(i.id_indicador)","grupo"=>"g.nombre_grupo"))
											->join(array('g' => 'tbl_grupo'),'i.tbl_grupo_id_grupo = g.id_grupo')
											->group("tbl_grupo_id_grupo"));
	}
}