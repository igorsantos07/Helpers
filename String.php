<?php
namespace Helper;

/**
 * Utility class for arrays. Not completely migrated, forgive the poor English skills below :P
 * Originally based on https://github.com/igorsantos07/Yii-Extensions/blob/master/helpers/StringHelper.php
 *
 * @see https://github.com/igorsantos07/Yii-Extensions/blob/master/helpers/StringHelper.php
 * @author Igor Santos <igorsantos07@gmail.com>
 */
class String {

	/**
	 * Recebe um texto e retorna ele substituído de forma que seja legível numa URL (slug)
	 * @param string $text
	 * @param string $input_encoding
	 * @return string
	 */
	public static function slugify($text, $input_encoding = 'utf-8') {
        $text = static::removeAccents($text);
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);			// replace non letter or digits by -
		$text = trim($text,'-');									// trim
		$text = iconv($input_encoding,'us-ascii//TRANSLIT', $text);	// transliterate
		$text = strtolower($text);									// lowercase
		$text = preg_replace('~[^-\w]+~', '', $text);				// remove unwanted characters
	  	return $text;
	}

	/**
	 * Turns a slug or key-string into a more humanized text. Not perfect since it does not add accents back or fixes
	 * perfectly text capitalization, but at least changes underlines into spaces :)
	 * Example: NOT_REGISTERED = Not registered
	 * @param $text
	 * @return string
	 */
	public static function humanize($text) {
		$text = strtr($text, ['_' => ' ']);
		$text = ucfirst(strtolower($text));
		return $text;
	}

	/**
	 * Remove os acentos da string.
	 * @author Rafael Soares (código) e Igor Santos (organização em método separado)
	 * @param string $string
	 * @param boolean $comes_with_entities se o texto já vem com entidades HTML
	 * @param string $input_encoding
	 * @return string
	 */
	public static function removeAccents($string, $comes_with_entities = true, $input_encoding = 'utf-8') {
		$original_content = ($comes_with_entities)? $string : htmlentities($string, ENT_NOQUOTES, $input_encoding);
		$entities_to_letters = [
			'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
			'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
			'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
			'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
			'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
			'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
			'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
			'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
			'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
			'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
			'C' => '/&Ccedil;/',
			'c' => '/&ccedil;/',
			'N' => '/&Ntilde;/',
			'n' => '/&ntilde;/',
			'Y' => '/&Yacute;/',
			'y' => '/&yacute;|&yuml;/'
		];
		return preg_replace($entities_to_letters, array_keys($entities_to_letters), $original_content);
	}

	/**
	 * Troca entidades HTML de acentos pelas letras corretamente acentuadas
	 * @author Igor Santos
	 * @param string $string
	 * @param boolean $opposite faz o oposto: troca entidades por acentos
	 * @return string
	 */
	public static function accents2entities($string, $opposite = false) {
		$entities_to_accents = [
			'á' => '&aacute;', 'ã' => '&atilde;', 'â' => '&acirc;', 'à' => '&agrave;',
			'Á' => '&Aacute;', 'Ã' => '&Atilde;', 'Â' => '&Acirc;', 'À' => '&Agrave;',
			'é' => '&eacute;', 'ê' => '&ecirc;', 'É' => '&Eacute;', 'Ê' => '&Ecirc;',
			'í' => '&iacute;', 'Í' => '&Iacute;',
			'ó' => '&oacute;', 'ô' => '&ocirc;', 'õ' => '&otilde;',
			'Ó' => '&Oacute;', 'Ô' => '&Ocirc;', 'Õ' => '&Otilde;',
			'ú' => '&uacute;', 'Ú' => '&Uacute;',
			'ç' => '&ccedil;', 'Ç' => '&Ccedil;',
		];
		if ($opposite) $entities_to_accents = array_flip($entities_to_accents);

		return strtr($string, $entities_to_accents);
	}

	/**
	 * Returns only the numbers from the given $string.
	 * @param $string
	 * @return string
	 */
	public static function onlyNumbers($string) {
		return preg_replace('/[^0-9]/', '', $string);
	}

	/**
	 * Returns only ASCII letters from the given $string.
	 * @param $string
	 * @return string
	 */
	public static function onlyLetters($string) {
		return preg_replace('/[^a-zA-Z]/', '', $string);
	}

	/**
	 * Converts a number from Brazilian formatting to pure English
	 * @param $number_br_format The number with Brazilian format
	 * @return string
	 */
	public static function numberBr2Eng($number_br_format) {
		$dot	= strpos($number_br_format, '.');
		$comma	= strpos($number_br_format, ',');

		if (($dot === false && $comma === false) || $dot == true && $comma === false) //none of both or only a dot
			return $number_br_format;
		elseif ($dot === false) //only a comma
			return strtr($number_br_format, ',', '.');
		else { //have both
			if ($dot > $comma)
				return $number_br_format; //plain english format
			else
				return strtr($number_br_format, [',' => '.', '.' => ',']); //twisted format
		}
	}
}