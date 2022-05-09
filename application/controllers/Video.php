<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {
    public function __construct()
	{
		parent::__construct();
        $models = array(
            'Video_Model' => 'vmodel',
            'History_Model' => 'hmodel',
            'Tags_Model' => 'tmodel',
         );
         $this->load->model($models);
		$this->load->helper('url');
        $this->load->helper("info_format_helper");
        $this->base_url = FCPATH.'upload';
	}

    public function upload_video()
    {
        if (!isset($_SESSION['login']))
        {
            $this->load->view('style_sheet');
            $this->load->view('header');
            $this->load->view("login");
            $this->load->view('footer');
            return;
        }
        $this->load->view("style_sheet");
        $this->load->view("header");
        $this->load->view("upload_edit");
        $this->load->view("footer");
    }

    public function upload_video_info_form()
    {
        if (!isset($_SESSION['login']))
        {
            $this->load->view('style_sheet');
            $this->load->view('header');
            $this->load->view("login");
            $this->load->view('footer');
            return;
        }
        if (!isset($_POST['title']) || !isset($_POST['tags']) || !isset($_POST['file_url']))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array(
                'err_code' => '400',
                'error' => "1 Params Error.",
            )));
        }
        $title = $_POST['title'];
        $tags = $_POST['tags'];
        $file_url = $_POST['file_url'];
        $user_id = $_SESSION['user_id'];
        // PS4#GAming#funny  
        // PS4
        // GAming
        // funny
        $tags = explode('#', $tags);
        // check param safety
        if (!check_video_tags_format($tags) || !check_video_url_format($file_url) || !check_video_title_format($title)){
            return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array(
                'err_code' => '400',
                'error' => '2 Params Error',
            )));
        }
        $error = '';
        if ($this->vmodel->add_a_video($title, $tags, date('Y-m-d H:i:s'), $file_url, $user_id, $error)){
            return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'err_code' => '200',
                'error' => "OK.",
            )));
        }
        return $this->output->set_content_type('application/json')
        ->set_status_header(400)
        ->set_output(json_encode(array(
            'err_code' => '400',
            'error' => $error,
        )));
    }

    public function upload()
    {   
        if (!isset($_SESSION['login'])){
            $this->load->view('style_sheet');
            $this->load->view('header');
            $this->load->view("login");
            $this->load->view('footer');
            return;
        }
        @set_time_limit(5 * 60);
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 120; // Temp file age in seconds
        if (!file_exists($this->base_url)) {
            @mkdir($this->base_url, 0777);
        }
        if (isset($_REQUEST["name"])) {
            $file_name = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $file_name = $_FILES["file"]["name"];
        } else {
            $file_name = uniqid("file_");
        }
        $file_name = uniqid().$file_name;
        $file_name = str_replace(' ', '', $file_name);
        $file_path = $this->base_url.'/'.$file_name;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        if ($cleanupTargetDir) {
            if (!is_dir($this->base_url) || !$dir = opendir($this->base_url)) {
                return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'err_code' => '400',
                    'error' => "Failed to open temp directory.",
                )));
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $this->base_url.'/'.$file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath === "{$file_path}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }	
        // Open temp file
        if (!$out = @fopen("{$file_path}.part", $chunks ? "ab" : "wb")) {
            return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array(
                'err_code' => '400',
                'error' => "Failed to open output stream.",
            )));
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'err_code' => '400',
                    'error' => "Failed to move uploaded file.",
                )));
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'err_code' => '400',
                    'error' => "Failed to open input stream.",
                )));
            }
        } else {	
            if (!$in = @fopen("php://input", "rb")) {
                return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'err_code' => '400',
                    'error' => "Failed to open input stream.",
                )));
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off 
            rename("{$file_path}.part", $file_path);
            return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'err_code' => '200',
                'error' => "Success!",
                'path' => '/oren/upload/'.$file_name,
            )));
        }

        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => "processing.",
        )));
    }

    public function uploaded_video_list()
    {
        $config = array();
        $config["base_url"] = base_url() . "video/uploaded_video_list";
        $config["total_rows"] = $this->vmodel->get_count();
        $config["per_page"] = 6;
        $config["uri_segment"] = 3;
        $config["first_url"] = base_url() . "video/uploaded_video_list/0";

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['videos'] = $this->vmodel->get_videos($config["per_page"], $page);
        $this->load->view('style_sheet');
        $this->load->view('header');
        $this->load->view("uploaded_video_list", $data);
        $this->load->view('footer');
    }

    public function get_video_batch()
    {
        $page = $_POST['page'];
        $per_page = $_POST['per_page'];
        if (is_null($page) || is_null($per_page))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'err_code' => '400',
                'error' => "Param Error",
            )));
        }
        $page = intval($page);
        $per_page = intval($per_page);
        $videos = $this->vmodel->get_videos($per_page, $per_page*$page);
        $data = array();
        foreach ($videos as $video)
        {
            array_push($data, array('id'=>$video->id, 'title'=>$video->name, 'url'=>$video->url, 'create_time'=>$video->create_time, 'views'=>$video->views));
        }

        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => "OK.",
            'videos' => $data,
        )));
    }

    public function video_details($id)
    {      
        $result = $this->vmodel->get_video_by_id($id);
        $data['id'] = $result->id;
        $data['title'] = $result->name;
        $data['url'] = $result->url;
        $data['create_time'] = $result->create_time;
        $data['views'] = $result->views;

        // update views
        $this->vmodel->update_views_by_id($id, intval($result->views)+1);

        // update user watch history
        $this->hmodel->add_record($id, $_SESSION['user_id'], date('Y-m-d H:i:s'));

        // load view
        $this->load->view('style_sheet');
        $this->load->view('header');
        $this->load->view("single_video_page", $data);
        $this->load->view('footer');
    }

    public function watched_history(){
        $config = array();
        $config["base_url"] = base_url() . "video/watched_history";
        $config["total_rows"] = $this->hmodel->get_history_count($_SESSION['user_id']);
        $config["per_page"] = 6;
        $config["uri_segment"] = 3;
        $config["first_url"] = base_url() . "video/watched_history/0";
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $result = $this->hmodel->get_watched_video_ids($_SESSION['user_id'], $config["per_page"], $page);
        foreach ($result as $res)
        {
            $ids[$res->video_id] = 1;
        }
        $video_ids = array();
        foreach ($ids as $key=>$val)
        {
            array_push($video_ids, $key);
        }
        $data['videos'] = $this->vmodel->get_batch_videos($video_ids);
        $this->load->view('style_sheet');
        $this->load->view('header');
        $this->load->view("watched_video_list", $data);
        $this->load->view('footer');
    }

    public function get_batch_videos_by_tag($tag)
    {
        $per_page = 10;
        $page = 0;
        $data['videos'] = $this->vmodel->get_batch_videos_by_tag($tag, $per_page, $per_page*$page);
        $data['tag'] = $tag;
        $this->load->view('style_sheet');
        $this->load->view('header');
        $this->load->view("tag_video_list", $data);
        $this->load->view('footer');
    }


    public function get_batch_videos_by_tag_json()
    {
        $page = $_POST['page'];
        $per_page = $_POST['per_page'];
        $tag = $_POST['tag'];
        if (is_null($page) || is_null($per_page) || is_null($tag))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array(
                'err_code' => '400',
                'error' => "Param Error",
            )));
        }
        $videos = $this->vmodel->get_batch_videos_by_tag($tag, $per_page, $per_page*$page);
        $data = array();
        foreach ($videos as $video)
        {
            array_push($data, array('id'=>$video->id, 'title'=>$video->name, 'url'=>$video->url, 'create_time'=>$video->create_time, 'views'=>$video->views));
        }
        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'err_code' => '200',
            'error' => "OK.",
            'videos' => $data,
        )));
    }

    public function search_videos()
    {

    }

    public function search_videos_json()
    {

    }
}