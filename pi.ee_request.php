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
		$this->key = trim($this->EE->TMPL->fetch_param('name'));

	}
	
	public function request() {

		return self::_array_search($_REQUEST);

	}

	public function post() {

		return self::_array_search($_POST);

	}

	public function get() {

		return self::_array_search($_GET);

	}

	private function _array_search($objSearch) {

		$array = $objSearch;

		foreach (explode('.', $this->key) as $key_part) {
			
			$array = (isset($array[$key_part])) ? $array[$key_part] : false;
		
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


/* End of file pi.this_else_that.php */
/* Location: /system/expressionengine/third_party/this_else_that/pi.this_else_that.php */