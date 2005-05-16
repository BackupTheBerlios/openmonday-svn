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

$om_config['dir'] = '';
$om_config['dir_core'] = $om_config['dir'] . 'core/';
$om_config['dir_modules'] = $om_config['dir'] . 'modules/';

?>
