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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//首頁活動
$route['activity/view/(:num)'] = "activity/view/$1";

//攝影作品
$route['image/workphoto_category_view/(:num)'] = "image/workphoto_category_view/$1";
$route['image/upload_img/(:num)'] = "image/upload_img/$1";

//影片
$route['video/video_view/(:num)'] = "video/video_view/$1";

//包套內容
$route['combowe/combowe_category/(:num)'] = "combowe/combowe_category/$1";
$route['combowe/combowe_category_view/(:num)'] = "combowe/combowe_category_view/$1";

//新秘造型
$route['makeup/makeup_category_view/(:num)'] = "makeup/makeup_category_view/$1";
$route['makeup/upload_img/(:num)'] = "makeup/upload_img/$1";

//禮服分類
$route['dress/dress_main_view/(:num)'] = "dress/dress_main_view/$1";
$route['dress/dress_category_view/(:num)'] = "dress/dress_category_view/$1";
$route['dress/add_category/(:num)'] = "dress/add_category/$1";
$route['dress/upload_img/(:num)'] = "dress/upload_img/$1";

//Blog
$route['blog/view/(:num)'] = "blog/view/$1";

//Qa
$route['qa/view/(:num)'] = "qa/view/$1";

//關於我們
$route['staff/view/(:num)'] = "staff/view/$1";
