<?php
namespace Thesaurus\Forms;

use Phalcon\Db\RawValue;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Thesaurus\Sistema\AdUsuario;

/**
 * Formulario de Thesaurus
 * http://www.sciencedirect.com/science/article/pii/S1877042814040725
 *
 * http://data.culture.fr/thesaurus/
 * http://data.culture.fr/thesaurus/page/ark:/67717/T2-27
 *
 * @author mrobayo
 */
class AdUsuarioForm extends BaseForm
{
	/**
	 *
	 */
    public function initialize(AdUsuario $entity = null, $options = ['edit'=>true])
    {
    	$this->add(new Hidden('id_usuario'));

    	$this->addText('nombre', ['tooltip'=>'Nombre del Usuario (requerido)', 'label'=>'Nombre', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Nombre es requerido'])] ]);

        if ($this->isEditable($options)) {

        }
        else {
        	foreach($this->getElements() as &$e) {
        		$e->setAttribute("readonly", "readonly");
        	}
        }
    }

    /**
     * Guardar thesaurus
     * iconv('ISO-8859-1','ASCII//TRANSLIT',$val);
     * iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$val);
     *
     * @param AdUsuario $entidad
     * @return int
     */
    public function guardar($entidad) {
    	$this->db->begin();

    	$entidad->nombre = $this->request->getString('nombre');

    	if ($entidad->isNew()) {
    		$entidad->is_activo = TRUE;
    		$entidad->fecha_ingreso = new RawValue('now()');
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

    	return $entidad->id_usuario;
    }

}