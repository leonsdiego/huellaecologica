<?php

class EditorController extends Controller_Abstract
{

    public function init()
    {
        /* Initialize action controller here */
		//Agrego las operaciones del init de Controller/Abstract y agrego el layout-admin
		parent::init();
		$this->_helper->_layout->setLayout('layout-admin');
    }
	
	public function indexAction()
	{
		//Acciones
		$this->view->cantidadUsuarios = Model_Usuario::getInstance()->cantidadUsuarios('encuestado');
		$this->view->cantidadVisitantes = Model_Usuario::getInstance()->cantidadUsuarios('visitante');
		$this->view->cantidadEditores = Model_Usuario::getInstance()->cantidadUsuarios('editor');
		$this->view->cuestionarios = Model_Cuestionario::getInstance()->contarCuestionarios();
	}

	/*
	*
	*	LISTAR USUARIOS
	*
	*/
	
    public function listarAction()
    {
		$rol      = $this->getRequest()->getParam('tipo');
		$pagina   = max(1, (int) $this->getRequest()->getParam('pagina', 1));
		$porPagina = 20;
		$offset   = ($pagina - 1) * $porPagina;

		$db = Zend_Db_Table::getDefaultAdapter();

		$total = (int) $db->fetchOne(
			$db->select()->from('tbl_usuario', 'COUNT(id_usuario)')->where('rol_usuario = ?', $rol)
		);

		$this->view->usuarios = $db->fetchAll(
			$db->select()
				->from(array('u' => 'tbl_usuario'))
				->joinLeft(
					array('c' => 'tbl_cuestionario'),
					'c.tbl_usuario_id_usuario = u.id_usuario',
					array('cuestionarios' => 'COUNT(c.id_cuestionario)')
				)
				->where('u.rol_usuario = ?', $rol)
				->group('u.id_usuario')
				->limit($porPagina, $offset)
		);

		$this->view->titulo       = 'Usuarios de tipo: "'.$rol.'"';
		$this->view->rol          = $rol;
		$this->view->pagina       = $pagina;
		$this->view->totalPaginas = (int) ceil($total / $porPagina);
		$this->view->total        = $total;
	}
	
	/*
	*
	*	PERFIL DE USUARIO PARA GESTIPON DE ROLES
	*
	*/
	
	public function perfilAction()
    {
		//Inicializar variable de Mensajes
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		
		$id = $this->getRequest()->getParam('id');
		if($this->getRequest()->getPost('botonEditarUsuario'))
		{
			$rol = Model_Usuario::getRolUsuario($this->getRequest()->getParam('id_usuario'));
			//echo '<pre>' . $rol['rol_usuario'] . '</pre>';
			if($rol['rol_usuario'] == 'encuestado')
			{
				Model_Usuario::getInstance()->updateUsuario(array(
					'id_usuario'=>$this->getRequest()->getParam('id_usuario'),
					'rol_usuario'=>$this->getRequest()->getParam('rol_usuario')
				));
				$flashMessenger->addMessage('�Bien! el perfil se actualiz� correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
			}
			else
			{
				$correo = Model_Usuario::getCorreoUsuario($this->getRequest()->getParam('id_usuario'));
				if(Model_Usuario::ultimoEditor() == false && $correo['email_usuario'] != 'editor@huellaecologica.com.ve' )
				{
					Model_Usuario::getInstance()->updateUsuario(array(
						'id_usuario'=>$this->getRequest()->getParam('id_usuario'),
						'rol_usuario'=>$this->getRequest()->getParam('rol_usuario')
					));
					$flashMessenger->addMessage('�Bien! el perfil se actualiz� correctamente');
					$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
				}
				else
				{
					$flashMessenger->addMessage('No se puede editar el rol de este usuario');
					$this->view->mensajesError = $this->_helper->flashMessenger->getCurrentMessages();
				}
			}
		}
		$this->view->cantidadCuestionarios = Model_Cuestionario::getInstance()->contarCuestionariosByUsuario($id);
		$this->view->perfil = Model_Usuario::getInstance()->getUsuarioById($id);//Devulve la informacion del uduario con el ID dado
	}
	
	/*
	*
	*	ESTAD�STICAS
	*
	*/
	
	public function estadisticasAction()
	{
		$this->view->indicadoresGrupos = Model_Indicador::getInstance()->contarIndicadoresByGrupo();
			$this->view->usuariosSexo = Model_Usuario::getInstance()->contarUsuariosByGenero();
	}
	
	public function estadisticasUsuariosAction()
	{
		
		$this->view->usuariosInstruccion = Model_Usuario::getInstance()->contarUsuariosByInstruccion();
		$this->view->estadosUsuarios = Model_Usuario::getInstance()->contarUsuariosByEstado();
		$this->view->usuariosSexo = Model_Usuario::getInstance()->contarUsuariosByGenero();
		$this->view->registrosMes = Model_Usuario::getInstance()->contarRegistrosByMes();

	}
	
	public function estadisticasCuestionariosAction()
	{
		$this->view->cuestionarios = Model_Cuestionario::getInstance()->contarCuestionarios();
		$this->view->cuestionariosByMes = Model_Cuestionario::getInstance()->contarCuestionariosByMes();
	}
	
	/*
	*
	*	ENCUESTA
	*
	*/
	
	public function encuestasAction()
	{
		
	}
	
	/*
	*
	*	CUESTIONARIO
	*
	*/
	
	public function cuestionarioAction()
	{
		$this->view->grupos = Model_Grupo::getGrupos();
		$this->view->indicadores = Model_Indicador::getIndicadores();
		$this->view->alternativas = Model_Alternativa::getAlternativas();
	}
	
	/*
	*
	*	GRUPOS
	*
	*/
	
	public function grupoAction()
	{
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		
		
		$id = $this->getRequest()->getParam('id');
		if(isset($id))
		{
			$this->view->boton = array(
									'nombre'=>'botonActualizarGrupo',
									'valor'=>'Actualizar'
									);
		}
		else
		{
			$this->view->boton = array(
									'nombre'=>'botonInsertarGrupo',
									'valor'=>'Insertar'
									);
		}
		
		if($this->getRequest()->getPost('botonInsertarGrupo'))
		{
			$ficha = $this->getRequest()->getParam('ficha_grupo');
			if( Model_Grupo::fichaExiste($ficha) == false )
			{
				Model_Grupo::getInstance()->insertGrupo(array(
					'ficha_grupo'=>$this->getRequest()->getParam('ficha_grupo'),
					'orden_grupo'=>$this->getRequest()->getParam('orden_grupo'),
					'nombre_grupo'=>$this->getRequest()->getParam('nombre_grupo')				
				));
				$flashMessenger->addMessage('�Bien! se a�adi� el grupo correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
				
			}
			else
			{
			$flashMessenger->addMessage('La ficha que est�s intentando ingresar ya existe, por favor prueba con otra');
			$this->view->mensajesError = $this->_helper->flashMessenger->getCurrentMessages();
			}
		}
		
		if($this->getRequest()->getPost('botonActualizarGrupo'))
		{
			Model_Grupo::getInstance()->updateGrupo(array(
					'id_grupo'=>$this->getRequest()->getParam('id_grupo'),
					'orden_grupo'=>$this->getRequest()->getParam('orden_grupo'),
					'nombre_grupo'=>$this->getRequest()->getParam('nombre_grupo')				
				));
				$flashMessenger->addMessage('�Bien! se actualiz� el grupo correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
		}
		
		if(isset($id))//Verifica nuevamente que la variable ID pas� como par�metro
		{
			$this->view->grupo = Model_Grupo::getInstance()->getGrupoById($id); //Llena el formulario de actualizaci�n del grupo
		}
		$this->view->grupos = Model_Grupo::getInstance()->getGrupos(); //Llena la lista de grupos
	}
	
	
	/*
	*
	*	INDICADORES
	*
	*/
	
	public function indicadorAction()
	{
		//Inicializar variable de Mensajes
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
				
		$id = $this->getRequest()->getParam('id');
		if(isset($id)) //Verifica que se pas� el ID del indicador como par�metro
		{
			/* Se crea el arreglo para dar valor al bot�n del formulario*/
			$this->view->boton = array(
									'nombre'=>'botonActualizarIndicador',
									'valor'=>'Actualizar'
									);
		}
		else
		{
			$this->view->boton = array(
									'nombre'=>'botonInsertarIndicador',
									'valor'=>'Insertar'
									);
		}

		if($this->getRequest()->getPost('botonInsertarIndicador'))
		{
			$ficha = $this->getRequest()->getParam('ficha_indicador');
			if( Model_Indicador::fichaExiste($ficha) == false )
			{
				$idFactorConversion = Model_FactorConversion::getInstance()->insertFactorConversion(array(
					'valor_factorConversion'=> doubleval($this->getRequest()->getParam('factor_indicador')),
					'ficha_factorConversion'=> $this->getRequest()->getParam('ficha_indicador')));
					
				Model_Indicador::getInstance()->insertIndicador(array(
					'ficha_indicador'=>$this->getRequest()->getParam('ficha_indicador'),
					'nombre_indicador'=>$this->getRequest()->getParam('nombre_indicador'),
					'descripcion_indicador'=>$this->getRequest()->getParam('descripcion_indicador'),
					'tbl_factorConversion_id_factorConversion'=>(int) $this->getRequest()->getParam('factor_indicador'),
					'tbl_grupo_id_grupo'=>$this->getRequest()->getParam('id_grupo'),
					'tbl_indicador_id_indicador_anterior'=>$this->getRequest()->getParam('tbl_indicador_id_indicador_anterior'),
					'cuenta_indicador'=>$this->getRequest()->getParam('cuenta_indicador'),
					'orden_indicador'=>$this->getRequest()->getParam('orden_indicador'),
					'formula_indicador'=>$this->getRequest()->getParam('formula_indicador'),
					'ayuda_indicador'=>$this->getRequest()->getParam('ayuda_indicador')
				));
				$flashMessenger->addMessage('�Bien! se a�adi� el indicador correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
			}
			else
			{
			$flashMessenger->addMessage('La ficha que est�s intentando ingresar ya existe, por favor prueba con otra');
			$this->view->mensajesError = $this->_helper->flashMessenger->getCurrentMessages();
			}
		}
		
		if($this->getRequest()->getPost('botonActualizarIndicador'))
		{
				$ficha = $this->getRequest()->getParam('ficha_indicador');
				$FactorConversion = Model_FactorConversion::getInstance()->getIdFactorConversionByFicha($ficha);
				if( null != $FactorConversion )
				{
					$idFactorConversion = $FactorConversion['id_factorConversion'];
					Model_FactorConversion::getInstance()->updateFactorConversion(array(
					'valor_factorConversion'=> doubleval($this->getRequest()->getParam('factor_indicador')),
					'ficha_factorConversion'=> $this->getRequest()->getParam('ficha_indicador'),
					'id_factorConversion'=>$idFactorConversion));
					
				}
				else
				{
					$idFactorConversion = Model_FactorConversion::getInstance()->insertFactorConversion(array(
						'valor_factorConversion'=> doubleval($this->getRequest()->getParam('factor_indicador')),
						'ficha_factorConversion'=> $this->getRequest()->getParam('ficha_indicador')));
				}
				
				Model_Indicador::getInstance()->updateIndicador(array(
					'id_indicador'=>$this->getRequest()->getParam('id_indicador'),
					'ficha_indicador'=>$this->getRequest()->getParam('ficha_indicador'),
					'nombre_indicador'=>$this->getRequest()->getParam('nombre_indicador'),
					'descripcion_indicador'=>$this->getRequest()->getParam('descripcion_indicador'),
					'tbl_factorConversion_id_factorConversion'=>$idFactorConversion,
					'tbl_grupo_id_grupo'=>$this->getRequest()->getParam('id_grupo'),
					'tbl_indicador_id_indicador_anterior'=>$this->getRequest()->getParam('tbl_indicador_id_indicador_anterior'),
					'cuenta_indicador'=>$this->getRequest()->getParam('cuenta_indicador'),
					'orden_indicador'=>$this->getRequest()->getParam('orden_indicador'),
					'formula_indicador'=>$this->getRequest()->getParam('formula_indicador'),
					'ayuda_indicador'=>$this->getRequest()->getParam('ayuda_indicador')
				));
				//Asociar indicador a una encuesta
				Model_EncuestaIndicador::getInstance()->updateEncuestaIndicador($this->getRequest()->getParam('id_encuesta'),$this->getRequest()->getParam('id_indicador'));
				$flashMessenger->addMessage('�Bien! se actualiz� el indicador correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
		}
		
		if(isset($id))//Verifica nuevamente que la variable ID pas� como par�metro
		{
			$this->view->encuestaIndicador = Model_EncuestaIndicador::getInstance()->getEncuestaByIndicador($id);
			$this->view->encuestas = Model_Encuesta::getInstance()->getEncuestas();
			$this->view->indicador = Model_Indicador::getInstance()->getIndicador($id); //Llena el formulario si se pasa el ID como par�metro
			$this->view->alternativas = Model_Alternativa::getInstance()->getAlternativasByIndicador($id);
		}
		$this->view->factores = Model_FactorConversion::getInstance()->getFactoresDeConversion();
		$this->view->grupos = Model_Grupo::getGrupos();
		$this->view->indicadoresAnteriores = Model_Indicador::getIndicadores();
		$this->view->indicadores = Model_Indicador::getInstance()->getIndicadores(); //Llena la lista de Indicadores
		
	}
	
	/*
	*
	*	FACTORES
	*
	*/
	
	public function factorAction()
	{
		//Inicializar variable de Mensajes
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		$this->view->indicadores = Model_Indicador::getInstance()->getIndicadores();
		
		$id = $this->getRequest()->getParam('id');
		
		
		if($this->getRequest()->getPost('botonActualizarFactor'))
		{
			Model_FactorConversion::getInstance()->updateFactorConversion(array(
				'id_factorConversion'=>$this->getRequest()->getParam('id_factor'),
				'valor_factorConversion'=> doubleval($this->getRequest()->getParam('valor_factor'))
			));
			$flashMessenger->addMessage('�Bien! se actualiz� el factor de conversi�n');
			$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
		}
		
		if(isset($id))//Verifica nuevamente que la variable ID pas� como par�metro
		{
			//Llena el formulario si se pasa el ID como par�metro
			$this->view->factorConversion = Model_FactorConversion::getInstance()->getFactorConversion($id); 
		}
		$this->view->factores = Model_FactorConversion::getInstance()->getFactoresDeConversion(); //LLenar lista de factores de conversi�n
	}
	
	/*
	*
	*	CONSEJOS
	*
	*/
	
	public function consejoAction()
	{
		//Inicializar variable de Mensajes
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		$this->view->grupos = Model_Grupo::getGrupos();
		
		$id = $this->getRequest()->getParam('id');
		if(isset($id)) 
		{
			/* Se crea el arreglo para dar valor al bot�n del formulario*/
			$this->view->boton = array(
									'nombre'=>'botonActualizarConsejo',
									'valor'=>'Actualizar'
									);
		}
		else
		{
			$this->view->boton = array(
									'nombre'=>'botonInsertarConsejo',
									'valor'=>'Insertar'
									);
		}
		
		if($this->getRequest()->getPost('botonInsertarConsejo'))
		{
			$ficha = $this->getRequest()->getParam('ficha_consejo');
			if( Model_Consejo::fichaExiste($ficha) == false )
			{
				
				Model_Consejo::getInstance()->insertConsejo(array(
					'ficha_consejo'=>$this->getRequest()->getParam('ficha_consejo'),
					'nombre_consejo'=>$this->getRequest()->getParam('nombre_consejo'),
					'descripcion_consejo'=>$this->getRequest()->getParam('descripcion_consejo'),
					'tbl_grupo_id_grupo'=>$this->getRequest()->getParam('grupo_consejo')
				));
				$flashMessenger->addMessage('�Bien! se a�adi� el consejo correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
			}
			else
			{
			$flashMessenger->addMessage('La ficha que est�s intentando ingresar ya existe, por favor prueba con otra');
			$this->view->mensajesError = $this->_helper->flashMessenger->getCurrentMessages();
			}
		}
		
		if($this->getRequest()->getPost('botonActualizarConsejo'))
		{
			Model_Consejo::getInstance()->updateConsejo(array(
				'id_consejo'=>$this->getRequest()->getParam('id_consejo'),
				'ficha_consejo'=>$this->getRequest()->getParam('ficha_consejo'),
				'nombre_consejo'=>$this->getRequest()->getParam('nombre_consejo'),
				'descripcion_consejo'=>$this->getRequest()->getParam('descripcion_consejo'),
				'tbl_grupo_id_grupo'=>$this->getRequest()->getParam('grupo_consejo')
			));
			$flashMessenger->addMessage('�Bien! se actualiz� el consejo correctamente');
			$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
		}
		
		if(isset($id))//Verifica nuevamente que la variable ID pas� como par�metro
		{
			//Llena el formulario si se pasa el ID como par�metro
			$this->view->consejo = Model_Consejo::getInstance()->getConsejo($id); 
		}
		$this->view->consejos = Model_Consejo::getInstance()->getConsejos();
		$this->view->alternativas = Model_Alternativa::getInstance()->getAlternativas();
	}
	
	/*
	*
	*	ALTERNATIVA
	*
	*/
	
	public function alternativaAction()
	{
		//Inicializar variable de Mensajes
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		
		$this->view->indicadores = Model_Indicador::getIndicadores();
		$this->view->consejos = Model_Consejo::getConsejos();
		
		$id = $this->getRequest()->getParam('id');
		if(isset($id))
		{
			/* Se crea el arreglo para dar valor al bot�n del formulario*/
			$this->view->boton = array(
									'nombre'=>'botonActualizarAlternativa',
									'valor'=>'Actualizar'
									);
		}
		else
		{
			$this->view->boton = array(
									'nombre'=>'botonInsertarAlternativa',
									'valor'=>'Insertar'
									);
		}
		
		//Recoge los datos del indicador pasado como par�metro
		$indicador = $this->getRequest()->getParam('indicador');
		
		if($this->getRequest()->getPost('botonInsertarAlternativa'))
		{
			$ficha = $this->getRequest()->getParam('ficha_alternativa');
			if( Model_Alternativa::fichaExiste($ficha) == false )
			{
				Model_Alternativa::getInstance()->insertAlternativa(array(
					'ficha_alternativa'=>$this->getRequest()->getParam('ficha_alternativa'),
					'descripcion_alternativa'=>$this->getRequest()->getParam('descripcion_alternativa'),
					'peso_alternativa'=> doubleval($this->getRequest()->getParam('peso_alternativa')),
					'unidad_alternativa'=>$this->getRequest()->getParam('unidad_alternativa'),
					'tbl_indicador_id_indicador'=>$this->getRequest()->getParam('id_indicador'),
					'tbl_indicador_id_indicador_siguiente'=>$this->getRequest()->getParam('id_indicador_siguiente'),
					'tbl_consejo_id_consejo'=>$this->getRequest()->getParam('consejo_alternativa'),
					'ayuda_alternativa'=>$this->getRequest()->getParam('ayuda_alternativa'),
					'orden_alternativa'=>$this->getRequest()->getParam('orden_alternativa')
				));
				$flashMessenger->addMessage('�Bien! se a�adi� la alternativa correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
			}
			else
			{
			$flashMessenger->addMessage('La ficha que est�s intentando ingresar ya existe, por favor prueba con otra');
			$this->view->mensajesError = $this->_helper->flashMessenger->getCurrentMessages();
			}
		}
		
		if($this->getRequest()->getPost('botonActualizarAlternativa'))
		{
				Model_Alternativa::getInstance()->updateAlternativa(array(
					'id_alternativa'=>$this->getRequest()->getParam('id_alternativa'),				
					'ficha_alternativa'=>$this->getRequest()->getParam('ficha_alternativa'),
					'descripcion_alternativa'=>$this->getRequest()->getParam('descripcion_alternativa'),
					'peso_alternativa'=> doubleval($this->getRequest()->getParam('peso_alternativa')),
					'unidad_alternativa'=>$this->getRequest()->getParam('unidad_alternativa'),
					'tbl_indicador_id_indicador'=>$this->getRequest()->getParam('id_indicador'),
					'tbl_indicador_id_indicador_siguiente'=>$this->getRequest()->getParam('id_indicador_siguiente'),
					'tbl_consejo_id_consejo'=>$this->getRequest()->getParam('consejo_alternativa'),
					'ayuda_alternativa'=>$this->getRequest()->getParam('ayuda_alternativa'),
					'orden_alternativa'=>$this->getRequest()->getParam('orden_alternativa')
				));
				$flashMessenger->addMessage('�Bien! se actualiz� la alternativa correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
		}
		
		if(isset($id))//Verifica nuevamente que la variable ID pas� como par�metro
		{
			$this->view->alternativa = Model_Alternativa::getInstance()->getAlternativa($id); 
		}
		//Llena el formulario si se pasa el ID como par�metro
		if(isset($indicador))
		{
			/* Se le da un valor al campo del indicador en el formulario*/
			$this->view->indicadorAlternativa = $indicador;
			$this->view->alternativas = Model_Alternativa::getInstance()->getAlternativasByIndicador($indicador);
		}
		else
		{
			$this->view->alternativas = Model_Alternativa::getAlternativasOrdenIndicador();
		}
	}
	
	public function sitioAction()
	{
		
	}
	
	public function informacionAction()
	{
		$this->view->informacion = Model_Informacion::listarInformacion();
	}
	
	public function nuevaInformacionAction()
	{
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');

		
		if($this->getRequest()->getPost('botonInsertarInfo'))
		{
				if(Model_Informacion::fichaExiste($this->getRequest()->getParam('ficha_informacion')) == false)
				{
					Model_Informacion::getInstance()->insertInformacion(array(
						'ficha_informacion'=>$this->getRequest()->getParam('ficha_informacion'),
						'titulo_informacion'=>$this->getRequest()->getParam('titulo_informacion'),
						'ubicacion_informacion'=>$this->getRequest()->getParam('ubicacion_informacion'),
						'contenido_informacion'=>$this->getRequest()->getParam('contenido_informacion')
					));
					$flashMessenger->addMessage('�Bien! la informaci�n se actualiz� correctamente');
					$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
				}
				else
				{
				$flashMessenger->addMessage('La ficha que est�s intentando ingresar ya existe, por favor prueba con otra. La informaci�n NO se agreg� a la base de datos.');
				$this->view->mensajesError = $this->_helper->flashMessenger->getCurrentMessages();
				$this->view->contenidoSalvado = $this->getRequest()->getParam('contenido_informacion');
				}
		}
	}
	
	public function infoAction()
	{
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->addMessage('');
		
		$id = $this->getRequest()->getParam('id');
		//echo '<pre>' . $rol['rol_usuario'] . '</pre>';
		if($this->getRequest()->getPost('botonActualizarInfo'))
		{
				Model_Informacion::getInstance()->updateInformacion(array(
					'id_informacion'=>$this->getRequest()->getParam('id_informacion'),
					'ficha_informacion'=>$this->getRequest()->getParam('ficha_informacion'),
					'ubicacion_informacion'=>$this->getRequest()->getParam('ubicacion_informacion'),
					'titulo_informacion'=>$this->getRequest()->getParam('titulo_informacion'),
					'contenido_informacion'=>$this->getRequest()->getParam('contenido_informacion')
				));
				$flashMessenger->addMessage('�Bien! la informaci�n se actualiz� correctamente');
				$this->view->mensajes = $this->_helper->flashMessenger->getCurrentMessages();
		}
		$this->view->informacion = Model_Informacion::getInstance()->getInformacionById($id);
	}
}