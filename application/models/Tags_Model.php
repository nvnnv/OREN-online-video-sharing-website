<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags_Model extends CI_Model {

    protected $VIDEO_TYPE = 'video_type';

    public function get_all_types()
    {
        $query = $this->db->get($this->VIDEO_TYPE);
        return $query->result();
    }

    public function get_types_video_id($id) {
        $this->db->where('video_id', $id);
        $query = $this->db->get($this->VIDEO_TYPE);
        return $query->result();
    }

    public function get_video_ids_by_tag($tag) {
        $this->db->where('type', $tag);
        $query = $this->db->get($this->VIDEO_TYPE);
        return $query->result();
    }
}