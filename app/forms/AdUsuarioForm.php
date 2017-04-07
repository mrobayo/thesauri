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
	const PERMISO_LECTOR = 'LECTOR';
	const PERMISO_EDITOR = 'EDITOR';
	const PERMISO_EXPERTO = 'EXPERTO';

	const PERMISOS_TYPES = [self::PERMISO_LECTOR=> 'Explorar y consultar', self::PERMISO_EDITOR=> 'Ingresar términos', self::PERMISO_EXPERTO => 'Ingresar y Aprobar términos'];

	const ROLE_TYPES = ['USER'=> 'USUARIO', 'ADMIN'=>'ADMINISTRADOR'];
	const AVISO_TYPES = ['TERMINO'=> 'TERMINO', 'DIARIO'=>'DIARIO', 'SEMANAL'=>'SEMANAL'];

	/**
	 *
	 */
    public function initialize(AdUsuario $entity = null, $options = ['edit'=>true])
    {
    	$this->add(new Hidden('id_usuario'));

    	$this->addText('nombre', ['tooltip'=>'Nombre del Usuario (requerido)', 'label'=>'Nombre', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);
    	$this->addText('email', ['tooltip'=>'Correo electrónico (requerido)', 'label'=>'Email', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'es requerido'])] ]);

    	$this->addSelect('app_role', ['tooltip'=>'Role de Seguridad', 'label'=>'Role', 'options'=> self::ROLE_TYPES, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
    	$this->addSelect('recibir_avisos', ['tooltip'=>'Recibir Avisos y Notificaciones', 'label'=>'Notificaciones', 'options'=> self::AVISO_TYPES, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
    	$this->addSelect('is_activo', ['tooltip'=>'Activar / Inactivar', 'label'=>'Activo', 'options'=> [0 => 'NO', 1 => 'SI'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

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
    public function guardar($entidad)
    {
    	// Binding

     	$entidad->nombre = $this->getString('nombre');
     	$entidad->app_role = $this->getString('app_role');
     	$entidad->email = $this->getString('email');

     	$entidad->is_activo = $this->getString('is_activo') ? new RawValue('TRUE'): new RawValue('FALSE');
     	$entidad->recibir_avisos = $this->getString('recibir_avisos');

     	if ($entidad->isNew()) {
     		$entidad->is_activo = new RawValue('TRUE');
     		$entidad->fecha_ingreso = new RawValue('now()');
     		if (empty($entidad->clave)) $entidad->clave = 'N/A';

     		//
     		$existe = $entidad->findFirst(['email = ?1', 'bind'=>[1 => $entidad->email ]]);
     	}
     	else
     	{
     		$existe = $entidad->findFirst(['email = ?1 AND id_usuario != ?2', 'bind'=>[1 => $entidad->email, 2 => $entidad->id_usuario ]]);
     	}

     	// Validar email duplicado

     	if ($existe) {
     		$this->flash->error("Usuario con el email {$entidad->email} ya existe");
     		return false;
     	}

     	// Guardar

   		$this->db->begin();

   		if ($entidad->save() == false)
   		{
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