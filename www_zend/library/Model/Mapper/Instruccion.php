<?php 

class Model_Mapper_Instruccion 
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

	public function getInstruccion($nombre)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_estado')->where('nombre_instruccion = ?',$nombre));
	}
	
	public function getInstruccionById($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_instruccion','nombre_instruccion')->where('id_instruccion = ?',$id));
	}
	public function getListaNiveles()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from('tbl_instruccion',array('nombre_instruccion'=>'nombre_instruccion','nivel_instruccion'=>'nivel_instruccion'))
										  ->order('nivel_instruccion ASC'));
	}
}