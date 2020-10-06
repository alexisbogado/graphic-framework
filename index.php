<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

date_default_timezone_set('Europe/Madrid');

require_once __DIR__ . '/app/helpers.php';

session_start();
load_app();

echo $routes->render($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
