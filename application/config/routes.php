<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'main/view';
$route['email-verify'] = 'main/check_email';
$route['ajax/(:any)'] = 'ajax/$1';
$route['ajax/(:any)/(:any)'] = 'ajax/$1/$2';
$route['setting/(:any)'] = 'main/setting/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
