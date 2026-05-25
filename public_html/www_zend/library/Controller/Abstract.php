<?php

class Controller_Abstract extends Zend_Controller_Action
{

    public function init()
    {
		$acl = Zend_Registry::getInstance()->get('acl');
		$action = $this->getRequest()->getActionName();
		$controller = $this->getRequest()->getControllerName();
		if($acl->isAllowed(null,$controller,$action)){
			return true;
		}
		//Si el usuario no está autenticado, redireccionarlo al login
		if(!Zend_Auth::getInstance()->hasIdentity()){
			$actionParams = $this->getRequest()->getParams();

			unset($actionParams['module']);
			unset($actionParams['controller']);
			unset($actionParams['action']);
			
			// Encapsulamos los parametros del request original por encoding del url y redireccionamos
			// Nota: se quita el slash al final para evitar que se el url se interprete de forma inadecuada
			$redirect = $this->_helper->url($action, $controller, 'default', $actionParams);  
			$redirect = rtrim($redirect, '/');
			$this->_helper->redirector->gotoSimpleAndExit('login', 'index', 'default', array('redirect' => $redirect));
		}
		//Si el usuario tiene identidad (hizo sesión), se verifica su rol
		$usuario = new Zend_Session_Namespace('usuario');
		if($acl->isAllowed($usuario->rol,$controller,$action)){
			return true;
		}//else:lo de abajo. Cuando el usuario registrado y habiendo inciado sesión intententa ingresar
		// a zona donde no tiene permiso
		$this->_helper->redirector->gotoSimpleAndExit('permiso', 'error');
    }
}

