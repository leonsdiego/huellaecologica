<?php 

class Model_CuestionarioAlternativa
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
	
	public function insertRespuesta($idCuestionario,$idAlternativa,$resultado,$idIndicador,$grupo,$idConsejo,$cuenta)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->insertRespuesta($idCuestionario,$idAlternativa,doubleval($resultado),$idIndicador,$grupo,$idConsejo,$cuenta);
	}
	
	
	public function contarAlternativas($idCuestionario)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->contarAlternativas($idCuestionario);
	}
	
	public function getResultadosByCuestionario($idCuestionario)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getResultadosByCuestionario($idCuestionario);
	}

	public function getAlternativa($idCuestionario,$idIndicador)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getAlternativa($idCuestionario,$idIndicador);
	}

	public function getUltimaAlternativa()
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getUltimaAlternativa();
	}
	
	public function getCuestionario($idCuestionario)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getCuestionario($idCuestionario);
	}
	
	public function resultadoExiste($idCuestionario,$idIndicador)
	{
		if ( 0 != Model_Mapper_CuestionarioAlternativa::getInstance()->resultadoExiste($idCuestionario,$idIndicador) )
		{
			return true;
		}
		return false;
	}
	
	public function getCuestionarioByAlternativa($idAlternativa)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getCuestionarioByAlternativa($idAlternativa);
	}
	
	public function getAlternativaMayorOrden($idCuestionario,$alternativasMayorOrden)
	{
		foreach( $alternativasMayorOrden as $alternativa )
		{
			if ( $idCuestionario == $this->getCuestionarioByAlternativa($alternativa['tbl_alternativa_id_alternativa']) )
			{
				return $alternativa['tbl_alternativa_id_alternativa'];
			}
		}
	}
	
	public function getConsejosByCuestionario($idCuestionario)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getConsejosByCuestionario($idCuestionario);
	}
	
	public function getAlternativasByCuestionario($idCuestionario)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getAlternativasByCuestionario($idCuestionario);
	}
	
	public function getSiguiente($idCuestionario)
	{
		$alternativasMayorOrden = $this->getUltimaAlternativa();
		$idUltimaAlternativa = $this->getAlternativaMayorOrden($idCuestionario,$alternativasMayorOrden);
		$indicadorActual = Model_Alternativa::getInstance()->getIndicador($idUltimaAlternativa);
		return Model_Indicador::getInstance()->getSiguiente($indicadorActual['id_indicador']);
	}
	
	public function getAlternativaByOrden($orden)
	{
		return Model_Mapper_CuestionarioAlternativa::getInstance()->getAlternativaByOrden($orden);
	}
	
	/*
	*
	*	FÓRMULAS
	*
	*/
	
	
	public function getResuladoAlternativaCuestionarioByFichaIndicador($ficha,$idCuestionario)
	{
		$indicador = Model_Indicador::getInstance()->getIndicadorByFicha($ficha);
		$resultado = doubleval(Model_Mapper_CuestionarioAlternativa::getInstance()->getResultadoByIndicadorCuestionario($idCuestionario,$indicador['id_indicador']));
		return doubleval($resultado);
	}
	
	public function calcularResultado($peso,$factor,$formula,$fichaIndicador,$idCuestionario)
	{	
		switch ($formula) {
		case 0:
			/* INDICADORES QUE CONDICIONAN A LOS SIGUIENTES */
			return 0;
        break;
		case 1:
			/*INDICADORES CUYO CÁLCULO ES PESO * FACTOR */
			$resultado = doubleval($peso)*doubleval($factor);
			return doubleval($resultado);
        break;
		case 2:
			/*INDICADORES DIVIDIDOS ENTRE EL NÚMERO DE HABITANTES DE LA VIVIENDA */
			$habitantes = doubleval(Model_Indicador::getInstance()->getCantidadHabitantes($idCuestionario));
			//Redondea el resultado en base a 4 decimales
			$resultado = round((doubleval($peso)*doubleval($factor))/doubleval($habitantes),4);
			return doubleval($resultado);
        break;
		case 3:
			/*INDICADORES CUYO CÁLCULO ES COMPLEJO */
			/* if( $fichaIndicador == 'actipo' )
			{
				$resultado = $this->getResultadoACtipo($peso,$factor,$fichaIndicador,$idCuestionario);
			} */
			if( $fichaIndicador == 'acuso' )
			{
				$resultado = $this->getResultadoAC($peso,$factor,$idCuestionario);
			}
			elseif( $fichaIndicador == 'usocalefaccion' )
			{
				$resultado = $this->getResultadoCalefaccion($peso,$factor,$idCuestionario);
			}
			elseif( $fichaIndicador == 'secadoropa' )
			{
				$resultado = $this->getResultadoSecadora($peso,$factor,$idCuestionario);
			}
			return $resultado;
        break;}
	}
	
	
	/* 
	*	FORMULA DE AIRES ACONDICIONADOS 
	*/
	
	public function getResultadoACtipo($peso,$factor,$fichaIndicador,$idCuestionario)
	{
		$alternativa = $this->getAlternativa($fichaIndicador,$idCuestionario);
			if( 'accentral' == $alternativa['ficha_alternativa'] )
			{
				echo '<pre>Ficha alternativa entró a ACCENTRAL: '.$alternativa['ficha_alternativa'].'</pre>';
				//resultado = (peso*factor)/habitaciones
				$resultado = round(doubleval((doubleval($peso)*doubleval($factor))/doubleval(Model_indicador::getInstance()->getCantidadHabitaciones($idCuestionario))),4);
			}
			else
			{
				echo '<pre>Ficha alternativa entró a NOACCENTRAL: '.$alternativa['ficha_alternativa'].'</pre>';
				//resultado = (peso*factor)
				$resultado = doubleval(doubleval($peso)*doubleval($factor));
			}
		return doubleval($resultado);
	}
	
	public function getResultadoAC($peso,$factor,$idCuestionario)
	{
		$acTipo = doubleval($this->getResuladoAlternativaCuestionarioByFichaIndicador('actipo',$idCuestionario));
		//resultado = resultado(acuso)*resultado(actipo)
		$resultado = doubleval(doubleval($peso)*doubleval($factor)*doubleval($acTipo));
		return round(doubleval($resultado),4);
	}
	
	
	/* 
	*	FORMULA DE CALEFACCIÓN
	*/
	
	public function getResultadoCalefaccion($peso,$factor,$idCuestionario)
	{
		$calefaccionTipo = doubleval($this->getResuladoAlternativaCuestionarioByFichaIndicador('tipocalefaccion',$idCuestionario));
		$habitantes = doubleval(Model_Indicador::getInstance()->getCantidadHabitantes($idCuestionario));
		//resultado = resultado(tipocalefaccion)*resultado(usocalefaccion)/habitantes)
		$resultado = doubleval(doubleval($peso)*doubleval($factor)*doubleval($calefaccionTipo)/doubleval($habitantes));
		return round(doubleval($resultado),4);
	}
	
	/* 
	*	FORMULA DE SECADORA
	*/
	
	public function getResultadoSecadora($peso,$factor,$idCuestionario)
	{
		$uso = doubleval($this->getResuladoAlternativaCuestionarioByFichaIndicador('usolavadora',$idCuestionario));
		$habitantes = doubleval(Model_Indicador::getInstance()->getCantidadHabitantes($idCuestionario));
		//resultado = resultado(tipocalefaccion)*resultado(usocalefaccion)/habitantes)
		$resultado = doubleval(doubleval($peso)*doubleval($factor)*doubleval($uso)/doubleval($habitantes));
		return round(doubleval($resultado),4);
	}
	
	/*
	*
	*	RESULTADOS
	*
	*/
	
	public function getHuella($idCuestionario)
	{
		$resultados = Model_Mapper_CuestionarioAlternativa::getInstance()->getHuella($idCuestionario);
		
		$acum = 0;
		foreach ( $resultados as $resultado )
		{
			$acum += doubleval($resultado['resultado']);
		}
		return round(doubleval($acum),2);
	}
	
	public function getResultadosByGrupo($idCuestionario,$idGrupo)
	{
		$resultados = Model_Mapper_CuestionarioAlternativa::getInstance()->getResultadosByGrupo($idCuestionario,$idGrupo);
		
		$acum = 0;
		foreach ( $resultados as $resultado )
		{
			$acum += doubleval($resultado['resultado']);
		}
		return doubleval($acum);
	}
	
	public function getResultadosAcumGrupos($idCuestionario)
	{
		$grupos = Model_Grupo::getInstance()->getGrupos();
		$resultadoGrupo = array();
		foreach( $grupos as $grupo )
		{
			$resultadoGrupo[$grupo['nombre_grupo']] = doubleval($this->getResultadosByGrupo($idCuestionario,$grupo['id_grupo']));
		}
		return $resultadoGrupo;
	}
}