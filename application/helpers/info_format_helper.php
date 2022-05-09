<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists("check_username_format"))
{
    function check_username_format($username){
        // username has to follow an email format
        if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        else {
            return false;
        }
    }
}

if (! function_exists("check_password_format"))
{
    function check_password_format($password){
        // 6 - 15 chars
        if (strlen($password) >= MIN_LENGTH_PASSWORD && strlen($password) <= MAX_LENGTH_PASSWORD){
            return true;
        }
        return false;
    }
}

if (! function_exists("check_nickname_format"))
{
    function check_nickname_format($nickname){
        // 5 - 20 chars
        if (strlen($nickname) >= MIN_LENGTH_NICKNAME && strlen($nickname) <= MAX_LENGTH_NICKANME){
            return true;
        }
        return false;
    }
}

if (! function_exists("check_video_title_format"))
{
    function check_video_title_format($title){
        if (strlen($title) >= MIN_LENGTH_VIDEO_TITLE && strlen($title) <= MAX_LENGTH_VIDEO_TITLE){
            return true;
        }
        return false;
    }
}

if (! function_exists("check_video_url_format"))
{
    function check_video_url_format($url){
        if (str_starts_with($url, '/oren') && (str_ends_with($url, 'mp4') || str_ends_with($url, 'avi'))){
            return true;
        }
        return false;
    }
}

if (! function_exists("check_video_tags_format"))
{
    function check_video_tags_format($tags){
        foreach ($tags as $tag){
            if (strlen($tag) < MIN_LENGTH_VIDEO_TAG || strlen($tag) > MAX_LENGTH_VIDEO_TAG){
                return false;
            }
            if (!ctype_alnum($tag)){
                return false;
            }
        }
        return true;
    }
}