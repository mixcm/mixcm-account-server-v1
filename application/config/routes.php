<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'main/view';
$route['ajax/(:any)'] = 'ajax/$1';
$route['ajax/(:any)/(:any)'] = 'ajax/$1/$2';
$route['api/(:any)'] = 'api/$1';
$route['api/(:any)/(:any)'] = 'api/$1/$2';
$route['setting/(:any)'] = 'main/setting/$1';
$route['(:any)'] = 'main/view/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
