<?php

/**
 * File: $Id$
 *
 * This file contains the om_exception class.
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
 * om_exception
 *
 * This is our custom exception handler. It is responsible for creating proper
 * output of low-level errors and module API violations.
 *
 */
class om_exception
{
	/**
	 * __construct
	 *
	 * The constructor. Sets some global exception type constants.
	 *
	 * @access public
	 */
	 public function __construct()
	 {
	 	define('EXCEPTION_UNKNOWN', 0);
	 	define('EXCEPTION_BAD_ENV', 1);
		define('EXCEPTION_BAD_CONFIG', 2);
	 }

	 /**
	  * handler
	  *
	  * This function is responsible for properly deciding how we output our
	  * exception to the user, if we output it at all.
	  *
	  * @access public
	  * @param object $data The exception object containing the data
	  * @param int $type A number representing an exception type
	  */
	  public function handler($data)
	  {
	  	echo '<pre>Uncought exception: ';
		var_dump($data);
		echo '</pre>';
	  }
}

?>
