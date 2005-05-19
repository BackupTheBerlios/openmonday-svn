<?php

/**
 * File: $Id$
 *
 * Overall configuration file.
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
 * Define configuration array
 */
$om_config = array();

$om_config['path'] = './';

$om_config['dir_core'] = $om_config['path'] . 'core/';
$om_config['dir_modules'] = $om_config['path'] . 'modules/';

$om_config['file_extension'] = '.php';

$om_config['file_core_initiator'] = $om_config['dir_core'] . 'om_initiator' . $om_config['file_extension'];
$om_config['file_core_exception'] = $om_config['dir_core'] . 'om_exception' . $om_config['file_extension'];
$om_config['file_core_controller'] = $om_config['dir_core'] . 'om_controller' . $om_config['file_extension'];

$om_config['url_module'] = 'module';
$om_config['url_action'] = 'action';

$om_config['default_module'] = 'news';
$om_config['default_action'] = 'display';

?>
