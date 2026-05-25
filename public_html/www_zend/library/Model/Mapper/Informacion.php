<?php 

class Model_Mapper_Informacion 
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

	public function getInformacion($ficha)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_informacion')->where('ficha_informacion = ?',$ficha));
	}
	
	public function getInformacionById($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_informacion')->where('id_informacion = ?',$id));
	}
	
	public function listarInformacion()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from('tbl_informacion'));
	}
	
	public function updateInformacion($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_informacion',$fila,$db->quoteInto('id_informacion=?',$fila['id_informacion']));
	}
	
	public function insertInformacion($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_informacion',$fila);
	}
}