<?php

use Behat\Transliterator\Transliterator;

/**
 * StringHelper
 * https://github.com/Behat/Transliterator
 *
 * @author mrobayo@gmail.com
 */
class StringHelper extends Transliterator
{


	/**
	 * Xml text to array
	 *
	 * @param string xml raw
	 */
	public static function xmltoArray($xmlraw) {
		$xmlObject = \StringHelper::loadxml($xmlraw);
		return \StringHelper::xml2array($xmlObject);
	}

	/**
	 * Quita los accentos
	 *
	 * @param string $string
	 * @return string
	 */
	public static function notilde($string) {
		return mb_strtolower(self::unaccent($string));
	}

	/**
	 * https://www.w3.org/TR/cooluris/
	 * https://www.w3.org/TR/2004/REC-rdf-concepts-20040210/
	 *
	 * @param string $text
	 * @param string $separator
	 */
	public static function urlize($text, $separator = '-') {
		return Transliterator::urlize(SELF::notilde($text), $separator);
	}

	/**
	 *
	 * @param string $xmlraw
	 * @return SimpleXMLElement
	 */
	public static function loadxml($xmlraw) {
		return simplexml_load_string($xmlraw, 'SimpleXMLElement', LIBXML_NOCDATA);
	}

	/**
	 *
	 *
	 * @param SimpleXMLElement $xmlObject
	 * @param array $out
	 * @return array
	 */
	public static function xml2array_orig($xmlObject, $out = [])
	{
		foreach($xmlObject->attributes() as $attr => $val)
			$out['@attributes'][$attr] = (string)$val;

			$has_childs = false;
			foreach($xmlObject as $index => $node)
			{
				$has_childs = true;
				$out[$index][] = SELF::xml2array($node);
			}
			if (!$has_childs && $val = (string)$xmlObject)
				$out['@value'] = $val;

				foreach ($out as $key => $vals)
				{
					if (is_array($vals) && count($vals) === 1 && array_key_exists(0, $vals))
						$out[$key] = $vals[0];
				}
				return $out;
	}

	public static function xml2array($xmlObject, $out = [])
	{
		foreach($xmlObject->attributes() as $attr => $val)
			$out['@attributes'][$attr] = (string)$val;

			$has_childs = false;
			foreach($xmlObject as $index => $node)
			{
				$has_childs = true;
				$out[$index][] = SELF::xml2array($node);
			}
			if (!$has_childs && $val = (string)$xmlObject)
				$out['@value'] = $val;

				foreach ($out as $key => $vals)
				{
					if (is_array($vals) && count($vals) === 1 && array_key_exists(0, $vals))
						$out[$key] = $vals[0];
				}

				if (count($out) == 1 && array_key_exists('@value', $out)) $out = $val;

				return $out;
	}

}
