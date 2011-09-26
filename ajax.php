<?php

require_once 'config.php';

/**
 * Ajax Service Controller
 *
 * @author Nahuel Rosso
 * @package copperFramework
 * @version 1.2
 */
final class Ajax extends copperService {

}

new Ajax(&$_REQUEST['method'], &$_REQUEST['params']);