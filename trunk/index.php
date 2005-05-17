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

/**
 * This is a valid entry point
 */
define('OPENMONDAY', TRUE);

/**
 * Include configuration array
 */
require_once 'config.php';

/**
 * Start the application
 */
require_once $om_config['file_core_initiator'];
$om_initiator = new om_initiator;
$om_initiator->initiate($om_config);

?>
