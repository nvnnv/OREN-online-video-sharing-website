<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Model extends CI_Model {

    protected $table = 'video';

    public function add_a_video($title, $tags, $time, $url, $user_id, &$error)
    {
        // add a record in video
        try {
            $data['name'] = $title;
            $data['url'] = $url;
            $data['create_time'] = $time;
            $data['delete_flag'] = 0;
            $this->db->insert('video', $data);
            $video_id = $this->db->insert_id();
        } catch(Exception $e){
            $error = $this->db->error();
            return false;
        }
        // add a record in user_upload_video
        try {
            $data_rela['video_id'] = $video_id;
            $data_rela['user_id'] = $user_id;
            $this->db->insert('user_upload_video', $data_rela);
        } catch(Exception $e){
            $error = $this->db->error();
            return false;
        }
        // add multiple records in video_type
        try {
            $data_tags = array();
            foreach ($tags as $tag){
                array_push($data_tags, array('type'=>$tag, 'video_id'=>$video_id));
            }
            $this->db->insert_batch('video_type', $data_tags);
        } catch(Exception $e){
            $error = $this->db->error();
            return false;
        }
        return true;
    }
    public function get_latest_5_uploaded_videos(&$data, $user_id)
    {
        $query = $this->db->query("SELECT * FROM video WHERE id in (SELECT video_id FROM user_upload_video WHERE user_id='$user_id') ORDER BY create_time limit 5");
        $data['data'] = array();
        $i = 0;
        foreach ($query->result() as $row)
        {
            $k = 'row'.$i;
            $extension = pathinfo($row->url, PATHINFO_EXTENSION);
            $data['data'][$k] = array(
                'title' => $row->name,
                'url' => $row->url,
                'video_id' => $row->id,
                'upload_time' => $row->create_time,
                'extension' => $extension,
            );
            $i+=1;
        }
        return true;
    }

    public function get_count() {
        return $this->db->count_all($this->table);
    }

    public function get_videos($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_video_by_id($id){
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->result()[0];
    }

    public function update_views_by_id($id, $views){
        $data = [
            'views' => $views,
        ];
        $this->db->where('id', $id);
        $query = $this->db->update($this->table, $data);
    }

    public function get_batch_videos($ids){
        foreach($ids as $id){
            $this->db->or_where('id', $id);
        }
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_batch_videos_by_tag($tag, $per_page, $start){
        $query = $this->db->query("select * from video where id in (select video_id from video_type where type='$tag') limit $per_page offset $start");
        return $query->result();
    }

    public function get_videos_like_name($name, $per_page, $start){
        $query = $this->db->query("select * from video where name like '%$name%' limit $per_page offset $start");
        return $query->result();
    }

    public function get_videos_by_user_nickname($nickname, $per_page, $start) {
        $query = $this->db->query("select * from video where id in 
            (select video_id from user_upload_video where user_id in 
            (select id from users where nick_name='$nickname')) 
            limit $per_page offset $start");
        return $query->result();
    }
}