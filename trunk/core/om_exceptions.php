<?php

/**
 * @package OpenMonday
 * @version $Id$
 * @author OpenMonday Development Group
 * @copyright Copyright 2005 OpenMonday Development Group
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @link http://openmonday.martlev.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

//
// Make sure this file is included by a valid entry point
//
if (!defined('OPENMONDAY'))
{
	exit;
}

/**
 * Handles exceptions
 *
 * This will try to output the exceptions called in a proper way
 */
final class om_exceptions
{
	/**
	 * The constructor
	 *
	 * Sets the exception handler, and defines exception type constants.
	 *
	 * @access public
	 */
	public function __construct()
	{
		//
		// Let's define the different types of exceptions
		//
		define('OM_EXCEPTION_CONFIG', 1);
		define('OM_EXCEPTION_MODULE', 2);
		define('OM_EXCEPTION_EVENT', 3);

		//
		// Let's set the exception handler to ours
		//
		set_exception_handler(array(&$this, 'handler'));
	}

	/**
	 * The actual handler
	 *
	 * This takes the exception object and outputs an error containing the data
	 * of the exception in proper format.
	 *
	 * @access public
	 * @param object $exception The exception object
	 */
	public function handler(&$exception)
	{
		print('<pre>');
		var_dump($exception);
		print('</pre>');
	}
}

?>
