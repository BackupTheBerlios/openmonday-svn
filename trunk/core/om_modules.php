<?php

/**
 * @package OpenMonday
 * @version $Id$
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
 * The Core Module Handler
 *
 * This class is responsible for loading modules and their dependencies, and
 * takes care of handling the different module APIs,
 */
final class om_modules
{
	/**
	 * @access private
	 * @var array Holds a reference to the configuration array
	 */
	private $om_config;

	/**
	 * @access private
	 * @var array Holds all data related to the modules, including its controller objects
	 */
	private $modules;

	/**
	 * The constructor
	 *
	 * Sets default values to class variables.
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->om_config = array();
		$this->modules = array();
	}
	
	/**
	 * Loads a module
	 *
	 * This function loads a module by including the module's metadata file, and
	 * the om_controller class. It then loads the module controller object in a
	 * local variable so it can be accessed later.
	 *
	 * @access public
	 * @param string $module The name of the module
	 */
	public function load_module($module)
	{
		//
		// We abort if the module is already loaded into memory
		//
		if ($this->is_module_loaded($module))
		{
			return;
		}

		//
		// If the module metadata file exists, we'll assume that the module exists
		//
		$module_file_metadata = $this->om_config['path'] . $this->om_config['dir_modules'] . $module . '/' . $this->om_config['file_module_metadata'] . $this->om_config['file_extension'];
		if (!is_file($module_file_metadata))
		{
			throw new Exception('The metadata file of the module "' . $module . '" does not exist.', OM_EXCEPTION_MODULE);
		}

		//
		// Let's load the module metadata into memory
		//
		require_once($module_file_metadata);

		//
		// Let's load those modules on which this module depends on
		//
		foreach ($this->modules[$module]['dependencies'] as $dependency)
		{
			$this->load_module($dependency);
		}

		//
		// Let's load the actual module controller class
		//
		require_once($this->om_config['path'] . $this->om_config['dir_core'] . $this->om_config['file_core_base_controller'] . $this->om_config['file_extension']);
		require_once($this->om_config['path'] . $this->om_config['dir_modules'] . $module . '/' . $this->om_config['file_module_controller'] . $this->om_config['file_extension']);

		//
		// Let's create the object.
		//
		$this->modules[$module]['controller'] = new om_controller;
	}

	/**
	 * Calls a module controller method
	 *
	 * This function takes a module and method name, and see if it is loaded into
	 * memory. It then calls the module controller's method and passes on the
	 * arguments provided. It returns whatever the module controller method
	 * returns.
	 *
	 * @access public
	 * @param string $module The name of the module
	 * @param string $method The name of the method
	 * @param array $arguments An optional array with arguments
	 * @return mixed Whatever the method returns
	 */
	public function module_method($module, $method, $arguments = array())
	{
		//
		// Throw an exception if the module or action do not exist
		//
		$this->is_module_loaded($module, FALSE);
		$this->is_method_supported($module, $method, FALSE);

		//
		// Let's prepare the arguments array so it fits the controller
		//
		$arguments = $this->prepare_arguments($arguments, FALSE);

		//
		// Let's pass the arguments on to the module API
		//
		return call_user_func_array(array(&$this->modules[$module]['controller'], $method), $arguments);
	}

	/**
	 * Prepares an argument array
	 *
	 * This function makes sure the arguments in the argument array passed on to
	 * load_module are in the correct place, depending on the method API. It
	 * takes an argument array containing the argument names as the keys, and
	 * returns an array with the same values, but with numerical keys and sorted
	 * as required by the respective module method.
	 *
	 * @access public
	 * @param string $module The name of the module
	 * @param string $method The name of the method
	 * @param array $arguments The array containing the arguments
	 * @param bool $check Should we check if the module/method exists before sorting?
	 * @return array The properly sorted and prepared argument array
	 */
	public function prepare_arguments($module, $method, $arguments, $check = TRUE)
	{
		//
		// Throw an exception if the module or action do not exist
		//
		if ($check === TRUE)
		{
			$this->is_module_loaded($module, FALSE);
			$this->is_method_supported($module, $method, FALSE);
		}

		//
		// Go through each possible argument of the method, and look up the a value
		// in the unprepared array with the same key as the name, and place it in
		// the new prepared array in proper order.
		//
		$prepared_arguments = array();
		foreach ($this->modules[$module]['api'][$method]['arguments'] as $argument_name)
		{
			if (isset($arguments[$argument_name]))
			{
				$prepared_arguments[] = $arguments[$argument_name];
			}
			else
			{
				$prepared_arguments[] = NULL;
			}
		}

		//
		// Return the new prepared array
		//
		return $prepared_arguments;
	}

	/**
	 * Checks if a module is loaded
	 *
	 * This function looks in the modules array for the given module.
	 *
	 * @access public
	 * @param string $module The name of the module
	 * @param bool $exception An exception will be thrown when the result is equal to the value of this variable.
	 * @return bool True if the module exists, false if not
	 */
	public function is_module_loaded($module, $exception = NULL)
	{
		//
		// See if the module exists in the modules array
		//
		if (!isset($this->modules[$module]))
		{
			//
			// Should we throw an exception when the module does not exist?
			//
			if ($exception === FALSE)
			{
				throw new Exception('The module "' . $module . '" is not loaded.', OM_EXCEPTION_MODULE);
			}
			return FALSE;
		}

		//
		// Should we throw an exception when the module does exist?
		//
		if ($exception === TRUE)
		{
			throw new Exception('The module "' . $module . '" is loaded.', OM_EXCEPTION_MODULE);
		}
		return TRUE;
	}

	/**
	 * Checks if a module supports a method
	 *
	 * This function looks in the registered API of the given module for the
	 * given method.
	 *
	 * @access public
	 * @param string $module The name of the module
	 * @param string $method The name of the method
	 * @param bool $exception An exception will be thrown when the result is equal to the value of this variable.
	 * @return bool True if the method is supported, false if not
	 */
	public function is_method_supported($module, $method, $exception = NULL)
	{
		//
		// See if the method exists in the module API
		//
		if (!isset($this->modules[$module]['api'][$method]))
		{
			//
			// Should we throw an exception when the method does not exist?
			//
			if ($exception === FALSE)
			{
				throw new Exception('The method "' . $method . '" is not implemented in module "' . $module . '".', OM_EXCEPTION_MODULE);
			}
			return FALSE;
		}

		//
		// Should we throw an exception when the method does exist?
		//
		if ($exception === TRUE)
		{
			throw new Exception('The method "' . $method . '" is implemented in module "' . $module . '".', OM_EXCEPTION_MODULE);
		}
		return TRUE;
	}

	/**
	 * Loads the configuration array
	 *
	 * This gives this object a reference to the configuration array, so we can
	 * access the data without much trouble.
	 *
	 * @access public
	 * @param array $om_config The configuration array
	 */
	public function load_om_config(&$om_config)
	{
		$this->om_config = $om_config;
	}
}

?>
