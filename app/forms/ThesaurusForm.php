<?php
namespace Thesaurus\Forms;

use \FluidXml\FluidXml;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Forms\Element\Select;
use Thesaurus\Thesauri\ThThesaurus;

/**
 * Formulario de Thesaurus
 *
 *
 *
 * @author mrobayo
 */
class ThesaurusForm extends Form
{
	const DEFAULT_TYPES = ['glossary'=>'Glosario', 'controlled vocabulary'=>'Vocabulario Controlado', 'taxonomy'=>'Taxonomía', 'thesaurus'=>'Tesauro', 'ontology'=>'Ontología'];

	// Licenses for Works of Practical Use
	// https://www.gnu.org/licenses/license-list.en.html#DocumentationLicenses

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

			'#ODbl'=>'Open Database license'
			// This is a free and copyleft license meant for data. It is incompatible with the GNU GPL. Please don't use it for software or documentation, since it is incompatible with the GNU GPL and with the GNU FDL. It makes inconvenient requirements about signing contracts which try to create an effect like copyleft for data that is not copyrightable, so we don't recommend using it; however, there is no reason to avoid using data released this way.
	];

	/**
	 * getPost
	 */
	private function getPost($name, $filters) {
		return $this->request->getPost($name, $filters);
	}

	/**
	 * Get String
	 *
	 * @param string $name
	 * @param array|string $filters
	 *
	 * @return string
	 */
	private function getString($name) {
		return $this->request->getPost($name, ['string', 'striptags']);
	}


	/**
	 *
	 * @param ThThesaurus $item
	 */
	public function textToXml($xmlraw) {
		$xmlObject = \StringHelper::loadxml($xmlraw);
		return \StringHelper::xml2array($xmlObject);
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

		$description = $this->getString("description");

		$rights = $this->getString("rights");
		$source = $this->getString("source");

		$publisher = $this->getString("publisher");
		$license = $this->getString("license");
		$coverage = $this->getString("coverage");
		$creator = $this->getString("creator");

		$created = $this->getString("created");
		$modified = $this->getString("modified");

		$type = $this->getString("type");

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

		$this->logger->error((string) $xml);
		return $xml;
	}

    public function initialize($entity = null, $options = null)
    {
    	$this->add(new Hidden('id_thesaurus'));

        // Name
    	$this->addText('nombre', ['label'=>'Titulo', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Titulo es requerido'])] ]);

		// Descripcion
        $this->addTextArea('description', ['label'=>'Descripción', 'filters'=>array('striptags', 'string'), 'validators'=>[new PresenceOf(['message' => 'Descripción es requerido'])] ]);

        // DC:IDENTIFIER
        $this->addText('identifier', ['label'=>'DC:Identificador', 'filters'=>array('striptags', 'string')]);

        // Publisher
        $this->addText('publisher', ['label'=>'Editor', 'filters'=>array('striptags', 'string')]);

        // rights
        $this->addText('rights', ['label'=>'Derechos/Copyright', 'filters'=>array('striptags', 'string')]);

		// license
        $this->addSelect('license', ['label'=>'Licencia', 'options'=> self::DEFAULT_RIGHTS, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

        // coverage
        $this->addText('coverage', ['label'=>'Cobertura', 'filters'=>array('striptags', 'string')]);

        //Fecha creación
        $this->addText('created', ['label'=>'Fecha creación', 'filters'=>array('striptags', 'string')]);

        //Subject
        $this->addText('subject', ['label'=>'Temática/Contenido', 'filters'=>array('striptags', 'string')]);

        //Language
        $this->addText('language', ['label'=>'Idioma', 'filters'=>array('striptags', 'string')]);

        //Source
        $this->addText('source', ['label'=>'Fuente', 'filters'=>array('striptags', 'string')]);

        // creator
        $this->addText('creator', ['label'=>'Creador', 'filters'=>array('striptags', 'string')]);

        //Contributor
        $this->addText('contributor', ['label'=>'Colaborador', 'filters'=>array('striptags', 'string')]);

        //Tipo
        $this->addSelect('type', ['label'=>'Tipo', 'options'=> self::DEFAULT_TYPES, 'attrs'=> ['useEmpty' => true, 'emptyText' => '--']]);

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

    private function addSelect($name, $attrs) {
    	$item = new Select($name, $attrs['options'], $attrs['attrs']);
    	$item->setLabel($attrs['label']);
    	$this->add($item);
    }

}