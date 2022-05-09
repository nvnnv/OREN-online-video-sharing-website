<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_Model extends CI_Model {

    public function add_record($video_id, $user_id, $date){
        $data = [
            'video_id'=>$video_id,
            'user_id'=>$user_id,
            'create_time'=>$date,
        ];
        $this->db->insert("user_video_history", $data);
    }

    public function get_history_count($user_id)
    {
        $query = $this->db->query("select * from user_video_history where user_id='$user_id' group by video_id");
        return $query->num_rows();
    }

    public function get_watched_video_ids($user_id, $per_page, $start)
    {
        $this->db->where("user_id", $user_id);
        $this->db->group_by("video_id");
        $this->db->limit($per_page, $start);
        $query = $this->db->get("user_video_history");
        return $query->result();
    }
}