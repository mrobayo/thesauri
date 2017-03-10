<?php
namespace Thesaurus\Forms;

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;

/**
 * Formulario Base
 *
 * @author mrobayo
 */
class BaseForm extends Form
{

	/**
	 * Editable
	 * @var booleam $editable
	 */
	protected $editable;

	/**
	 * Phalcon\Forms\Form constructor
	 *
	 * @param object $entity
	 * @param array $userOptions
	 */
	public function __construct($entity = null, $userOptions = null) {
		parent::__construct($entity, $userOptions);
		$this->editable = $this->isEditable($userOptions);
	}

	/**
     * Renders a specific item in the form
     *
     * @param string $name
     * @param array $attributes
     * @return string
     */
    public function render($name, $attributes = null) {
    	$element  = $this->get($name);
    	$tooltip = $element->getAttribute('tooltip');

    	if (!is_array($attributes)) {
    		$attributes = [];
    	}

    	if ($tooltip) {
    		$attributes['aria-describedby'] = $name.'Help';
    		$tooltip = '<small id="'.$name.'Help" class="form-text text-muted" '.($this->editable ? '' : 'hidden').'>'.$tooltip.'</small>';
    	}
    	return parent::render($name, $attributes) . $tooltip;
    }

	/**
	 * Indica si es editable
	 *
	 * @param array $options
	 * @return boolean
	 */
	protected function isEditable($options) {
		if (!empty($options) && isset($options['edit'])) {
			return $options['edit'];
		}
		return TRUE;
	}

	/**
	 * getPost
	 */
// 	protected function getPost($name, $filters) {
// 		return $this->request->getPost($name, $filters);
// 	}

	/**
	 * Get String
	 *
	 * @param string $name
	 * @param array|string $filters
	 *
	 * @return string
	 */
	public function getString($name) {
		return $this->request->getPost($name, ['string', 'striptags']);
	}


    protected function addTextArea($name, $attrs) {
    	$item = new TextArea($name);
    	$item->setLabel($attrs['label']);

    	if (isset($attrs['filters'])) {
    		$item->setFilters($attrs['filters']);
    		unset($attrs['filters']);
    	}
    	if (isset($attrs['validators'])) {
    		$item->addValidators($attrs['validators']);
    		unset($attrs['validators']);
    	}
    	$item->setAttributes($attrs);

    	$this->add($item);
    }

    protected function addText($name, $attrs) {
    	$item = new Text($name);
    	$item->setLabel($attrs['label']);

    	if (isset($attrs['filters'])) {
    		$item->setFilters($attrs['filters']);
    		unset($attrs['filters']);
    	}
    	if (isset($attrs['validators'])) {
    		$item->addValidators($attrs['validators']);
    		unset($attrs['validators']);
    	}
    	$item->setAttributes($attrs);

    	$this->add($item);
    }

    protected function addSelect($name, $attrs) {
    	$item = new Select($name, $attrs['options'], $attrs['attrs']);
    	$item->setLabel($attrs['label']);

    	unset($attrs['attrs']);
    	unset($attrs['label']);
    	unset($attrs['options']);

    	$item->setAttributes($attrs);
    	$this->add($item);
    }

    /**
     * Valida si el editable
     */
    public function editable() {
    	return $this->editable;
    }


}