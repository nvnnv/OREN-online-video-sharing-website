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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['user'] = 'welcome';
$route['go_to_signup_page'] = 'user/go_to_signup_page';
$route['go_to_login_page'] = 'user/go_to_login_page';
$route['signup'] = 'user/signup';
$route['login'] = 'user/login';
$route['logout'] = 'user/logout';
$route['video'] = 'video';
$route['upload_video'] = 'video/upload_video';
$route['upload'] = 'video/upload';
$route['upload_video_info_form'] = 'video/upload_video_info_form';
$route['go_to_info_panel'] = 'user/go_to_info_panel';
$route['go_to_change_pro_pic'] = 'user/go_to_change_pro_pic';
$route['go_to_change_password'] = 'user/go_to_change_password';
$route['change_pro_pic'] = 'user/change_pro_pic';
$route['change_password'] = 'user/change_password';
$route['uploaded_video_list'] = 'video/uploaded_video_list';
$route['watched_history'] = 'video/watched_history';
$route['get_all_tags_video'] = 'tags/get_all_tags_video';
$route['get_video_batch'] = 'video/get_video_batch';
$route['video_details/(:num)'] = 'video/video_details/$1';
$route['get_batch_videos_by_tag/(:num)'] = 'video/get_batch_videos_by_tag/$1';
$route['get_user_info_json'] = 'user/get_user_info_json';
$route['get_tags_by_video_id'] = 'tags/get_tags_by_video_id';
$route['all_tags'] = 'tags/all_tags';
$route['likes_and_dislikes'] = 'likes/likes_and_dislikes';
$route['like'] = 'likes/like';
$route['dislike'] = 'likes/dislike';
$route['cancel_like_or_dislike'] = 'likes/cancel_like_or_dislike';
$route['video_user_like_or_dislike'] = 'likes/video_user_like_or_dislike';
$route['get_comments'] = 'comments/get_comments';
$route['add_comment'] = 'comments/add_comment';
