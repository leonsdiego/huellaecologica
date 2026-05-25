<?php 

class Model_Mapper_Alternativa
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

	public function getAlternativa($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_alternativa')->where('id_alternativa = ?',$id));
	}
	
	public function getAlternativas()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(array('a' => 'tbl_alternativa'))
											->join(array('i' => 'tbl_indicador'),'a.tbl_indicador_id_indicador = i.id_indicador')
											->join(array('c' => 'tbl_consejo'),'a.tbl_consejo_id_consejo = c.id_consejo')
											->order('orden_alternativa ASC','orden_indicador ASC'));
	}
	
	public function getAlternativasOrdenIndicador()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(array('a' => 'tbl_alternativa'))
											->join(array('i' => 'tbl_indicador'),'a.tbl_indicador_id_indicador = i.id_indicador')
											->join(array('c' => 'tbl_consejo'),'a.tbl_consejo_id_consejo = c.id_consejo')
											->order('orden_indicador ASC','orden_alternativa ASC'));
	}
	
	public function getIndicadorSiguiente($idAlternativa)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from('tbl_alternativa','tbl_indicador_id_indicador_siguiente')
											->where('id_alternativa = ?',$idAlternativa));
	}
	
	public function getAlternativaByFicha($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_alternativa')->where('ficha_alternativa = ?',$ficha));
	}
	
	public function getAlternativasByIndicador($idIndicador)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from (array('a' => 'tbl_alternativa'))
											->where('a.tbl_indicador_id_indicador = ?',$idIndicador)
											->join(array('c' => 'tbl_consejo'),'a.tbl_consejo_id_consejo = c.id_consejo')
											->join(array('i' => 'tbl_indicador'),'a.tbl_indicador_id_indicador = i.id_indicador')
											->order('orden_alternativa ASC'));
	}
	
	public function getConsejoByAlternativa($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$consejo = $db->fetchRow($db->select()	->from (array('a' => 'tbl_alternativa'),'tbl_consejo_id_consejo')
												->where('a.id_alternativa = ?',$id));
		return $consejo['tbl_consejo_id_consejo'];
	}
	
	public function getIndicador($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from ('tbl_alternativa','tbl_indicador_id_indicador')
											->where('id_alternativa = ?',$id));
	}
	
	public function getPesoAlternativa($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$peso = $db->fetchRow($db->select()	->from(('tbl_alternativa'),'peso_alternativa')
									->where('id_alternativa = ?',$id));
		return $peso['peso_alternativa'];
	}
	
	public function updateAlternativa($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_alternativa',$fila,$db->quoteInto('id_alternativa=?',$fila['id_alternativa']));
	}
	
	public function insertAlternativa($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_alternativa',$fila);
	}
}
