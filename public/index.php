<?php
/**
 * This file contains the public/index.php file for project TCP-0001.
 *
 * File Information:
 * Project Name: TCP-0001
 * Module Name: Public
 * File Name: index.php
 * File Version: 1.0.0
 * Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Last Modified: 03/23/2023
 * File Authored on: 03/23/2023
 * File Copyright: 03/2023
 */

/**
 * Composer autoloader
 */
require '../vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(error_level: E_ALL);
set_error_handler(callback: 'Core\Error::errorHandler');
set_exception_handler(callback: 'Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();
$router->add(route: '', params: ['controller' => 'Home', 'action' => 'index']);
$router->add(route: '{controller}/{action}');
$router->add(route: '{controller}/{action}/{id:\d+}');
$router->add(route: '{controller}/{action}/{grp:\d+}/{id:\d+}');
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
}
