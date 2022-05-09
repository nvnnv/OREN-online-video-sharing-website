<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Likes_Model extends CI_Model {

    protected $LIKES = 'video_user_likes';

    public function count_likes_dislikes($id) {
        $query_g = $this->db->query("select count(good) as good from video_user_likes where good=1 and video_id='$id'");
        $query_b = $this->db->query("select count(bad) as bad from video_user_likes where bad=1 and video_id='$id'");
        $data['good'] = $query_g->row_array()['good'];
        $data['bad'] = $query_b->row_array()['bad'];
        return $data;
    }

    public function like($video_id, $user_id){
        $data['video_id'] = $video_id;
        $data['user_id'] = $user_id;
        $data['good'] = 1;
        $this->db->insert($this->LIKES, $data);
    }

    public function dislike($video_id, $user_id){
        $data['video_id'] = $video_id;
        $data['user_id'] = $user_id;
        $data['bad'] = 1;
        $this->db->insert($this->LIKES, $data);
    }

    public function cancel_like_or_dislike($video_id, $user_id){
        $this->db->where('video_id', $video_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->LIKES);
    }

    public function get_like_dislike_user_id($video_id, $user_id){
        $this->db->where("video_id", $video_id);
        $this->db->where("user_id", $user_id);
        $query = $this->db->get($this->LIKES);
        if ($query->num_rows() === 1) {
            return $query->result()[0];
        }else{
            return null;
        }
    }
}