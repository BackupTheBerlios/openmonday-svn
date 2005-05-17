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
	 * Holds the om_exception class
	 * @access protected
	 * @var object
	 */
	protected $om_exception;

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
		ini_set('magic_quote_runtime', FALSE);

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
		 * magic_quotes_gpc should not be enabled
		 */
		if (ini_get('magic_quotes_gpc'))
		{
			throw new Exception('The PHP configuration option "magic_quotes_gpc" needs to be set to "off".', EXCEPTION_BAD_ENV);
		}

		/**
		 * register_globals should not be enabled
		 */
		if (ini_get('register_globals'))
		{
			throw new Exception('The PHP configuration option "register_globals" needs to be set to "off".', EXCEPTION_BAD_ENV);
		}
	}	
}

?>
