<?php

/**
 * File: $Id$
 *
 * Our access point.
 *
 * @package OpenMonday
 * @subpackage Core
 * @copyright Copyright 2005 OpenMonday Development Group
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://openmonday.martlev.com
 *
 */

//
// This is a valid entry point
//
define('OPENMONDAY', TRUE);

//
// Set full error reporting and disable magic_quotes_runtime
//
ini_set('error_reporting', E_ALL);
ini_set('magic_quote_runtime', FALSE);

//
// Take precautions if our .htaccess file failed to disable register_globals
//
if (ini_get('register_globals'))
{
	foreach ($_REQUEST as $var_name)
	{
		unset(${$var_name});
	}
}

//
// Tell everybody magic quotes are enabled if .htaccess failed to disable it
//
if (ini_get('magic_quotes_gpc'))
{
	define('MAGIC_QUOTES_ENABLED', TRUE);
}

//
// Include config and required core classes
//
require_once('config.php');
require_once($om_config['dir_core'] . 'om_initiator.php');
require_once($om_config['dir_core'] . 'om_exception.php');
require_once($om_config['dir_core'] . 'om_hooks.php');
require_once($om_config['dir_core'] . 'om_event.php');
require_once($om_config['dir_core'] . 'om_dao.php');
require_once($om_config['dir_core'] . 'om_controller.php');
require_once($om_config['dir_core'] . 'om_model.php');
require_once($om_config['dir_core'] . 'om_view.php');

//
// Start the initiator
//
$om_initiator =& new om_initiator;
$om_initiator->initiate();

?>
