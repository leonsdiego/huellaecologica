<?php 

class Model_Mapper_Grupo
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
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from('tbl_grupo')
											->order('orden_grupo ASC'));
	}
	
	public function getGrupoByFicha($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_grupo')->where('ficha_grupo = ?',$ficha));
	}
		
	public function getGrupoById($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_grupo')->where('id_grupo = ?',$id));
	}
		
	public function getNombreGrupo($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_grupo','nombre_grupo')->where('id_grupo = ?',$id));
	}
	
	public function getIdGrupo($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_grupo','id_grupo')->where('ficha_grupo = ?',$ficha));
	}
	
	public function updateGrupo($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_grupo',$fila,$db->quoteInto('id_grupo=?',$fila['id_grupo']));
	}
	
	public function insertGrupo($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_grupo',$fila);
	}
	/*
	public function getCuestionario()
	{
		$indicador[][] = Array();
		$alternativa[][] = Array();
		foreach ($grupo = $this->getGrupos())
		{
			$fichaIndicador = Model_Indicador::getIndicadoresByGrupo($grupo['id_grupo']);
			$indicador[$grupo['id_grupo']][]
		}
	}*/
}
