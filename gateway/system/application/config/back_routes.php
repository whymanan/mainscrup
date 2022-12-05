<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//For pages those have a static name
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['logout'] = 'authentication/logout';
$route['login'] = 'authentication/authentication/index';
$route['auth'] = 'authentication/authentication/index';
$route['auth/get_data'] = 'authentication/authentication/get_data';
$route['join'] = 'authentication/register';
$route['forget'] = 'authentication/resetView';
$route['verification'] = 'authentication/resetView';

// User group

$route['users'] = 'user/UserController/index';
$route['users/add'] = 'user/UserController/add';
$route['users/submit'] = 'user/UserController/submit';
$route['users/edit'] = 'user/UserController/edit';
$route['users/update'] = 'user/UserController/update';
$route['users/kyc'] = 'user/UserController/kyc';
$route['users/upload_profile'] = 'user/UserController/upload_profile';


// EAPS Route
$route['eaps'] = 'eaps/EapsController/index';
$route['smssend'] = 'eaps/EapsController/smssend';




$route['dashboard'] = 'admin/dashboard';


// Distributor
$route['menu'] = 'admin/menu/index';
$route['menu/add'] = 'admin/menu/add';
$route['menu/edit'] = 'admin/menu/edit';
$route['menu/submit'] = 'admin/menu/submit';

// Setting groups

$route['setting'] = 'admin/role/index';
$route['setting/add'] = 'setting/Setting/add';
$route['setting/submit'] = 'setting/Setting/submit';
$route['setting/edit'] = 'setting/Setting/edit';
$route['setting/get_tabs'] = 'setting/Setting/get_tabs';



// role
$route['role'] = 'admin/role/index';
$route['role/edit'] = 'admin/role/edit';
$route['role/submit'] = 'admin/role/submit';
$route['role/get_tabs'] = 'admin/role/get_tabs';
$route['role/update_role_permission'] = 'admin/role/update_role_permission';



$route['autocities'] = 'external/auto_cities';
$route['autostates'] = 'external/auto_states';
$route['cities'] = 'external/get_cities';
$route['states'] = 'external/get_states';
$route['ifscdata'] = 'external/datayuge';
$route['autovendor'] = 'external/get_vendor';
$route['autorole'] = 'external/get_role';
$route['vendorexist'] = 'external/get_vendor_exist';
$route['role_exist'] = 'external/get_role_exist';
$route['menuexist'] = 'external/get_menu_exist';
$route['submenuexist'] = 'external/get_submenu_exist';
$route['file_upload'] = 'file_upload/index';
$route['gallery_upload'] = 'file_upload/add_gallery';
