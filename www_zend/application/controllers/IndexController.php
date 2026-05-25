<?php

class IndexController extends Controller_Abstract
{

    public function indexAction()
    {
		//Acciones
		$this->view->sidebar1 = Model_Informacion::getInstance()->getInformacion('sidebar1');
		$this->view->sidebar2 = Model_Informacion::getInstance()->getInformacion('sidebar2');
		$this->view->contenido1 = Model_Informacion::getInstance()->getInformacion('contenido1');
    }
	
	//Registro de nuevos usuarios
	public function registroAction()
	{
		
		$this->view->sidebar3 = Model_Informacion::getInstance()->getInformacion('sidebar3');
		$this->view->sidebar6 = Model_Informacion::getInstance()->getInformacion('sidebar6');
		//Definiendo el url al que se va a direccionar el usuario una vez que se haya registrado correctamente
		$redirect = $this->getRequest()->getParam('redirect');
		if(!isset($redirect))
		{
			$redirect = '/index/login';
		}
		$this->view->redirect = $redirect;
		
		// Averiguar si existe una sesi�n para no mostrar el formulario de registro
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}
		
		//Enviar mensaje de error si el usuario est� duplicado
		if($this->getRequest()->getParam('duplicado'))
		{
			$this->view->mensaje = 'El usuario '.$this->getRequest()->getParam("duplicado").' ya se encuentra registrado';
		}
		
		
		//Recibe los datos del formulario de registro
		if($this->getRequest()->getPost('botonRegistro'))
		{
			$user_ip = $this->getRequest()->getServer('REMOTE_ADDR');
			$user_pass = SHA1($this->getRequest()->getParam('pass_usuario'));
			//Validar que el usuario no se encontraba registrado
			if( false == Model_Usuario::usuarioExiste(strtolower($this->getRequest()->getParam('email_usuario'))))
			{
			Model_Usuario::insertUsuario(array(
				'email_usuario'=>strtolower($this->getRequest()->getParam('email_usuario')),
				'pass_usuario'=>$user_pass,
				'rol_usuario'=>'encuestado',
				'estatus_usuario'=>1,
				'ip_usuario'=>$user_ip
			));
			$mail = new Zend_Mail(); //Env�a notificaci�n de correo electr�nico cada vez que se registra un usuario nuevo
			$mail->setBodyText('El usuario con el correo electr�nico: '. $this->getRequest()->getParam('email_usuario') . ' se ha registrado.')
				->setFrom($this->getRequest()->getParam('email_usuario'), 'Usuario nuevo')
				->addTo('editor@huellaecologica.com.ve', 'Huella Ecol�gica')
				->setSubject('Nuevo usuario registrado')
				->send();
			$redirect = '/index/login/correo/'.$this->getRequest()->getParam('email_usuario');
			$this->_helper->redirector->gotoUrlAndExit($redirect);
			}
			//Si el email ya se encuentra registrado
			$redirectError = '/index/registro/duplicado/'.$this->getRequest()->getParam('email_usuario');
			$this->_helper->redirector->gotoUrlAndExit($redirectError);
		}
	}
	
	//Acciones del inicio de sesi�n
	public function loginAction()
    {
		$this->view->sidebar4 = Model_Informacion::getInstance()->getInformacion('sidebar4');
		//Definiendo el url al que se va a direccionar el usuario una vez que haya hecho inicio de sei�n exitosamente
		$redirect = $this->getRequest()->getParam('redirect');
		if(!isset($redirect))
		{
			$redirect = '/usuario/perfil';
		}
		$this->view->redirect = $redirect;
		
		// Averiguar si existe una sesi�n para no mostrar el formulario de login
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}//else: todo lo que sigue	
		
		if($this->getRequest()->getParam('correo'))
		{
			$this->view->correo = $this->getRequest()->getParam('correo');
			$this->view->mensajes = '�Excelente! Te has registrado correctamente. Ahora ingresa tu contrase�a para iniciar sesi�n.';
		}
		//si no se envi� un formulario por POST, se muestra el formulario de login sin tratar de autenticar
		if(!$this->getRequest()->getPost('botonInicioSesion'))
		{
			return;
		}
		$db = Zend_Db_Table::getDefaultAdapter();
		//formulario por POST tratando de autenticarlo
		$adapter = new Zend_Auth_Adapter_DbTable(
			$db,
			'tbl_usuario',
			'email_usuario',
			'pass_usuario');
		$adapter->setIdentity($this->getRequest()->getPost(strtolower('email_usuario')));
		$adapter->setCredential(sha1($this->getRequest()->getPost('pass_usuario')));
		$resultado = Zend_Auth::getInstance()->authenticate($adapter);
		
		//si se autentica exitosamente, se env�a al usuario a donde necesita ir
		if($resultado->isValid())
		{
			$usuario = new Zend_Session_Namespace('usuario');
			$select = $db->select()->from('tbl_usuario','rol_usuario')->where('email_usuario = ?',$resultado->getIdentity());
			$usuario->rol = $db->fetchOne($select);
		
			$this->_helper->redirector->gotoUrlAndExit($redirect);
		}
		//si el usuario no se autentica, se muestran los mensajes de error de inicio de sesi�n
		$this->view->mensajesError = $resultado->getMessages();
	}//Fin de login de usuario
	
	
	
	//Acci�n de cerrar la sesi�n de usuario
	public function cerrarSesionAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector->gotoSimpleAndExit('hasta-luego');
	}
	
	//P�gina de despedida luego de cerrar sesi�n
	public function hastaLuegoAction()
	{
		$this->view->sidebar4 = Model_Informacion::getInstance()->getInformacion('sidebar4');
	}
	
	public function informacionAction()
	{
		$this->view->sidebar5 = Model_Informacion::getInstance()->getInformacion('sidebar5');
		$this->view->autores = Model_Informacion::getInstance()->getInformacion('autores');
	}
	
	public function consejosAction()
	{
		$this->view->grupos = Model_Grupo::getInstance()->getGrupos();
		$this->view->consejos = Model_Consejo::getInstance()->getConsejos();
	}
	
	public function contactoAction()
	{
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		
		if(!isset($redirect))
		{
			$redirect = '/index/contacto';
		}
		
		if($this->getRequest()->getPost('botonEnviarContacto'))
		{
			$flashMessenger->addMessage('�Bien! tu mensaje se ha enviado correctamente.');
			$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
			
			$ip = $this->getRequest()->getServer('REMOTE_ADDR');
			$nombre = $this->getRequest()->getParam('nombre_usuario');
			$correo = $this->getRequest()->getParam('email_usuario');
			$ciudad = $this->getRequest()->getParam('ciudad_usuario');
			$mensaje= $this->getRequest()->getParam('mensaje_contacto');
			
			
			$mail = new Zend_Mail(); //Env�a notificaci�n de correo electr�nico cada vez que se registra un usuario nuevo
			$mail->setBodyText('Ciudad: '. $ciudad . 
								' Mensaje: ' . $mensaje . 
								' IP: '. $ip)
				->setFrom($correo, $nombre)
				->addTo('editor@huellaecologica.com.ve', 'Huella Ecol�gica')
				->setSubject('Mensaje del formulario de contacto de HuellaEcologica.com.ve')
				->send();

		}
	}
}
