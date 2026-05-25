<?php

class UsuarioController extends Controller_Abstract
{

    public function init()
    {
        /* Initialize action controller here */
		//Agrego las operaciones del init de Controller/Abstract y agrego el layout-admin
		parent::init();
		$this->_helper->_layout->setLayout('layout');
    }

	public function perfilAction()
	{
		//Llena la lista de Estados
		$this->view->estados = Model_Estado::getInstance()->getListaEstados();
		$this->view->instruccion = Model_Instruccion::getInstance()->getListaNiveles();
		//Inicializar variable de Mensajes
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		//Actualización de datos de usuario
		if($this->getRequest()->getPost('botonUpdatePerfil'))
		{
			$flashMessenger->addMessage('¡Bien! el perfil se actualizó correctamente.');
			$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
			Model_Usuario::getInstance()->updateUsuario(array(
				'id_usuario'=>$this->getRequest()->getParam('id_usuario'),
				'nombre_usuario'=>$this->getRequest()->getParam('nombre_usuario'),
				'sexo_usuario'=>$this->getRequest()->getParam('sexo_usuario'),
				'tbl_instruccion_nombre_instruccion'=>$this->getRequest()->getParam('instruccion_usuario'),
				'profesion_usuario'=>$this->getRequest()->getParam('profesion_usuario'),
				'fechaNac_usuario'=>$this->getRequest()->getParam('fechaNac_usuario'),
				'tbl_estado_nombre_estado'=>$this->getRequest()->getParam('estado_usuario'),
				/*'municipio_usuario'=>$this->getRequest()->getParam('municipio_usuario')*/
			));
			
		}
		$correo = Zend_Auth::getInstance()->getIdentity();
		$this->view->perfil = Model_Usuario::getInstance()->getUsuario($correo);
		
		//Llena la lista de cuestionarios respondidos por el usuario
		$idUsuario = Model_Usuario::getInstance()->getIdUsuarioByCorreo($correo);
		$this->view->cuestionarios = Model_Cuestionario::getInstance()->getCuestionariosTerminadosByUsuario($idUsuario);
		
	}
	
	public function resultadosAction()
	{
		//Acciones
		
		$idCuestionario = $this->getRequest()->getParam('cuestionario');
		$correo = Zend_Auth::getInstance()->getIdentity();
		$usuario = Model_Usuario::getInstance()->getUsuario($correo);
		$this->view->perfil =$usuario;
		$idUsuario = $usuario['id_usuario'];
		
		//Verifica que el cuestionario pasado como parámetro efectivamente es del usuario que ha iniciado sesión
		if ( false == Model_Cuestionario::getInstance()->cuestionarioEsDeUsuario($idUsuario,$idCuestionario))
		{
			$redirect = '/../usuario/perfil';
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}
		//Verifica que el cuestionario pasado como parámetro esté terminado
		$estatus = Model_Cuestionario::getInstance()->getEstatus($idCuestionario);
		if( 'terminado' !=  $estatus['estatus_cuestionario'])
		{
			$redirect = '/cuestionario/responder';
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}
		$cuestionario = Model_Cuestionario::getInstance()->getCuestionario($idCuestionario);
		$fecha = date("l, F d Y"); 
		$fecha = strtotime($cuestionario['fecha_cuestionario']);
		$this->view->fecha = date("j/m/Y", $fecha).' a las '.date("g:ia ", $fecha);
		$this->view->huella = Model_CuestionarioAlternativa::getInstance()->getHuella($idCuestionario);
		$this->view->grupos = Model_Grupo::getInstance()->getGrupos();
		$this->view->resultadosGrupos = Model_CuestionarioAlternativa::getInstance()->getResultadosAcumGrupos($idCuestionario);
		$this->view->idCuestionario = $idCuestionario;
	}
	
	public function consejosAction()
	{
		$idCuestionario = $this->getRequest()->getParam('cuestionario');
		$correo = Zend_Auth::getInstance()->getIdentity();
		$usuario = Model_Usuario::getInstance()->getUsuario($correo);
		$this->view->perfil =$usuario;
		$idUsuario = $usuario['id_usuario'];
		
		//Verifica que el cuestionario pasado como parámetro efectivamente es del usuario que ha iniciado sesión
		if ( false == Model_Cuestionario::getInstance()->cuestionarioEsDeUsuario($idUsuario,$idCuestionario))
		{
			$redirect = '/../usuario/perfil';
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}
		//Verifica que el cuestionario pasado como parámetro esté terminado
		$estatus = Model_Cuestionario::getInstance()->getEstatus($idCuestionario);
		if( 'terminado' !=  $estatus['estatus_cuestionario'])
		{
			$redirect = '/cuestionario/responder';
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}
		$cuestionario = Model_Cuestionario::getInstance()->getCuestionario($idCuestionario);
		$fecha = date("l, F d Y"); 
		$fecha = strtotime($cuestionario['fecha_cuestionario']);
		$this->view->fecha = date("j/m/Y", $fecha).' a las '.date("g:ia ", $fecha);
		$this->view->idCuestionario = $idCuestionario;
		$this->view->grupos = Model_Grupo::getInstance()->getGrupos();
		$this->view->consejos = Model_CuestionarioAlternativa::getInstance()->getConsejosByCuestionario($idCuestionario);
	}
}

