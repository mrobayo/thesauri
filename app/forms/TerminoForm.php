<?php
namespace Thesaurus\Forms;

use Phalcon\Db\RawValue;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Thesauri\ThThesaurus;
use Thesaurus\Thesauri\ThRelacion;

/**
 * Formulario de Termino
 *
 * @author mrobayo
 */
class TerminoForm extends BaseForm
{
	const TP_REL_EQ = 'TP',
		  TG_REL_EQ = 'TG',
		  SIN_REL_EQ = 'SIN';

	const RELATION_TYPES = ['TP' =>'Término Preferente',
							'TE' => 'Término Específico',
							'TG' => 'Término General',
							'TR' => 'Término Relacionado',
							'SIN' => 'Término Alternativo'];

	const CANDIDATO = 'CANDIDATO',
		  APROBADO  = 'APROBADO';

	const ESTADO_LIST = ['APROBADO'=>'APROBADO', 'CANDIDATO'=>'CANDIDATO', 'REEMPLAZADO'=>'REEMPLAZADO', 'DEPRECADO'=>'DEPRECADO'];

	/**
	 *
	 */
    public function initialize(ThTermino $entity = null, $options = ['edit'=>true])
    {
    	if (! isset($options['thesaurus_list'])) $options['thesaurus_list'] = [];
    	if (! isset($options['language_list'])) $options['language_list'] = [];

    	$this->add(new Hidden('id_termino'));

    	$this->addText('nombre', ['tooltip'=>'Término preferido', 'label'=>'Término', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);
    	$this->addTextArea('descripcion', ['label'=>'Definición', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);

    	$this->addText(self::TG_REL_EQ, ['tooltip'=>'Término más general', 'label'=>'Término General', 'filters'=>array('striptags', 'string'), 'validators'=>[] ]);
    	$this->addText(self::SIN_REL_EQ . '[]', ['label'=>'Sinónimos', 'filters'=>array('striptags', 'string'), 'validators'=>[] ]);

        $this->addSelect('id_thesaurus', ['label'=>'Thesaurus', 'options'=> $options['thesaurus_list'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
        $this->addSelect('iso25964_language', ['label'=>'Idioma', 'options'=> $options['language_list'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

        $this->addSelect('estado_termino', ['label'=>'Estado', 'options'=> SELF::ESTADO_LIST, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

        if ($this->isEditable($options)) {

        }
        else {
        	foreach($this->getElements() as &$e) {
        		$e->setAttribute("readonly", "readonly");
        	}
        }
    }

	/**
	 * Guardar relacion (sor patrocinio)
	 *
	 * @param ThTermino $entidad
	 * @param string $termino_rel
	 * @param string $tipo_relacion
	 */
    public function guardarRelacion($entidad, $termino_rel, $tipo_relacion) {
    	$auth = $this->session->get('auth');
    	// $this->logger->error('Guardar Relacion: '. $entidad->id_termino . ' ' . $termino_rel . ' - ' . $tipo_relacion);

    	$rel_notilde = \StringHelper::notilde($termino_rel);
    	$entidad_rel = ThTermino::findFirst([
    			'notilde=?1 AND id_thesaurus=?2', 'bind'=> [1=>$rel_notilde, 2=>$entidad->id_thesaurus] ]);

    	if (! $entidad_rel)
    	{
    		$this->guardarTermino($termino_rel, NULL, $entidad->iso25964_language, $entidad->id_thesaurus);
    	}
    	else
    	{
    		// Verificar si la relacion ya existe
    		$rel = ThRelacion::findFirst([
    			'id_termino = ?1 AND id_termino_rel = ?2 AND tipo_relacion = ?3',
    			'bind' => [1=> $entidad->id_termino, 2=>$entidad_rel->id_termino, 3=> $tipo_relacion] ]);
    		if ($rel) return $rel;
    	}

    	// Guardar nueva relacion
		$rel = new ThRelacion();

		$rel->tipo_relacion = $tipo_relacion;
		$rel->id_termino = $entidad->id_termino;
		$rel->id_thesaurus = $entidad->id_thesaurus;
		$rel->id_termino_rel = $entidad_rel->id_termino;

		if ($rel->save() == false) {
			return false; // Fallo al guardar
		}
		return $rel;
    }


    /**
     * ThTermino
     *
     * @param ThTermino $entidad
     * @return boolean
     */
    private function bind_post($entidad) {
    	$entidad->nombre = $this->getString('nombre');
    	$entidad->notilde = \StringHelper::notilde( $entidad->nombre );

    	$entidad->iso25964_language = $this->getString('iso25964_language');
    	$entidad->descripcion = $this->getString('descripcion');

    	$es_nuevo = FALSE;

    	if ($entidad->isNew()) {
    		$es_nuevo = TRUE;
    		$auth = $this->session->get('auth');

    		$entidad->id_thesaurus = $this->getString('id_thesaurus');
    		$entidad->estado_termino = self::CANDIDATO;
    		$entidad->fecha_ingreso = new RawValue('now()');
    		$entidad->id_ingreso = $auth['id'];
    		$entidad->rdf_uri = $this->config->rdf->baseUri . 'termino/%/' . \StringHelper::urlize( $entidad->nombre );
    	}
    	else {
    		$entidad->fecha_modifica = new RawValue('now()');
    		$entidad->estado_termino = $this->getString('estado_termino');
    	}

    	return $es_nuevo;
    }

    /**
     * Valida si ya existe
     * @param ThTermino $entidad
     */
    private function valida_existe($entidad) {
    	$es_nuevo = $entidad->isNew();

    	$bind = [1 => $entidad->notilde, 2=> $entidad->id_thesaurus];
    	if (!$es_nuevo) $bind[3] = $entidad->id_termino;

    	$existe = $entidad->findFirst(['notilde=?1 AND id_thesaurus=?2'.($es_nuevo?'':' AND id_termino!=?3'),'bind'=> $bind]);
    	return $existe;
    }

    /**
     * Guardar termino

     * @param ThTermino $entidad
     * @return integer
     */
    public function guardar($entidad)
    {
    	// Binding
    	$es_nuevo = $this->bind_post($entidad);

    	// Validar duplicado
     	if ($this->valida_existe($entidad)) {
     		$this->flash->error("Termino [{$entidad->nombre}] ya esta registrado.");
     		return false;
     	}

    	// Guardar
    	$this->db->begin();

    	if ($entidad->save() == false) {
    		$this->db->rollback();

    		foreach ($entidad->getMessages() as $message) {
    			$this->flash->error((string) $message);
    		}

    		return false;
    	}

	    // Guardar Termino preferido
   		$this->guardarRelacion($entidad, $entidad->nombre, self::TP_REL_EQ);

   		// Guardar Termino General
   		$te_general = $this->request->getPost(TerminoForm::TG_REL_EQ);
   		if (! empty($te_general)) {
   			$this->guardarRelacion($entidad, $te_general, TerminoForm::TG_REL_EQ);
   		}

   		// Guardar sinonimos
   		$sinonimos_list = $this->request->getPost(TerminoForm::SIN_REL_EQ);
		foreach ($sinonimos_list as $sin)
		{
			if (empty($sin)) continue;
			$this->guardarRelacion($entidad, $sin, TerminoForm::SIN_REL_EQ);
		}

    	// Actualizar thesaurus
    	$this->actualizar_actividad($entidad->id_thesaurus);

    	$this->db->commit();
    	$this->flash->success("Termino [{$entidad->nombre}] guardado exitosamente");

    	return $entidad->id_thesaurus;
    }

    /**
     * Actualizar actividad
     *
     * @param integer $id_thesaurus
     */
    private function actualizar_actividad($id_thesaurus)
    {
    	$sql = 'UPDATE th_thesaurus
    			   SET term_aprobados = (SELECT count(1) FROM th_termino e WHERE e.id_thesaurus = th_thesaurus.id_thesaurus AND e.estado_termino = ?)
    	  			 , term_pendientes = (SELECT count(1) FROM th_termino e WHERE e.id_thesaurus = th_thesaurus.id_thesaurus AND e.estado_termino = ?)
    	  			 , ultima_actividad = now()
    			 WHERE id_thesaurus = ?';
    	$this->db->execute($sql, [self::APROBADO, self::CANDIDATO, $id_thesaurus]);
    }

    /**
     * Guardar termino
     */
    public function guardarTermino($nombre, $descripcion, $lang, $id_thesaurus)
    {
    	$entidad = new ThTermino();

    	// Binding
    	$entidad->nombre = $nombre;
    	$entidad->notilde = \StringHelper::notilde( $entidad->nombre );
    	$entidad->iso25964_language = $lang;
    	$entidad->descripcion = $descripcion;
    	$entidad->rdf_uri = $this->config->rdf->baseUri . 'termino/%/' . \StringHelper::urlize( $entidad->nombre );

    	$es_nuevo = FALSE;

    	if ($entidad->isNew()) {
    		$es_nuevo = TRUE;
    		$auth = $this->session->get('auth');

    		$entidad->id_thesaurus = $id_thesaurus;
    		$entidad->estado_termino = self::CANDIDATO;
    		$entidad->fecha_ingreso = new RawValue('now()');
    		$entidad->id_ingreso = $auth['id'];
    	}
    	else {
    		$entidad->fecha_modifica = new RawValue('now()');
    	}

    	// Validar
    	if ($this->valida_existe($entidad)) {
    		$this->flash->error("Termino [{$entidad->nombre}] ya esta registrado.");
    		return false;
    	}

    	// Guardar
    	if ($entidad->save() == false) {

    		foreach ($entidad->getMessages() as $message) {
    			$this->flash->error((string) $message);
    		}
    		return false;
    	}

    	$this->flash->success("Termino [{$entidad->nombre}] guardado exitosamente");
    	return $entidad->id_thesaurus;
    }


}