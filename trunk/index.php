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
// This file is a valid entry point.
//
define('OPENMONDAY', TRUE);

//
// Set PHP configuration settings that can be set at runtime
//
ini_set('error_reporting', E_ALL);
ini_set('magic_quotes_runtime', '1');

//
// Load the configuration array
//
require_once('config.php');

//
// Load the core classes
//
require_once($om_config['path'] . $om_config['dir_core'] . $om_config['file_core_exceptions'] . $om_config['file_extension']);
require_once($om_config['path'] . $om_config['dir_core'] . $om_config['file_core_modules'] . $om_config['file_extension']);
require_once($om_config['path'] . $om_config['dir_core'] . $om_config['file_core_events'] . $om_config['file_extension']);

//
// Create the objects
//
$om_exceptions = new om_exceptions;

$om_modules = new om_modules;
$om_modules->load_om_config($om_config);

$om_events = new om_events;
$om_events->load_om_modules($om_modules);

//
// First set the default module, method and arguments
//
$module = $om_config['default_module'];
$method = $om_config['default_method'];
$arguments = $om_config['default_arguments'];

//
// Let's see if the REQUEST values tell us anything about which module
// we should load.
//
if (!empty($_REQUEST[$om_config['url_arg_module']]))
{
	$module = preg_replace('/[^0-9a-z_]/i', '', $_REQUEST[$om_config['url_arg_module']]);

	//
	// Let's see if we should load a specific method
	//
	if (!empty($_REQUEST[$om_config['url_arg_method']]))
	{
		$method = preg_replace('/[^0-9a-z_]/i', '', $_REQUEST[$om_config['url_arg_method']]);
		
		//
		// Let's pass all remaining REQUEST variables on as method arguments.
		//
		$arguments = &$_REQUEST;
	}
}

//
// Load the module and call the method
//
$om_modules->load_module($module);
$om_modules->module_method($module, $method, $arguments);

?>
