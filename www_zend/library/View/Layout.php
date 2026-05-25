<?php
class View_Layout extends Zend_Layout_Controller_Plugin_Layout
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->getLayout()->setLayoutPath(
            APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts'
        );
    }
     
}