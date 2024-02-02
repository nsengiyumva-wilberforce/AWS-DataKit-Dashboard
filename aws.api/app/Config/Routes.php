<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

$routes->get('migrator', 'Migrate::index');
$routes->get('forms', 'Form::index');
$routes->group('form', function($routes){
	$routes->add('add', 'Form::create');
	$routes->add('edit', 'Form::update');
	$routes->add('delete', 'Form::delete');
	$routes->add('update-question-order', 'Form::update_form_question_order');
	$routes->add('conditional-logic/add', 'Form::create_conditional_logic');
	$routes->add('conditional-logic/remove', 'Form::delete_conditional_logic');
});

$routes->get('questions', 'Question::index');
$routes->group('question', function($routes){
	$routes->add('add', 'Question::create');
	$routes->add('edit', 'Question::update');
	$routes->add('delete', 'Question::delete');
});

$routes->get('library-questions', 'Question_library::index');
$routes->group('library-question', function($routes){
	$routes->add('add', 'Question_library::create');
	$routes->add('edit', 'Question_library::update');
	$routes->add('delete', 'Question_library::delete');
});

$routes->get('entries', 'Entry::index');
$routes->group('entry', function($routes){
	$routes->add('form_entry_geodata', 'Entry:form_entry_geodata');
	$routes->add('add', 'Entry::create');
	$routes->add('add-followup', 'Entry::create_entry_followup');
	$routes->add('add-photo', 'Entry::create_last_entry_photo');
	$routes->add('add-photo-test', 'Entry::create_last_entry_photo_test');
	$routes->add('edit', 'Entry::update');
	$routes->add('reject-entry', 'Entry::reject_entry');
	$routes->add('rejected-entries', 'Entry::rejected_entries');
	$routes->add('update-rejected-entry', 'Entry::update_rejected_entry');
	$routes->add('update-rejected-entry-photo', 'Entry::rejected_photo_update');
	$routes->add('delete', 'Entry::delete');
	$routes->add('/', 'Entry::getEntry');
});

$routes->get('charts', 'Chart::index');
$routes->group('chart', function($routes){
	$routes->add('add', 'Chart::create');
	$routes->add('edit', 'Chart::update');
	$routes->add('delete', 'Chart::delete');
});


$routes->get('indicator-charts', 'Indicator_chart::index');
$routes->group('indicator-chart', function($routes){
	$routes->add('add', 'Indicator_chart::create');
	$routes->add('edit', 'Indicator_chart::update');
	$routes->add('delete', 'Indicator_chart::delete');
});

$routes->get('organisations', 'App_organisation::index');
$routes->group('organisation', function($routes){
	$routes->add('add', 'App_organisation::create');
	$routes->add('edit', 'App_organisation::update');
	$routes->add('delete', 'App_organisation::delete');
});

$routes->get('projects', 'App_project::index');
$routes->group('project', function($routes){
	$routes->add('add', 'App_project::create');
	$routes->add('edit', 'App_project::update');
	$routes->add('delete', 'App_project::delete');
});

$routes->get('regions', 'Region::index');
$routes->group('region', function($routes){
	$routes->add('add', 'Region::create');
	$routes->add('edit', 'Region::update');
	$routes->add('delete', 'Region::delete');
});

$routes->get('districts', 'App_district::index');
$routes->group('district', function($routes){
	$routes->add('add', 'App_district::create');
	$routes->add('edit', 'App_district::update');
	$routes->add('delete', 'App_district::delete');
});

$routes->get('sub-counties', 'App_sub_county::index');
$routes->group('sub-county', function($routes){
	$routes->add('add', 'App_sub_county::create');
	$routes->add('edit', 'App_sub_county::update');
	$routes->add('delete', 'App_sub_county::delete');
});

$routes->get('parishes', 'App_parish::index');
$routes->group('parish', function($routes){
	$routes->add('add', 'App_parish::create');
	$routes->add('edit', 'App_parish::update');
	$routes->add('delete', 'App_parish::delete');
});

$routes->get('villages', 'App_village::index');
$routes->group('village', function($routes){
	$routes->add('add', 'App_village::create');
	$routes->add('edit', 'App_village::update');
	$routes->add('delete', 'App_village::delete');
});


$routes->get('users', 'User::index');
$routes->group('user', function($routes){
	$routes->add('add', 'User::create');
	$routes->add('edit', 'User::update');
	$routes->add('delete', 'User::delete');
	$routes->add('authenticate', 'User::authenticate');
	$routes->add('change-password', 'User::change_password');
});

$routes->get('admin-users', 'Admin_user::index');
$routes->group('admin-user', function($routes){
	$routes->add('add', 'Admin_user::create');
	$routes->add('edit', 'Admin_user::update');
	$routes->add('delete', 'Admin_user::delete');
	$routes->add('authenticate', 'Admin_user::authenticate');
	$routes->add('change-password', 'Admin_user::change_password');
});



$routes->add('entry-geodata', 'Entry::form_entry_geodata');
$routes->add('entries/group-by-region', 'Entry::group_by_region');
$routes->add('entries/group-by-latrine-coverage', 'Entry::group_by_latrine_coverage');
$routes->add('entries/group_by_sanitation_category', 'Entry::group_by_sanitation_category');
$routes->add('entries/group-by-duration-of-water-collection', 'Entry::group_by_duration_of_water_collection');
$routes->add('entries/group-by-water-treatment', 'Entry::group_by_water_treatment');
$routes->add('entries/group-by-family-savings', 'Entry::group_by_family_savings');
$routes->add('entries/group-by-region-and-districts', 'Entry::group_by_region_and_districts');
$routes->add('form-entries', 'Entry::form_entries');
$routes->add('compiled-entry', 'Entry::compiled_entry');
$routes->add('report/entries', 'Entry::form_entries_report');
$routes->add('report/user-data-submission', 'User::data_submission');
$routes->add('aggregated-report/entries', 'Entry::form_entries_aggregated_report');





$routes->get('answer-types', 'Util::answer_type');
$routes->get('admin-roles', 'Util::admin_roles');
$routes->get('app-lists', 'Util::app_lists');
$routes->get('app-tables', 'Util::app_tables');
$routes->get('overview-counter', 'Util::overview_counter');
$routes->get('user-region-areas', 'Util::user_region_areas');
$routes->get('downloadable-region-entries', 'Entry::downloadable_region_entries');
$routes->post('add-library-question', 'Question::add_question_from_the_library');



