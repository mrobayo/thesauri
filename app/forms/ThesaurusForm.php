<?php
namespace Thesaurus\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Formulario de Thesaurus
 * @author mrobayo
 */
class ThesaurusForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
    	$this->add(new Hidden('id_thesaurus'));

        // Name
    	$this->addText('nombre', ['label'=>'Titulo', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Titulo es requerido'])] ]);

		// Descripcion
        $this->addTextArea('descripcion', ['label'=>'Descripción', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Descripción es requerido'])] ]);

        // DC:IDENTIFIER
        $this->addText('identifier', ['label'=>'DC:Identificador', 'filters'=>array('striptags', 'string')]);

        // Publisher
        $this->addText('publisher', ['label'=>'Editor', 'filters'=>array('striptags', 'string')]);

        // Right
        $this->addText('rights', ['label'=>'Derechos/Copyright', 'filters'=>array('striptags', 'string')]);

		//Licencia
        $this->addText('license', ['label'=>'Licencia', 'filters'=>array('striptags', 'string')]);

        //Coverage
        $this->addText('coverage', ['label'=>'Cobertura', 'filters'=>array('striptags', 'string')]);

        //Fecha creación
        $this->addText('created', ['label'=>'Fecha creación', 'filters'=>array('striptags', 'string')]);

        //Subject
        $this->addText('subject', ['label'=>'Temática/Contenido', 'filters'=>array('striptags', 'string')]);

        //Language
        $this->addText('language', ['label'=>'Idioma', 'filters'=>array('striptags', 'string')]);

        //Source
        $this->addText('source', ['label'=>'Fuente', 'filters'=>array('striptags', 'string')]);

        //Creador
        $this->addText('creator', ['label'=>'Creador', 'filters'=>array('striptags', 'string')]);

        //Contributor
        $this->addText('contributor', ['label'=>'Colaborador', 'filters'=>array('striptags', 'string')]);

        //Tipo
        $this->addText('type', ['label'=>'Tipo', 'filters'=>array('striptags', 'string')]);

        //Formato
        $this->addText('format', ['label'=>'DC:Format', 'filters'=>array('striptags', 'string')]);
    }

    private function addTextArea($name, $attrs) {
    	$item = new TextArea($name);
    	$item->setLabel($attrs['label']);
    	if (isset($attrs['filters'])) {
    		$item->setFilters($attrs['filters']);
    	}
    	if (isset($attrs['validators'])) {
    		$item->addValidators($attrs['validators']);
    	}
    	$this->add($item);
    }

    private function addText($name, $attrs) {
    	$item = new Text($name);
    	$item->setLabel($attrs['label']);
    	if (isset($attrs['filters'])) {
    		$item->setFilters($attrs['filters']);
    	}
    	if (isset($attrs['validators'])) {
    		$item->addValidators($attrs['validators']);
    	}
    	$this->add($item);
    }

}