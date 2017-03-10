<?php

namespace Thesaurus\Controllers;

use FluidXml\FluidXml;

/**
 * Index
 * @author mrobayo@gmail.com
 */
class IndexController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Inicio');
		parent::initialize();
	}

	public function sha1Action()
	{
		//$u = \StringHelper::urlize( "La Sagrada Familia" );
		//echo $u;

		echo '<br>';
		echo $this->router->getModuleName();
		echo '<br>';
		echo $this->router->getNamespaceName();
		echo '<br>';
		echo $this->router->getControllerName();
		echo '<br>';
		echo $this->router->getActionName();

		echo '<pre>';
// 		echo iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', 'maría y josé');
// 		echo '<br>';
// 		echo iconv('UTF-8', 'ASCII//TRANSLIT', 'maría y josé');

// 		echo '<br>';
// 		echo \StringHelper::unaccent('María y José');
// 		echo '<br>';
// 		echo \StringHelper::urlize('María y José');

		$var = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<thesaurus>
  <identifier>urlize</identifier>
  <coverage>cobertura</coverage>
  <creator>mario robayo</creator>
  <date>2017-03-16</date>
  <created>2017-03-16</created>
  <modified/>
  <description>Ejemplo</description>
  <format>text/xml</format>
  <publisher>editor</publisher>
  <rights>derechos</rights>
  <source>fuente</source>
  <title>La Sagrada Familia</title>
  <license><lic>abc</lic><a>xyz</a></license>
  <type>controlled vocabulary</type>
  <language>es</language>
  <language>qu</language>
</thesaurus>

XML;
		$x = new FluidXml($var);

		//echo $x->dom();

		//print_r($x->dom());
		//print_r($x->array());

		echo '</pre>';

		echo '<hr>';

		$a = \StringHelper::loadxml($var);
		$a = \StringHelper::xml2array($a);

		echo '<pre>';
		print_r($a);
		echo '</pre>';
	}


    public function indexAction()
    {
    	//$connection = $this->db;
    	//$robot = $connection->fetchOne("select 1 dummy, version() ver");

    	$this->view->myheading = $this->config->application->appTitle; //print_r($robot, true);
    	$this->view->t = $this->getTranslation();
    }

    public function enviarAction()
    {
    	$this->view->disable();

    	/* create a dom document with encoding utf8 */
    	$domtree = new \DOMDocument('1.0', 'UTF-8');

    	/* create the root element of the xml tree */
    	$xmlRoot = $domtree->createElement("xml");
    	/* append it to the document created */
    	$xmlRoot = $domtree->appendChild($xmlRoot);

    	$currentTrack = $domtree->createElement("track");
    	$currentTrack = $xmlRoot->appendChild($currentTrack);

    	/* you should enclose the following two lines in a cicle */
    	$currentTrack->appendChild($domtree->createElement('path','song1.mp3'));
    	$currentTrack->appendChild($domtree->createElement('title','title of song1.mp3'));

    	$currentTrack->appendChild($domtree->createElement('path','song2.mp3'));
    	$currentTrack->appendChild($domtree->createElement('title','title of song2.mp3'));

    	/* get the xml printed */
    	echo $domtree->saveXML();
    }

}

