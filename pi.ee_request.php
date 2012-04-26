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

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct() {
		
		$this->EE =& get_instance();

		$key = trim($this->EE->TMPL->fetch_param('key'));
		
		if (isset($_REQUEST[$key])) {
			$this->return_data = $_REQUEST[$key];
		} else {
			$this->return_data = '';
		}

	}
	
	public function post() {

		$key = trim($this->EE->TMPL->fetch_param('name'));
		
		if (isset($_POST[$key])) {
			return $_POST[$key];
		} else {
			return '';
		}

	}

	public function get() {

		$key = trim($this->EE->TMPL->fetch_param('name'));
		
		if (isset($_GET[$key])) {
			return $_GET[$key];
		} else {
			return '';
		}

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