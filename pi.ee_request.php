<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * EE Request Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Jesse Bunch
 * @link		http://getbunch.com/
 */

$plugin_info = array(
	'pi_name'		=> 'EE Request',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Jesse Bunch',
	'pi_author_url'	=> 'http://getbunch.com/',
	'pi_description'=> 'Returns GET and POST data',
	'pi_usage'		=> Ee_request::usage()
);


class Ee_request {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		$this->EE =& get_instance();

	}
	
	public function request() {

		$key_string = trim($this->EE->TMPL->fetch_param('name'));

		return self::_array_search($_REQUEST, $key_string);

	}

	public function session() {

		$key_string = trim($this->EE->TMPL->fetch_param('name'));

		return self::_array_search($_SESSION, $key_string);

	}

	public function post() {

		$key_string = trim($this->EE->TMPL->fetch_param('name'));

		return self::_array_search($_POST, $key_string);

	}

	public function get() {

		$key_string = trim($this->EE->TMPL->fetch_param('name'));

		return self::_array_search($_GET, $key_string);

	}

	/**
	 * Recursively traverses an array, returning 
	 * the value specified by the dot notated path
	 * @param $array array The array to traverse
	 * @param $key_string string The dot notated path to return (ie results.item.forecast.temperature)
	 * @return mixed
	 * @author Jesse Bunch & Chris Lock
	*/
	static function _array_search(&$array, $key_string) {

		foreach (explode('.', $key_string) as $key) {
			
			// Index or Assoc Key?
			if ($num_matches = preg_match('/(?<=\[)([0-9]+)(?=\])/', $key, $key_indexes)) {

				$key_index = $key_indexes[0];

				$array = (isset($array[$key_index])) ? $array[$key_index] : false;

			} else {

				$array = (isset($array[$key])) ? $array[$key] : false;

			}
		
		}

		return $array;

	}
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

 Since you did not provide instructions on the form, make sure to put plugin documentation here.
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.ee_request.php */
/* Location: /system/expressionengine/third_party/ee_request/pi.ee_request.php */