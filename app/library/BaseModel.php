<?php

//namespace Thesaurus\Model;

class BaseModel extends \Phalcon\Mvc\Model {

	/**
	 * si es nuevo
	 * @var boolean
	 */
	protected $_new = true;

	public function afterFetch() {
		$this->_new = false;
	}

	/**
	 * ya no es nuevo
	 */
	public function afterSave()	{
		$this->_new = false;
	}

	/**
	 * si es nuevo
	 * @return boolean
	 */
	public function isNew() {
		return $this->_new;
	}



}
