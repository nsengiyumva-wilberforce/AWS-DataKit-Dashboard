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
$route['default_controller'] = 'app';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;




$route['authenticate'] = 'app/authenticate';
$route['logout'] = 'app/logout';
$route['dashboard'] = 'app/dashboard';

$route['forms'] = 'app/forms';
$route['form/(:any)'] = 'app/form/$1';
$route['form-builder/(:any)'] = 'app/form_builder/$1';
$route['form/(:any)/edit'] = 'app/form_builder/$1';
$route['form/(:any)/settings'] = 'app/form_settings/$1';


$route['entries'] = 'app/entries';
$route['form-entries/(:any)'] = 'app/form_entries/$1';
$route['entry/(:any)'] = 'app/entry/$1';
$route['entry/(:any)/edit/(:any)'] = 'app/edit_entry/$1/$2';

$route['reports'] = 'app/reports';
$route['report/form/(:any)/data/aggregated'] = 'app/aggregated_report/$1';
$route['report/form/(:any)/data/(:any)'] = 'app/entries_report/$1/$2';

$route['insights'] = 'app/insights';


$route['ajax-entries-report/(:any)/(:any)/(:any)/(:any)'] = 'ajax/ajax_entries_report/$1/$2/$3/$4';

$route['maps'] = 'app/maps';
$route['map/(:any)'] = 'app/map/$1';


$route['mobile-users'] = 'app/mobile_users';
$route['mobile-user/report'] = 'app/user_report';
$route['mobile-user/add'] = 'app/add_mobile_user';
$route['mobile-user/(:any)'] = 'app/mobile_user/$1';
$route['mobile-user/(:any)/edit'] = 'app/edit_mobile_user/$1';
$route['mobile-user/(:any)/delete'] = 'app/delete_mobile_user/$1';
$route['data-form/add-mobile-user'] = 'form/add_mobile_user';
$route['data-form/edit-mobile-user/(:any)'] = 'form/edit_mobile_user/$1';



$route['dashboard-users'] = 'app/dashboard_users';
$route['dashboard-user/add'] = 'app/add_dashboard_user';
$route['dashboard-user/(:any)'] = 'app/dashboard_user/$1';
$route['dashboard-user/(:any)/edit'] = 'app/edit_dashboard_user/$1';
$route['dashboard-user/(:any)/delete'] = 'app/delete_dashboard_user/$1';
$route['data-form/add-dashboard-user'] = 'form/add_dashboard_user';
$route['data-form/edit-dashboard-user/(:any)'] = 'form/edit_dashboard_user/$1';



$route['admin-roles'] = 'app/admin_roles';
$route['settings'] = 'app/settings';


$route['regions'] = 'app/regions';
$route['districts'] = 'app/districts';
$route['sub-counties'] = 'app/sub_counties';
$route['parishes'] = 'app/parishes';
$route['villages'] = 'app/villages';
$route['projects'] = 'app/projects';
$route['organisations'] = 'app/organisations';
$route['question-library'] = 'app/question_library';


$route['add-region'] = 'app/add_region';
$route['add-district'] = 'app/add_district';
$route['add-sub-county'] = 'app/add_sub_county';
$route['add-parish'] = 'app/add_parish';
$route['add-village'] = 'app/add_village';
$route['add-project'] = 'app/add_project';
$route['add-organisation'] = 'app/add_organisation';
$route['add-chart'] = 'app/add_chart';
$route['add-indicator-chart'] = 'app/add_indicator_chart';


$route['region/(:any)/edit'] = 'app/edit_region/$1';
$route['district/(:any)/edit'] = 'app/edit_district/$1';
$route['sub-county/(:any)/edit'] = 'app/edit_sub_county/$1';
$route['parish/(:any)/edit'] = 'app/edit_parish/$1';
$route['village/(:any)/edit'] = 'app/edit_village/$1';
$route['project/(:any)/edit'] = 'app/edit_project/$1';
$route['organisation/(:any)/edit'] = 'app/edit_organisation/$1';
$route['chart/(:any)/edit'] = 'app/edit_chart/$1';
$route['indicator-chart/(:any)/edit'] = 'app/edit_indicator_chart/$1';


$route['region/(:any)/delete'] = 'ajax/delete_region/$1';
$route['district/(:any)/delete'] = 'ajax/delete_district/$1';
$route['sub-county/(:any)/delete'] = 'ajax/delete_sub_county/$1';
$route['parish/(:any)/delete'] = 'ajax/delete_parish/$1';
$route['village/(:any)/delete'] = 'ajax/delete_village/$1';
$route['project/(:any)/delete'] = 'ajax/delete_project/$1';
$route['organisation/(:any)/delete'] = 'ajax/delete_organisation/$1';
$route['entry/(:any)/delete'] = 'ajax/delete_response/$1';
$route['chart/(:any)/delete'] = 'ajax/delete_chart/$1';
$route['form/(:any)/delete'] = 'form/delete_form/$1';
$route['indicator-chart/(:any)/delete'] = 'ajax/delete_indicator_chart/$1';


$route['entry-followups/(:any)'] = 'app/entry_followups/$1';


// $route['data-form/add-region'] = 'form/add_region';
// $route['data-form/add-district'] = 'form/add_district';
// $route['data-form/add-sub-county'] = 'form/add_sub_county';
// $route['data-form/add-parish'] = 'form/add_parish';
// $route['data-form/add-village'] = 'form/add_village';


$route['data-form/edit-region'] = 'form/edit_region';
$route['data-form/edit-district'] = 'form/edit_district';
$route['data-form/edit-sub-county'] = 'form/edit_sub_county';
$route['data-form/edit-parish'] = 'form/edit_parish';
$route['data-form/edit-village'] = 'form/edit_village';
$route['data-form/edit-project'] = 'form/edit_project';
$route['data-form/edit-organisation'] = 'form/edit_organisation';
$route['data-form/edit-chart'] = 'form/edit_chart';
$route['data-form/edit-indicator-chart'] = 'form/edit_indicator_chart';


$route['create-region'] = 'form/add_region';
$route['create-district'] = 'form/add_district';
$route['create-sub-county'] = 'form/add_sub_county';
$route['create-parish'] = 'form/add_parish';
$route['create-village'] = 'form/add_village';
$route['create-project'] = 'form/add_project';
$route['create-organisation'] = 'form/add_organisation';
$route['create-chart'] = 'form/add_chart';
$route['create-indicator-chart'] = 'form/add_indicator_chart';
$route['create-conditional-logic'] = 'ajax/add_conditional_logic';


$route['update-region/(:any)'] = 'form/update_region/$1';
$route['update-district/(:any)'] = 'form/update_district/$1';
$route['update-sub-county/(:any)'] = 'form/update_sub_county/$1';
$route['update-parish/(:any)'] = 'form/update_parish/$1';
$route['update-village/(:any)'] = 'form/update_village/$1';
$route['update-project/(:any)'] = 'form/update_project/$1';
$route['update-organisation/(:any)'] = 'form/update_organisation/$1';
$route['update-chart/(:any)'] = 'form/update_chart/$1';
$route['update-indicator-chart/(:any)'] = 'form/update_indicator_chart/$1';


$route['sms'] = 'app/sms';

