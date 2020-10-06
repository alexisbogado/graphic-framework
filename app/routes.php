<?php

/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

$routes->get('/', 'IndexController@index')->name('index');
$routes->get('/logout', 'AuthController@logout')->name('logout');
$routes->get('/home', 'IndexController@home')->name('home');

// API Routes
$routes->api('post', '/auth/signup', 'AuthController@signup');
$routes->api('post', '/auth/signin', 'AuthController@signin');
$routes->api('get', '/auth/user', 'AuthController@user');
// Examples
$routes->api('get', '/roles/{id?}', 'RolesController@get');
