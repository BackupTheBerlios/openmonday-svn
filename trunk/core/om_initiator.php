<?php

/**
 * File: $Id$
 *
 * This file contains the om_initiator class.
 *
 * @package OpenMonday
 * @subpackage Core
 * @copyright Copyright 2005 OpenMonday Development Group
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://openmonday.martlev.com
 *
 */

/**
 * Make sure this file is included by a valid entry point
 */
if (!defined('OPENMONDAY'))
{
	exit;
}

/**
 * om_initiator
 *
 * This class acts as a front controller for the application as a whole, and
 * is responsible for the initial startup.
 *
 */
class om_initiator
{
	/**
	 * Holds the configuration array
	 * @access protected
	 * @var array
	 */
	protected $om_config;

	/**
	 * Holds the exception handler
	 * @access protected
	 * @var object
	 */
	protected $om_exception;

	/**
	 * Holds the controller
	 * @access protected
	 * @var object
	 */
	protected $om_controller;

	/**
	 * __construct
	 *
	 * The constructor
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->om_config = array();
		$this->om_exception = (object) NULL;
		$this->om_controller = (object) NULL;
	}
	
	/**
	 * initiate
	 *
	 * Includes the required files for the core, and sets it up.
	 *
	 * @access public
	 * @param array $om_config The configuration values
	 */
	public function initiate(&$om_config)
	{
		/**
		 * Make sure we have full error reporting and disable magic quotes runtime
		 */
		ini_set('error_reporting', E_ALL);
		ini_set('magic_quotes_runtime', FALSE);

		/**
		 * Create our reference to the configuration array
		 */
		$this->om_config = &$om_config;

		/**
		 * Load the exception handler.
		 */
		require_once $om_config['file_core_exception'];
		$this->om_exception = new om_exception;
		set_exception_handler(array(&$this->om_exception, 'handler'));
		
		/**
		 * Check for bad environmental settings
		 */
		if (ini_get('magic_quotes_gpc'))
		{
			throw new Exception('The PHP configuration option "magic_quotes_gpc" needs to be set to "off".', EXCEPTION_BAD_ENV);
		}
		
		if (ini_get('magic_quotes_sybase'))
		{
			throw new Exception('The PHP configuration option "magic_quotes_sybase" needs to be set to "off".', EXCEPTION_BAD_ENV);
		}

		if (ini_get('register_globals'))
		{
			throw new Exception('The PHP configuration option "register_globals" needs to be set to "off".', EXCEPTION_BAD_ENV);
		}

		/**
		 * See if the GET and POST variables tell us which module to load.
		 */
		$module = '';
		if (!empty($_POST[$this->om_config['url_module']]) || !empty($_GET[$this->om_config['url_module']]))
		{
			$module = (!empty($_POST[$this->om_config['url_module']])) ? $_POST[$this->om_config['url_module']] : $_GET[$this->om_config['url_module']];
			$module = preg_replace('/[^0-9a-z_]/i', '', $module);
		}

		/**
		 * See if the GET and POST variables tell us which action the module
		 * controller needs to load.
		 */
		$action = '';
		if (!empty($_POST[$this->om_config['url_action']]) || !empty($_GET[$this->om_config['url_action']]))
		{
			$action = (!empty($_POST[$this->om_config['url_action']])) ? $_POST[$this->om_config['url_action']] : $_GET[$this->om_config['url_action']];
			$action = preg_replace('/[^0-9a-z_]/i', '', $action);
		}

		/**
		 * See if the module's controller definition exists
		 */
		$controller_file = $this->om_config['dir_modules'] . $module . '/' . 'controller' . $this->om_config['file_extension'];
		if ($module == '' || $module == $this->om_config['default_module'] || !is_file($controller_file))
		{
			/**
			 * Module controller not found. Let's try to find the default module.
			 */
			$controller_file = $this->om_config['dir_modules'] . $this->om_config['default_module'] . '/' . 'controller' . $this->om_config['file_extension'];
			if (!is_file($controller_file))
			{
				/**
				 * Default module doesn't exist either. We can't continue.
				 */
				throw new Exception('The "default_module" set in the configuration file does not exist.', EXCEPTION_BAD_CONFIG);
			}
		}

		/**
		 * Module exists. Let's load it's controller definition.
		 */
		require_once $om_config['file_core_base_controller'];
		require_once $filename;

		/**
		 * Start up the controller
		 */
		$this->om_controller = new om_controller;
		$this->om_controller->do($action);
	}
}

?>
