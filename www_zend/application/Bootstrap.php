<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function run(){
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		$view->doctype('XHTML1_TRANSITIONAL');
		
		// Agregamos los helperPaths adicionales
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$view->jQuery()->setVersion('1.7.1');
        $view->jQuery()->setUiVersion('1.8.11');
		
		Zend_Layout::startMvc(array('pluginClass'=>'View_Layout'));
		
		require APPLICATION_PATH . '/configs/acl.php';
		Zend_Registry::getInstance()->set('acl',$acl);
		$front = $this->getResource('FrontController');
		$front->setParam('bootstrap', $this);
		$front->dispatch();
	}
	protected function _initView()
    {
        $view = new Zend_View();
        return $view;
    }
}

