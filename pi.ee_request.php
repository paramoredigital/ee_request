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
 * @author		Jesse Bunch & Cameron West
 * @link		http://paramoredigital.com/
 */

$plugin_info = array(
	'pi_name'		=> 'EE Request',
	'pi_version'	=> '1.3',
	'pi_author'		=> 'Jesse Bunch & Cameron West',
	'pi_author_url'	=> 'http://paramoredigital.com/',
	'pi_description'=> 'Returns and sets PHP server data',
	'pi_usage'		=> Ee_request::usage()
);


class Ee_request {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public function request()
	{
		$key_string = trim($this->EE->TMPL->fetch_param('name'));
		$format_string = trim($this->EE->TMPL->fetch_param('format'));
		$default_string = trim($this->EE->TMPL->fetch_param('default'));
		$value_string = trim($this->EE->TMPL->fetch_param('value'));
		$unset_string = trim($this->EE->TMPL->fetch_param('unset'));

		if($value_string)
			$_REQUEST = self::_set_value($_REQUEST, $key_string, $value_string, $default_string, $format_string);
		elseif($unset_string)
			$_REQUEST = self::_set_value($_REQUEST, $unset_string, $value_string, $default_string, $format_string, 'unset');
		else
			return $this->_array_search($_REQUEST, $key_string, $default_string, $format_string);
	}

	public function session()
	{
		// Is a session open?
		$session_exists = (isset($_SESSION)) ? true : false;

		if (!$session_exists)
			session_start();

		$temp_session = $_SESSION;

		$key_string = trim($this->EE->TMPL->fetch_param('name'));
		$format_string = trim($this->EE->TMPL->fetch_param('format'));
		$default_string = trim($this->EE->TMPL->fetch_param('default'));
		$value_string = trim($this->EE->TMPL->fetch_param('value'));
		$unset_string = trim($this->EE->TMPL->fetch_param('unset'));

		if($value_string)
			$_SESSION = $this->_set_value($temp_session, $key_string, $value_string, $default_string, $format_string);
		elseif($unset_string)
			$_SESSION = $this->_set_value($temp_session, $unset_string, $value_string, $default_string, $format_string, 'unset');
		else
			$result = $this->_array_search($temp_session, $key_string, $default_string, $format_string);

		// Close session to avoid session_start warning
		//if (!$session_exists) session_write_close();

		if($value_string || $unset_string)
			return '';
		else
			return $result;
	}

	public function post()
	{
		$key_string = trim($this->EE->TMPL->fetch_param('name'));
		$format_string = trim($this->EE->TMPL->fetch_param('format'));
		$default_string = trim($this->EE->TMPL->fetch_param('default'));
		$value_string = trim($this->EE->TMPL->fetch_param('value'));
		$unset_string = trim($this->EE->TMPL->fetch_param('unset'));

		if($value_string)
			$_POST = $this->_set_value($_POST, $key_string, $value_string, $default_string, $format_string);
		elseif($unset_string)
			$_POST = $this->_set_value($_POST, $unset_string, $value_string, $default_string, $format_string, 'unset');
		else
			return $this->_array_search($_POST, $key_string, $default_string, $format_string);
	}

	public function get()
	{
		$key_string = trim($this->EE->TMPL->fetch_param('name'));
		$format_string = trim($this->EE->TMPL->fetch_param('format'));
		$default_string = trim($this->EE->TMPL->fetch_param('default'));
		$value_string = trim($this->EE->TMPL->fetch_param('value'));
		$unset_string = trim($this->EE->TMPL->fetch_param('unset'));

		if($value_string)
			$_GET = $this->_set_value($_GET, $key_string, $value_string, $default_string, $format_string);
		elseif($unset_string)
			$_GET = $this->_set_value($_GET, $unset_string, $value_string, $default_string, $format_string, 'unset');
		else
			return $this->_array_search($_GET, $key_string, $default_string, $format_string);
	}

	public function cookie()
	{
		$key_string = trim($this->EE->TMPL->fetch_param('name'));
		$format_string = trim($this->EE->TMPL->fetch_param('format'));
		$default_string = trim($this->EE->TMPL->fetch_param('default'));
		$value_string = trim($this->EE->TMPL->fetch_param('value'));
		$unset_string = trim($this->EE->TMPL->fetch_param('unset'));

		if($value_string)
			$_COOKIE = $this->_set_value($_COOKIE, $key_string, $value_string, $default_string, $format_string);
		elseif($unset_string)
			$_COOKIE = $this->_set_value($_COOKIE, $unset_string, $value_string, $default_string, $format_string, 'unset');
		else
			return $this->_array_search($_COOKIE, $key_string, $default_string, $format_string);
	}

	/**
	 * Recursively traverses an array, and sets
	 * the value specified by the dot notated path
	 *
	 * @param $array array The array to traverse
	 * @param $key_string string The dot notated path to store to (ie results.item.forecast.temperature
	 * @param $value string the value to store
	 * @param $default_string string the default value to set if the item is not found
	 * @param $format_string string the date-time format to use when storing dates
	 * @param $action string whether the action we take is to 'set' or 'unset' a value at this location
	 * @return mixed
	 */
	private function _set_value($array, $key_string, $value, $default_string='', $format_string='', $action = 'set')
	{
		$keys = explode('.', $key_string);
		$key = array_shift($keys);

		if(!$value) $value = $default_string;
		if($format_string) $value = date($format_string, strtotime($value));

		// Index or Assoc Key?
		if ($num_matches = preg_match('/(?<=\[)([0-9]+)(?=\])/', $key, $key_indexes))
			$key = $key_indexes[0];

		if(!count($keys))
		{
			if($action == 'set')
			{
				if(isset($array[$key]))
				{
					if(!is_array($array[$key]) && $array[$key] != $value)
						$array[$key] = array($array[$key]);
					if(is_array($array[$key]) && !in_array($value, $array[$key]))
						$array[$key][] = $value;
				}
				else
					$array[$key] = $value;
			}
			elseif($action == 'unset' && isset($array[$key]))
			{
				if(!is_array($array[$key]))
					unset($array[$key]);
				elseif(is_array($array[$key]) && in_array($value, $array[$key]))
					unset($array[$key][array_search($value, $array[$key])]);
				elseif(is_array($array[$key]) && !$value)
					unset($array[$key]);
			}
		}
		else
		{
			if(!isset($array[$key]))
				$array[$key] = array();

			$key_string = implode('.', $keys);
			$array[$key] = self::_set_value($array[$key], $key_string, $value, '', '', $action);
		}

		return $array;
	}

	/**
	 * Recursively traverses an array, returning
	 * the value specified by the dot notated path
	 *
	 * @param $array array The array to traverse
	 * @param $key_string string The dot notated path to return (ie results.item.forecast.temperature)
	 * @param $default_string string the default value to return if the item is not found
	 * @param $format_string string the date-time format to use when retrieving a date value
	 * @return mixed
	*/
	private function _array_search($array, $key_string, $default_string='', $format_string='')
	{
		foreach($array as $key => $item)
			$array[$key] = urldecode($item);

		foreach (explode('.', $key_string) as $key)
		{
			// Index or Assoc Key?
			if ($num_matches = preg_match('/(?<=\[)([0-9]+)(?=\])/', $key, $key_indexes)) {

				$key_index = $key_indexes[0];

				$array = (isset($array[$key_index])) ? $array[$key_index] : "";
			} else {
				$array = (isset($array[$key])) ? $array[$key] : "";
			}
		}

		if(!$array && $default_string)
			$array = $default_string;


		if($array && $format_string)
		{
			if($format_string == 'pipe' && is_array($array))
				$array = implode('|', $array);

			if($format_string != 'pipe' && !is_array($array))
			{
				if(is_numeric($array))
					$array = date($format_string, $array);
				else
					$array = date($format_string, strtotime($array));
			}
		}

		$array = $this->validateDataType($key_string, $array);

		return $array;
	}

	/**
	 * Validates input based on the config file
	 * Currently only supports date and numeric
	 * This should be used to sanitize input we are getting through EE Request
	 *
	 * @param $key string the config item to look for
	 * @param $value string the value that is being sanitized
	 */
	private function validateDataType($key, $value)
	{
		$config = $this->EE->config->item('ee_request_validation');
		if(! isset($config[$key]))
			return $value;

		if($config[$key] == 'numeric' && ! is_numeric($value))
			return '';

		if($config[$key] == 'date')
			if(date('m/d/Y', strtotime($value)) != $value && date('Y-m-d H:i', strtotime($value)) != $value)
				return false;

		return $value;
	}
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

 Please see the <a href="https://github.com/paramoreagency/ee_request/blob/master/README.md" target="_blank">GitHub README file</a> for full documentation.

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.ee_request.php */
/* Location: /system/expressionengine/third_party/ee_request/pi.ee_request.php */