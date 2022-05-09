<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Likes extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model("Likes_Model");
	}

    public function like() {
        $video_id = $_POST['video_id'];
        $user_id = $_POST['user_id'];
        if (is_null($video_id) || is_null($user_id))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Id is null!',
			)));
		}
        $this->Likes_Model->like($video_id, $user_id);
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => 'OK.',
        )));
    }

    public function cancel_like_or_dislike() {
        $video_id = $_POST['video_id'];
        $user_id = $_POST['user_id'];
        if (is_null($video_id) || is_null($user_id))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Id is null!',
			)));
		}
        $this->Likes_Model->cancel_like_or_dislike($video_id, $user_id);
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => 'OK.',
        )));
    }

    public function dislike() {
        $video_id = $_POST['video_id'];
        $user_id = $_POST['user_id'];
        if (is_null($video_id) || is_null($user_id))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Id is null!',
			)));
		}
        $this->Likes_Model->dislike($video_id, $user_id);
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => 'OK.',
        )));
    }

    public function likes_and_dislikes() {
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
        $data = $this->Likes_Model->count_likes_dislikes($video_id);
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => 'OK.',
            'likes' => $data['good'],
            'dislikes' => $data['bad'],
        )));
    }

    public function video_user_like_or_dislike() {
        $video_id = $_POST['video_id'];
        $user_id = $_POST['user_id'];
        if (is_null($video_id) || is_null($user_id))
		{
			return $this->output->set_content_type('application/json')
			->set_status_header(400)
			->set_output(json_encode(array(
				'err_code' => '400',
				'error' => 'Id is null!',
			)));
		}
        $result = $this->Likes_Model->get_like_dislike_user_id($video_id, $user_id);
        if (is_null($result))
        {
            return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(array(
				'err_code' => '200',
				'error' => 'OK.',
                'status' => '0',
			)));
        }
        if ($result->good === '1')
        {
            return $this->output->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(array(
				'err_code' => '200',
				'error' => 'OK.',
                'status' => '1',
			)));
        }
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => 'OK.',
            'status' => '2',
        )));
    }
}