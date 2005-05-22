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
 * The Core Event Handler
 *
 * This class handlers the triggering and keeping of events.
 */
final class om_events
{
	/**
	 * @access private
	 * @var object Holds a reference to the om_modules object
	 */
	private $om_modules;

	/**
	 * @access private
	 * @var array Holds the event data
	 */
	private $events;

	/**
	 * The constructor
	 *
	 * Sets default values to class variables
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->om_modules = (object) NULL;
		$this->events = array();
	}

	/**
	 * Register a new event
	 *
	 * This function registers an event so it can be assigned module methods
	 * and triggered later.
	 *
	 * @access public
	 * @param string $event The unique name of the event.
	 */
	public function register_event($event)
	{
		//
		// Check if the event isn't registered already
		//
		$this->is_event_registered($event, FALSE);

		//
		// Register the event
		//
		$this->events[$event] = array();
	}
	
	/**
	 * Assigns a module method to an event
	 *
	 * This function assigns a module method and any arguments which should be
	 * passed on to the method to a specific event. When an event is triggered
	 * the method is called, and the arguments are passed on.
	 *
	 * @access public
	 * @param string $event The name of the event
	 * @param string $module The name of the module
	 * @param string $method The name of the method
	 * @param array $arguments An array containing the arguments which should be passed on to the method
	 */
	public function assign_method($event, $module, $method, $arguments = array())
	{
		//
		// Check if the event is registered
		//
		$this->is_event_registered($event, TRUE);

		//
		// Assign the module method to the event.
		//
		$this->events[$event][] = array('module' => $module, 'method' => $method, 'arguments' => $arguments);
	}

	/**
	 * Trigger an event
	 *
	 * This triggers an event and executes all methods assigned to it. It
	 * executes the methods in the same order as they were assigned in.
	 *
	 * @access public
	 * @param string $event The name of the event
	 */
	public function trigger_event($event)
	{
		//
		// Check if the event is registered
		//
		$this->is_event_registered($event, TRUE);

		//
		// Execute any methods assigned to this event.
		//
		foreach ($this->events[$event] as $method)
		{
			$this->om_modules->module_method($method['module'], $method['method'], $method['arguments']);
		}
	}

	/**
	 * Checks if an event is registered
	 *
	 * This function looks in the event array for the given event.
	 *
	 * @access public
	 * @param string $event The name of the event
	 * @param bool $exception An exception will be thrown when the result is equal to the value of this variable.
	 * @return bool True if the event is registered, false if not
	 */
	public function is_event_registered($event, $exception = NULL)
	{
		//
		// See if the event exists in the event array
		//
		if (!isset($this->events[$event]))
		{
			//
			// Should we throw an exception when the event does not exist?
			//
			if ($exception === FALSE)
			{
				throw new Exception('The event "' . $event . '" is not registered.', OM_EXCEPTION_EVENT);
			}
			return FALSE;
		}

		//
		// Should we throw an exception when the event does exist?
		//
		if ($exception === TRUE)
		{
			throw new Exception('The event "' . $event . '" is registered.', OM_EXCEPTION_EVENT);
		}
		return TRUE;
	}

	/**
	 * Loads the om_modules object
	 *
	 * This gives this object a reference to the om_modules object, so we
	 * can access the object without much trouble.
	 *
	 * @access public
	 * @param object $om_modules The om_modules object.
	 */
	public function load_om_modules(&$om_modules)
	{
		$this->om_modules = $om_modules;
	}
}

?>
