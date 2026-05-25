<?php
	$acl = new Zend_Acl();
	
	$acl->addResource('error');
	$acl->addResource('index');
	$acl->addResource('usuario');
	$acl->addResource('editor');
	$acl->addResource('cuestionario');
	$acl->addRole('encuestado'); //Rol encuestado
	$acl->addRole('visitante','encuestado'); //Rol visitante, hereda los derechos del encuestado
	$acl->addRole('editor','encuestado'); //editor hereda los derechos de encuestado

	
	$acl->allow(null,'index'); //Zonas libres
	$acl->allow(null,'error'); //Zonas libres
	$acl->allow('editor','editor'); //Zona de usuarios donde solo accede el editor
	$acl->allow('visitante','editor','index');//Zonas donde puede acceder el visitante
	$acl->allow('visitante','editor','cuestionario');
	$acl->allow('visitante','editor','estadisticas');
	$acl->allow('visitante','editor','estadisticas-usuarios');
	$acl->allow('visitante','editor','estadisticas-cuestionarios');
	$acl->allow('encuestado','usuario'); //Zona de usuarios donde solo accede el editor
	$acl->allow('encuestado','cuestionario'); //Accion de rsponder el cuestionario donde acceden encuestado y editor