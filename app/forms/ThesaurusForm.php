<?php
namespace Thesaurus\Forms;

use Phalcon\Db\RawValue;
use \FluidXml\FluidXml;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Thesaurus\Thesauri\ThThesaurus;

/**
 * Formulario de Thesaurus
 * http://www.sciencedirect.com/science/article/pii/S1877042814040725
 *
 * http://data.culture.fr/thesaurus/
 * http://data.culture.fr/thesaurus/page/ark:/67717/T2-27
 *
 * @author mrobayo
 */
class ThesaurusForm extends BaseForm
{
	const DEFAULT_TYPES = ['glossary'=>'Glosario', 'controlled vocabulary'=>'Vocabulario Controlado', 'taxonomy'=>'Taxonomía', 'thesaurus'=>'Tesauro', 'ontology'=>'Ontología'];

	// Licenses for Works https://www.gnu.org/licenses/license-list.en.html#DocumentationLicenses
	const DEFAULT_RIGHTS = [
			'#GPLOther'=> 'GNU General Public License',
			// The GNU GPL can be used for general data which is not software, as long as one can determine what the definition of “source code” refers to in the particular case. As it turns out, the DSL (see below) also requires that you determine what the “source code” is, using approximately the same definition that the GPL uses.
			'#FDLOther'=>'GNU Free Documentation License',
			// The GNU FDL is recommended for textbooks and teaching materials for all topics. (“Documentation” simply means textbooks and other teaching materials for using equipment or software.) We also recommend the GNU FDL for dictionaries, encyclopedias, and any other works that provide information for practical use.
			'#ccby'=>'Creative Commons Attribution 4.0 license',
			// This is a non-copyleft free license that is good for art and entertainment works, and educational works. It is compatible with all versions of the GNU GPL; however, like all CC licenses, it should not be used on software.
			'#ccbysa'=>'Creative Commons Attribution-Sharealike 4.0 license',
			// This is a copyleft free license that is good for artistic and entertainment works, and educational works. Like all CC licenses, it should not be used on software.
			'#dsl'=>'Design Science License',
			// This is a free and copyleft license meant for general data. Please don't use it for software or documentation, since it is incompatible with the GNU GPL and with the GNU FDL; however, it is fine to use for other kinds of data.
			'#FreeArt'=>'Free Art License',
			// This is a free and copyleft license meant for artistic works. It permits commercial distribution, as any free license must. It is a copyleft license because any larger work that includes part of the work you received must be released, as a whole, either under the same license or under a similar license that meets stated criteria. Please don't use it for software or documentation, since it is incompatible with the GNU GPL and with the GNU FDL.
			'#ODbl'=>'Open Database license'];
			// This is a free and copyleft license meant for data. It is incompatible with the GNU GPL. Please don't use it for software or documentation, since it is incompatible with the GNU GPL and with the GNU FDL. It makes inconvenient requirements about signing contracts which try to create an effect like copyleft for data that is not copyrightable, so we don't recommend using it; however, there is no reason to avoid using data released this way.

	/**
	 *
	 */
    public function initialize(ThThesaurus $entity = null, $options = ['edit'=>true])
    {
    	$this->add(new Hidden('id_thesaurus'));

    	$this->addText('nombre', ['tooltip'=>'Título del Thesaurus (requerido)', 'label'=>'Titulo', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Titulo es requerido'])] ]);
    	//$this->addText('iso25964_identifier', ['label'=>'DC:Identificador', 'filters'=>array('striptags', 'string')]);

    	$this->addTextArea('iso25964_description', ['label'=>'Descripción', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Descripción es requerido'])] ]);
        $this->addText('iso25964_publisher', ['tooltip'=>'Entidad responsable de la publicación', 'label'=>'Editor', 'filters'=>array('striptags', 'string')]);
        $this->addText('iso25964_rights', ['tooltip'=>'Copyright / Otros Derechos de la Información', 'label'=>'Derechos/Copyright', 'filters'=>array('striptags', 'string')]);

        $this->addSelect('iso25964_license', ['tooltip'=> 'Licencias para otros trabajos', 'label'=>'Licencia', 'options'=> self::DEFAULT_RIGHTS, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
        $this->addText('iso25964_coverage', ['tooltip'=>'Cobertura espacial o temporal del Thesaurus', 'label'=>'Cobertura/Alcance', 'filters'=>array('striptags', 'string')]);
        $this->addText('iso25964_created', ['label'=>'Fecha creación', 'filters'=>array('striptags', 'string')]);

        $this->addText('iso25964_subject', ['tooltip'=>'Indice de Términos indicando las materias del contenido', 'label'=>'Temática/Contenido', 'filters'=>array('striptags', 'string')]);
        $this->addText('iso25964_language', ['tooltip'=>'Idiomas soportados por el Thesaurus', 'label'=>'Idioma', 'filters'=>array('striptags', 'string')]);
        $this->addText('iso25964_source', ['tooltip'=>'Recursos desde los cuales el Thesaurus fue derivado', 'label'=>'Fuentes', 'filters'=>array('striptags', 'string')]);

        $this->addText('iso25964_creator', ['tooltip'=>'Persona o entidad principal responsable de la elaboración', 'label'=>'Creador', 'filters'=>array('striptags', 'string')]);
        $this->addText('iso25964_contributor', ['tooltip'=>'Personas u organizaciones quienes contribuyeron con el Thesaurus', 'label'=>'Colaborador', 'filters'=>array('striptags', 'string')]);
        $this->addSelect('iso25964_type', ['tooltip'=>'El género del vocabulario', 'label'=>'Tipo', 'options'=> self::DEFAULT_TYPES, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

        $this->addSelect('is_activo', ['tooltip'=>'Activar / Inactivar', 'label'=>'Activo', 'options'=> [0 => 'NO', 1 => 'SI'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
        $this->addSelect('is_primario', ['tooltip'=>'Primario (Aparece como pagina inicial del sitio)', 'label'=>'Primario', 'options'=> [0 => 'NO', 1 => 'SI'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);
        $this->addSelect('is_publico', ['tooltip'=>'Publico (Si LECTOR_PERMISO = ANONIMO es explorable sin ingresar como usuario registrado) / Privado (Solo pueden ver los usuarios autorizados)', 'label'=>'Publico/Privado', 'options'=> [0 => 'PRIVADO', 1 => 'PUBLICO'], 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

        $this->addSelect('permisos[]', ['tooltip'=>'Permisos por Usuario', 'label'=>'Tipo', 'options'=> AdUsuarioForm::PERMISOS_TYPES, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

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
     * @param ThThesaurus $entidad
     * @return int
     */
    public function guardar($entidad)
    {
    	// Binding

    	$entidad->nombre = $this->getString('nombre');
    	$entidad->notilde = \StringHelper::notilde( $entidad->nombre );

    	$entidad->xml_iso25964 = (string) $this->postToXml();
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
    		$entidad->is_activo = new RawValue('TRUE');
    		$entidad->is_primario = new RawValue('FALSE');
    		$entidad->is_publico = new RawValue('TRUE');
    		$entidad->aprobar_list = '';
    		$entidad->id_propietario = 1;
    		$entidad->fecha_ingreso = new RawValue('now()');

    		$existe = $entidad->findFirst(['notilde = ?1', 'bind'=>[1 => $entidad->notilde]]);
    	}
    	else {
    		$entidad->fecha_modifica = new RawValue('now()');

    		$existe = $entidad->findFirst(['notilde = ?1 AND id_thesaurus != ?2', 'bind'=>[1 => $entidad->notilde, 2 => $entidad->id_thesaurus ]]);
    	}

    	// Validar duplicado

    	if ($existe) {
    		$this->flash->error("Thesaurus {$entidad->nombre} ya existe");
    		return false;
    	}

    	$this->db->begin();

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
    	if (is_array($language)) foreach($language as $lang)
    	{
    		$xml->add(['language' => $lang]);
    	}

    	// $this->logger->error((string) $xml);
    	return $xml;
    }

}