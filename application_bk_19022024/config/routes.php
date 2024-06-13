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
$route['default_controller'] = 'welcome';
$route['404_override'] = 'errors/error_404';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['db/sync'] = 'updates/db_sync';
$route['db/json'] = 'updates/db_json';
$route['db/upgrade'] = 'updates/db_upgrade';
$route['db/update'] = 'updates/db_update';
$route['db/install'] = 'updates/db_install';

$route['all_jobtypes/edit/(:num)'] = 'all_jobtypes/add/$1';

$route['all_jobtypes/delete/(:num)'] = 'all_jobtypes/delete/$1';

$route['wiki/edit/(:num)'] = 'wiki/add/$1';

$route['wiki/delete/(:num)'] = 'wiki/delete/$1';


$route['notice_board/edit/(:num)'] = 'notice_board/add/$1';

$route['notice_board/delete/(:num)'] = 'notice_board/delete/$1';


/*
| -------------------------------------------------------------------------
| REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/v1/(:any)'] = 'api_v1/$1';
$route['api/v1/invoices/id/(:num)'] = "api_v1/invoices/index/id/$1";

$route['api/v1/users/(:num)'] = 'api_v1/users/id/$1'; // Example 4
$route['api/v1/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api_v1/users/id/$1/format/$3$4'; // Example 8

$route['notes/(:num)']['PUT'] = 'notes/index/$1';
$route['notes/(:num)']['GET'] = 'notes';
$route['notes/(:num)']['DELETE'] = 'notes/index/$1';
$route['notes/(:num)']['POST'] = 'notes/index/';
// $route['notes'] = "notes/app";
$route['add_jobs'] = 'jobs/add';
$route['candidates'] = 'candidates/login';

$route['vacations/edit/(:num)'] = 'vacations/add/$1';

$route['vacations/delete/(:num)'] = 'vacations/delete/$1';

$route['annual_incentive_plans/edit/(:num)'] = 'annual_incentive_plans/add/$1';

$route['annual_incentive_plans/delete/(:num)'] = 'annual_incentive_plans/delete/$1';


$route['ad_knowledge'] = 'Ad_knowledge';
$route['ad_knowledge/ad_knowledge_view/(:num)'] = 'Ad_knowledge/ad_knowledge_view/$1';
$route['ad_knowledge/addKnowledge'] = 'Ad_knowledge/addKnowledge';
$route['ad_knowledge/addCategory'] = 'Ad_knowledge/addCategory';
$route['ad_knowledge/category_list'] = 'Ad_knowledge/category_list';
$route['ad_knowledge/add_like_details/(:num)/(:num)'] = 'Ad_knowledge/add_like_details/$1/$1';
$route['ad_knowledge/delete_knowledge'] = 'Ad_knowledge/delete_knowledge';
$route['ad_knowledge/add_comments'] = 'Ad_knowledge/add_comments';
$route['ad_knowledge/edit_category/(:num)'] = 'Ad_knowledge/edit_category/$1';
$route['ad_knowledge/delete_category/(:num)'] = 'Ad_knowledge/delete_category/$1';
$route['ad_knowledge/changeCategoryStatus/(:num)'] = 'Ad_knowledge/changeCategoryStatus/$1';
$route['ad_knowledge/check_categoryname'] = 'Ad_knowledge/check_categoryname';

$route['em_knowledge'] = 'Em_knowledge';
$route['em_knowledge/em_knowledge_view/(:num)'] = 'Em_knowledge/em_knowledge_view/$1';
$route['em_knowledge/add_like_details/(:num)/(:num)'] = 'Em_knowledge/add_like_details/$1/$1';
$route['em_knowledge/delete_knowledge'] = 'Em_knowledge/delete_knowledge';


//$route['api/v2/(:any)'] = "api_v2/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */