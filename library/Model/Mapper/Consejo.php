<?php 

class Model_Mapper_Consejo
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
			$db = Zend_Db_Table::getDefaultAdapter();
			return $db->fetchRow($db->select()->from('tbl_consejo')->where('id_consejo = ?',$id));
		}
	public function getConsejos()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()	->from(array('c' => 'tbl_consejo'))
											->join(array('g' => 'tbl_grupo'),'c.tbl_grupo_id_grupo = g.id_grupo')
											->order('nombre_grupo ASC'));
	}
	
	public function getConsejoByFicha($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_consejo','id_consejo')->where('ficha_consejo = ?',$ficha));
	}
	
	public function getIdConsejo($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_consejo','id_consejo')->where('ficha_consejo = ?',$ficha));
	}
	
	public function updateConsejo($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_consejo',$fila,$db->quoteInto('id_consejo=?',$fila['id_consejo']));
	}
	
	public function insertConsejo($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_consejo',$fila);
	}
}
