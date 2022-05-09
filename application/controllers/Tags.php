<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model("Tags_Model");
		$this->load->helper('url');
	}

    public function get_all_tags_video()
    {
        $tags = $this->Tags_Model->get_all_types();
        $merged_tags = array();
        foreach ($tags as $tag)
        {
            if (!array_key_exists($tag->type, $merged_tags)){
                array_push($merged_tags, $tag->type);
            }
        }
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => "OK.",
            'tags' => $merged_tags,
        )));
    }

    public function get_tags_by_video_id(){
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
        $tags = $this->Tags_Model->get_types_video_id($video_id);
        $data = array();
        foreach ($tags as $tag)
        {
            array_push($data, $tag->type);
        }
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => 'OK.',
            'tags' => $data,
        )));
    }

    public function all_tags()
    {
        $tags = $this->Tags_Model->get_all_types();
        $merged_tags = array();
        foreach ($tags as $tag)
        {
            if (!array_key_exists($tag->type, $merged_tags)){
                array_push($merged_tags, $tag->type);
            }
        }
        $data['tags'] = $merged_tags;
        $this->load->view("style_sheet");
		$this->load->view('header');
		$this->load->view("tags", $data);
		$this->load->view('footer');
    }
}