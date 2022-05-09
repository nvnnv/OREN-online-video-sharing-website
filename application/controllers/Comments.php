<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model("Comment_Model");
		$this->load->model("User_Model");
	}

    public function get_comments(){
		$video_id = $_POST['video_id'];
		$per_page = $_POST['per_page'];
		$page = $_POST['page'];
		if (is_null($video_id) || is_null($per_page) || is_null($page))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Params null!',
			)));
		}
		$result_comments = $this->Comment_Model->get_comment_batch($video_id, $per_page, $per_page*$page);
		$user_ids = array();
		foreach ($result_comments as $comment){
			array_push($user_ids, $comment->user_id);
		}
		$result_users = $this->User_Model->get_batch_users($user_ids);
		$user_data = array();
		foreach ($result_users as $user){
			$user_data[$user->id] = array(
				'user_img' => $user->user_img,
				'nick_name' => $user->nick_name,
			);
		}
		$data = array();
		for ($i=0; $i<count($result_comments); $i++){
			array_push($data, array(
				'comment_id'=>$result_comments[$i]->id,
				'comment'=>$result_comments[$i]->comment,
				'create_time'=>$result_comments[$i]->create_time,
				'user_id'=>$result_comments[$i]->user_id,
				'user_img'=>$user_data[(string)$result_comments[$i]->user_id]['user_img'],
				'nickname'=>$user_data[(string)$result_comments[$i]->user_id]['nick_name'],
			));
		}
		return $this->output->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode(array(
			'err_code' => '200',
			'error' => 'OK.',
			'comments' => $data, 
		)));
	}

	public function add_comment(){
		$video_id = $_POST['video_id'];
		$user_id = $_POST['user_id'];
		$comment = $_POST['comment'];
		if (is_null($video_id) || is_null($user_id) || is_null($comment))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Params null!',
			)));
		}
		$this->Comment_Model->add_comment($video_id, $user_id, $comment, date('Y-m-d H:i:s'));
		return $this->output->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode(array(
			'err_code' => '200',
			'error' => 'OK.',
		)));
	}
}