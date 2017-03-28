<?php
namespace Thesaurus\Forms;

use Phalcon\Db\RawValue;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Thesaurus\Thesauri\ThTermino;
use Thesaurus\Thesauri\ThThesaurus;

/**
 * Formulario de Termino
 *
 * @author mrobayo
 */
class TerminoForm extends BaseForm
{
	const RELATION_TYPES = ['TP'=>'Término Preferente', 'TE'=>'Término Específico', 'TG'=>'Término General', 'TR'=>'Término Relacionado', 'SIN'=>'Término Alternativo'];

	const CANDIDATO = 'CANDIDATO',
		  APROBADO  = 'APROBADO';

	const ESTADO_LIST = [APROBADO, CANDIDATO, 'REEMPLAZADO', 'DEPRECADO'];

	/**
	 *
	 */
    public function initialize(ThTermino $entity = null, $options = ['edit'=>true])
    {
    	$this->add(new Hidden('id_termino'));

    	$this->addText('nombre', ['tooltip'=>'Nombre del Término', 'label'=>'Término', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);
    	$this->addTextArea('descripcion', ['label'=>'Definición', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);

    	if (! isset($options['thesaurus_list']))
    	{
    		$options['thesaurus_list'] = [];
    	}

    	if (! isset($options['language_list']))
    	{
    		$options['language_list'] = [];
    	}

        $this->addSelect('id_thesaurus', ['tooltip'=>'Thesaurus', 'label'=>'Thesaurus', 'options'=> $options['thesaurus_list'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
        $this->addSelect('iso25964_language', ['tooltip'=>'Idioma', 'label'=>'Idioma', 'options'=> $options['language_list'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

        if ($this->isEditable($options)) {

        }
        else {
        	foreach($this->getElements() as &$e) {
        		$e->setAttribute("readonly", "readonly");
        	}
        }
    }

    /**
     * Guardar termino

     * @param ThTermino $entidad
     * @return int
     */
    public function guardar($entidad)
    {
    	// Binding

    	$entidad->nombre = $this->getString('nombre');
    	$entidad->notilde = \StringHelper::notilde( $entidad->nombre );

    	$entidad->iso25964_language = $this->getString('iso25964_language');
    	$entidad->description = $this->getString('description');

    	$es_nuevo = FALSE;

    	if ($entidad->isNew()) {
    		$es_nuevo = TRUE;
    		$auth = $this->session->get('auth');

    		$entidad->id_thesaurus = $this->getString('id_thesaurus');
    		$entidad->estado_termino = self::CANDIDATO;
    		$entidad->fecha_ingreso = new RawValue('now()');
    		$entidad->id_ingreso = $auth['id'];
    		$entidad->rdf_uri = $this->config->rdf->baseUri . 'termino/';
    	}
    	else {
    		$entidad->fecha_modifica = new RawValue('now()');
    	}

    	// Validar

    	$existe = $entidad->findFirst(['notilde = ?1', 'bind'=>[1 => $entidad->notilde]]);

    	if ($existe) {
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

    	// Crear URI

    	$entidad->rdf_uri = $this->config->rdf->baseUri . 'termino/' . $entidad->id_termino . '/' . \StringHelper::urlize( $entidad->nombre );
    	$entidad->save();

    	// Actualizar thesaurus

    	$thesa = ThThesaurus::findFirstByid_thesaurus($entidad->id_thesaurus);

    	if ($thesa) {
    		$thesa->term_pendientes += $es_nuevo ? 1: 0;
    		$thesa->ultima_actividad = new RawValue('now()');

    		$thesa->save();
    	}

    	$this->db->commit();
    	$this->flash->success("Termino [{$entidad->nombre}] guardado exitosamente");

    	return $entidad->id_thesaurus;
    }


}