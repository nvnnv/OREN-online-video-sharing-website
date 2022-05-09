<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("User_Model");
		$this->load->model("Video_Model");
		$this->load->helper('url');
		$this->load->helper("info_format_helper"); // new helper has to end with 'helper'
	}

	public function go_to_signup_page()
	{
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view("signup");
		$this->load->view('footer');
	}

	public function go_to_login_page()
	{
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view("login");
		$this->load->view('footer');
	}

	public function go_to_info_panel()
	{
		if (!isset($_SESSION['login']))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('index');
			$this->load->view('footer');
			return;
		}
		$data = array();
		$this->Video_Model->get_latest_5_uploaded_videos($data, $_SESSION['user_id']);
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view('user_account_page', $data);
		$this->load->view('footer');
	}

	public function go_to_change_pro_pic()
	{
		if (!isset($_SESSION['login']))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('index');
			$this->load->view('footer');
			return;
		}
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view('change_profile_pic');
		$this->load->view('footer');
	}

	public function go_to_change_password()
	{
		if (!isset($_SESSION['login']))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('index');
			$this->load->view('footer');
			return;
		}
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view('change_password');
		$this->load->view('footer');
	}

	public function change_pro_pic()
	{
		if (!isset($_SESSION['login']))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('login');
			$this->load->view('footer');
			return;
		}
		$data = array(
			'error' => null,
		);
		if (!array_key_exists('img_select', $_FILES))
		{
			$data['error'] = 'You have to select a pic!';
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_profile_pic', $data);
			$this->load->view('footer');
			return;
		}
		if($_FILES['img_select']['size'] > 5242880) { //5 MB (size is also in bytes)
			$data['error'] = 'file size has to be <= 5mb';
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_profile_pic', $data);
			$this->load->view('footer');
			return;
		}
		/*
			$_FILES
			'img_select' => array(
				'size' => 23453,
				'name' => vide15.png,
				'tmp_name' => binary data
			)
		*/
		$target_file = $_FILES["img_select"]["name"];
		$image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		$target_path = FCPATH.'upload/';
		$target_file = $_SESSION['username'].uniqid().'.'.$image_file_type;
		if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
			$data['error'] = 'The file type has to be jpg, png, or jpeg.';
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_profile_pic', $data);
			$this->load->view('footer');
			return;
		}
		try {
			$error = null;
			move_uploaded_file($_FILES["img_select"]["tmp_name"], $target_path.$target_file);
			if ($this->User_Model->modify_pro_pic_by_id($_SESSION['user_id'], '/oren/upload/'.$target_file, $error));
		} catch (Exception $e) {
			$data['error'] = 'Storing going wrong.';
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_profile_pic', $data);
			$this->load->view('footer');
			return;
		}
		$data['error'] = 'Modified Successfully!';
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view('change_profile_pic', $data);
		$this->load->view('footer');
		return;	
	}

	public function change_password()
	{
		if (!isset($_SESSION['login']))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('login');
			$this->load->view('footer');
			return;
		}
		$old_p = $_POST['old_password'];
		$new_p = $_POST['new_password'];
		$c_new_p = $_POST['confirm_n_password'];
		$data = array(
			'error' => null,
		);
		if ($old_p === $new_p)
		{
			$data['error'] = 'Please new password has to be different with old one.';
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_password', $data);
			$this->load->view('footer');
			return;
		}
		if ($new_p !== $c_new_p)
		{
			$data['error'] = 'It is inconsistent betwen new password and confirm new password.';
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_password', $data);
			$this->load->view('footer');
			return;
		}
		if (!check_password_format($old_p) || !check_password_format($new_p))
		{
			$min_length_password = MIN_LENGTH_PASSWORD;
			$max_length_password = MAX_LENGTH_PASSWORD;
			$data['error'] = "password has to have $min_length_password - $max_length_password";
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_password', $data);
			$this->load->view('footer');
			return;
		}
		$error = null;
		if ($this->User_Model->change_password_by_id($_SESSION['user_id'], $old_p, $new_p, $error))
		{
			$data['error'] = "Successfully modified the password!";
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('change_password', $data);
			$this->load->view('footer');
			return;
		}
		$data['error'] = $error;
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view('change_password', $data);
		$this->load->view('footer');
		return;
	}
	
	public function login()
	{	
		// if user already logged in
		if (isset($_SESSION['login']))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('index');
			$this->load->view('footer');
			return;
		}
		$username = $_POST["username"];
		$password = $_POST["password"];
		$data = array(
			'username' => $username,
			'error' => 'wrong username or password',
		);
		if (is_null($username) || is_null($password))
		{
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view("login", $data);
			$this->load->view('footer');
			return;
		}
		$error = "";
		$user_data = [];
		if ($this->User_Model->get_a_user($username, $password, $user_data, $error))
		{	
			$user_data['password'] = '';
			$this->session->set_userdata($user_data);// unpackage
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('index');
			$this->load->view('footer');
			return;
		}
		$data['error'] = $error;
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view("login", $data);
		$this->load->view('footer');
	}

	public function logout()
	{
		// user logout
		unset(
			$_SESSION['username'],
			$_SESSION['login'],
			$_SESSION['user_id'],
			$_SESSION['password'],
			$_SESSION['nick_name'],
			$_SESSION['user_img'],
			$_SESSION['create_time'],
		);
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view("login");
		$this->load->view('footer');
	}

	public function signup()
	{	
		$username = $_POST["username"];
		$password = $_POST["password"];
		$confirm_password = $_POST["confirm_password"];
		$nickname = $_POST["nickname"]; // repeated nicknames are permitted
		$data = array(
			'username' => $username,
			'nickname' => $nickname,
			'error' => null,
		);
		if ($confirm_password !== $password)
		{
			$data['error'] = "password not consistent";
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('signup', $data);
			$this->load->view('footer');
			return;
		}
		if (!check_nickname_format($nickname)) 
		{
			$min_length_nickname = MIN_LENGTH_NICKNAME;
			$max_length_nickname = MAX_LENGTH_NICKANME;
			$data['error'] = "nickname has to have $min_length_nickname - $max_length_nickname chars";
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('signup', $data);
			$this->load->view('footer');
			return;
		}
		if (!check_password_format($password))
		{
			$min_length_password = MIN_LENGTH_PASSWORD;
			$max_length_password = MAX_LENGTH_PASSWORD;
			$data['error'] = "password has to have $min_length_password - $max_length_password chars";
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('signup', $data);
			$this->load->view('footer');
			return;
		}
		if (!check_username_format($username))
		{
			$data['error'] = "username has to follow an email format.";
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('signup', $data);
			$this->load->view('footer');
			return;
		}
		$error = '';
		if (!$this->User_Model->add_a_user($username, $password, $nickname, $error)) {
			$data['error'] = $error;
			$this->load->view('style_sheet');
			$this->load->view('header');
			$this->load->view('signup', $data);
			$this->load->view('footer');
			return;
		}
		$this->load->view('style_sheet');
		$this->load->view('header');
		$this->load->view("login", $data);
		$this->load->view('footer');
	}

	public function get_user_info_json()
    {
        $video_id = $_POST['id'];
		if (is_null($video_id))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Id is null!',
			)));
		}

		$error = '';
		$data = array();
		if ($this->User_Model->get_user_by_video_id($video_id, $error, $data))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(array(
				'err_code' => '200',
				'error' => "OK.",
				'user' => $data,
			)));
		}
		return $this->output->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
            'err_code' => '400',
            'error' => $error,
        )));
    }
}
?>