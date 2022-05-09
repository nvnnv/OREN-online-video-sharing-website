<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_Model extends CI_Model {

    protected $VIDEO_COMMENTS = 'video_comments';

    public function get_comment_batch($video_id, $per_page, $start){
        $this->db->where("video_id", $video_id);
        $this->db->limit($per_page, $start);
        $query = $this->db->get($this->VIDEO_COMMENTS);
        return $query->result();
    }

    public function add_comment($video_id, $user_id, $comment, $date){
        $data = [
            'video_id'=>$video_id,
            'user_id'=>$user_id,
            'comment'=>$comment,
            'create_time'=>$date,
        ];
        $this->db->insert($this->VIDEO_COMMENTS, $data);
    }
}