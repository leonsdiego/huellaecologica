<?php 

class Model_Mapper_Usuario 
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

	public function getUsuario($correo)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_usuario')->where('email_usuario = ?',$correo));
	}
	
	public function getIdUsuarioByCorreo($correo)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()	->from(('tbl_usuario'),'id_usuario')
											->where('email_usuario = ?',$correo));
	}
	
	public function getUsuarioById($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_usuario')->where('id_usuario = ?',$id));
	}
	
	public function getCorreoUsuario($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_usuario','email_usuario')->where('id_usuario = ?',$id));
	}
	
	public function getRolUsuario($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($db->select()->from('tbl_usuario','rol_usuario')->where('id_usuario = ?',$id));
	}
	
	public function cantidadUsuarios($rol)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$cantUsuarios = $db->fetchRow($db->select()	->from("tbl_usuario", array("num"=>"COUNT(id_usuario)"))
										->where("rol_usuario = ?",$rol));
		return $cantUsuarios['num'];
	}
	
	public function ultimoEditor()
	{
		$cantEditores = self::cantidadUsuarios('editor');
		if( $cantEditores == 2 )
		{
			return true;
		}
		return false;
	}
	
	public function updateUsuario($fila)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update('tbl_usuario',$fila,$db->quoteInto('id_usuario=?',$fila['id_usuario']));
	}
	
	public function insertUsuario($info)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert('tbl_usuario',$info);
	}
	
	public function contarUsuariosByEstado()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(array("u"=>"tbl_usuario"),array("cant"=>"COUNT(u.id_usuario)","estado"=>"u.tbl_estado_nombre_estado"))
											->group("tbl_estado_nombre_estado"));
	}
	public function contarUsuariosByGenero()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(("tbl_usuario"),array("cant"=>"COUNT(id_usuario)","sexo"=>"sexo_usuario"))
											->group("sexo_usuario"));
	}
	
	public function contarUsuariosByInstruccion()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(array("u"=>"tbl_usuario"),array("cant"=>"COUNT(u.id_usuario)","instruccion"=>"u.tbl_instruccion_nombre_instruccion"))
											->group("u.tbl_instruccion_nombre_instruccion"));
	}
	
	public function contarRegistrosByMes()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->fetchAll($db->select()->from(array("u"=>"tbl_usuario"),array("mes"=>"DATE_FORMAT(u.fechaRegistro_usuario,'%Y-%m')","cant"=>"COUNT(u.id_usuario)"))
											->group("DATE_FORMAT(u.fechaRegistro_usuario,'%Y-%m')"));
	}
}
