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

//
// Define configuration array
//
$om_config = array();

$om_config['path'] = './';

$om_config['dir_core'] = 'core/';
$om_config['dir_modules'] = 'modules/';

$om_config['file_extension'] = '.php';

$om_config['file_core_exceptions'] = 'om_exceptions';
$om_config['file_core_modules'] = 'om_modules';
$om_config['file_core_events'] = 'om_events';
$om_config['file_core_base_controller'] = 'om_base_controller';
$om_config['file_core_base_model'] = 'om_base_model';
$om_config['file_core_base_view'] = 'om_base_view';

$om_config['file_module_metadata'] = 'metadata';
$om_config['file_module_config'] = 'config';
$om_config['file_module_controller'] = 'controller';

$om_config['default_module'] = 'test';
$om_config['default_method'] = 'default';
$om_config['default_arguments'] = array();

$om_config['url_arg_module'] = 'module';
$om_config['url_arg_method'] = 'method';

?>
