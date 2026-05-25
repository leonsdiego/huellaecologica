<?php 

class Model_Mapper_Estado 
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

	public function getEstado($nombre)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_estado')->where('nombre_estado = ?',$nombre));
	}
	
	public function getUsuarioById($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_estado')->where('id_estado = ?',$id));
	}
	public function getListaEstados()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchCol($db->select()->from('tbl_estado','nombre_estado')
										  ->order('nombre_estado ASC'));
	}
}