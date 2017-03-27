<?php
namespace Thesaurus\Forms;

use Phalcon\Db\RawValue;
use \FluidXml\FluidXml;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Thesaurus\Thesauri\ThTermino;

/**
 * Formulario de Termino
 *
 * @author mrobayo
 */
class TerminoForm extends BaseForm
{

	/**
	 *
	 */
    public function initialize(ThTermino $entity = null, $options = ['edit'=>true])
    {
    	$this->add(new Hidden('id_termino'));

    	$this->addText('nombre', ['tooltip'=>'Nombre del Término', 'label'=>'Término', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);
    	$this->addTextArea('descripcion', ['label'=>'Descripción', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);

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
    public function guardar($entidad) {
    	$this->db->begin();

    	$xml = $this->postToXml($this->request);

    	$entidad->nombre = $this->request->getPost('nombre', array('string', 'striptags'));
    	$entidad->notilde = \StringHelper::notilde( $entidad->nombre );

    	$entidad->xml_iso25964 = (string) $xml;
    	$entidad->rdf_uri = $this->config->rdf->baseUri . \StringHelper::urlize( $entidad->nombre );
    	$entidad->iso25964_identifier = \StringHelper::urlize($entidad->nombre);

    	$entidad->iso25964_description = $this->getString('iso25964_description');
    	$entidad->iso25964_publisher = $this->getString('iso25964_publisher');
    	$entidad->iso25964_rights = $this->getString('iso25964_rights');

    	$entidad->iso25964_license = $this->getString('iso25964_license');
    	$entidad->iso25964_coverage = $this->getString('iso25964_coverage');
    	$entidad->iso25964_created = $this->getString('iso25964_created');

    	$entidad->iso25964_subject = $this->getString('iso25964_subject');
    	$entidad->iso25964_language = $this->getString('iso25964_language');
    	$entidad->iso25964_source = $this->getString('iso25964_source');

    	$entidad->iso25964_creator = $this->getString('iso25964_creator');
    	$entidad->iso25964_contributor = $this->getString('iso25964_contributor');
    	$entidad->iso25964_type = $this->getString('iso25964_type');

    	if ($entidad->isNew()) {
    		$entidad->term_aprobados = 0;
    		$entidad->term_pendientes = 0;
    		$entidad->is_activo = TRUE;
    		$entidad->is_publico = TRUE;
    		$entidad->aprobar_list = '';
    		$entidad->id_propietario = 1;
    		$entidad->fecha_ingreso = new RawValue('now()');
    	}
    	else {
    		$entidad->fecha_modifica = new RawValue('now()');
    	}

    	if ($entidad->save() == false) {
    		$this->db->rollback();

    		foreach ($entidad->getMessages() as $message) {
    			$this->flash->error((string) $message);
    		}

    		return false;
    	}

    	$this->db->commit();
    	$this->flash->success('Guardado exitosamente');

    	return $entidad->id_thesaurus;
    }

    /**
     * Post a Xml
     *
     * @return \Thesaurus\Forms\FluidXml
     */
    public function postToXml() {
    	$xml = new FluidXml('thesaurus');

    	$title = $this->getString("nombre");
    	$identifier = \StringHelper::urlize($title);

    	$description = $this->getString("iso25964_description");

    	$rights = $this->getString("iso25964_rights");
    	$source = $this->getString("iso25964_source");

    	$publisher = $this->getString("iso25964_publisher");
    	$license = $this->getString("iso25964_license");
    	$coverage = $this->getString("iso25964_coverage");
    	$creator = $this->getString("iso25964_creator");

    	$created = $this->getString("iso25964_created");
    	$modified = $this->getString("iso25964_modified");
    	$type = $this->getString("iso25964_type");

    	$xml->add([
    			'identifier'  => $identifier,
    			'coverage' => $coverage,
    			'creator' => $creator,
    			'date' => $created,
    			'created' => $created,
    			'modified' => $modified,
    			'description' => $description,
    			'format' => 'text/xml',
    			'publisher' => $publisher,
    			'rights' => $rights,
    			'source' => $source,
    			'title' => $title,
    			'license' => $license,
    			'type' => $type
    	]);

    	$language = explode(',', $this->getString("language"));

    	foreach($language as $lang) {
    		$xml->add(['language' => $lang]);
    	}

    	// $this->logger->error((string) $xml);
    	return $xml;
    }

}