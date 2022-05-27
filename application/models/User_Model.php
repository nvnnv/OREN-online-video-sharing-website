<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model {

    private $DEFAULT_USER_IMG = "/oren/images/resources/vide8.png";

	public function add_a_user($username, $password, $nickname, &$error)
	{   
		$data["username"] = $username;
        $data["password"] = $password;
        $data["nick_name"] = $nickname;
        $data["user_img"] = $this->DEFAULT_USER_IMG;
        $data["create_time"] = date('Y-m-d H:i:s');
        try {
            $this->db->insert("users", $data);
        } catch (Exception $e){
            # put a record into log
            $error = $this->db->error();
            return false;
        }
        return true;
	}

    public function get_a_user($username, $password, &$user_data, &$error)
    {
        try {
            $query = $this->db->query("select * from users where username='$username' and password='$password'");
            if ($query->num_rows() > 0){
                $row = $query->row_array();
                $user_data['username'] = $row['username'];
                $user_data['password'] = '';
                $user_data['user_id'] = $row['id'];
                $user_data['nick_name'] = $row['nick_name'];
                $user_data['user_img'] = $row['user_img'];
                $user_data['create_time'] = $row['create_time'];
                $user_data['login'] = true;
                $user_data['kill'] = $row['kill'];
                return true;
            }
        } catch (Exception $e){
            $error = "DB Operation error.";
            return false;
        }
        $error = "wrong username or password.";
        return false;
    }

    public function change_password_by_id($user_id, $old_p, $new_p, &$error)
    {
        try {
            $query = $this->db->query("select * from users where id='$user_id'");
            $row = $query->row_array();
            if ($old_p !== $row['password']) {
                $error = 'Password is not correct';
                return false;
            }
            $data = [
                'password' => $new_p,
            ];
            $this->db->where('id', $user_id);
            $this->db->update('users', $data); 
        } catch (Exception $e){
            $error = "DB Operation error.";
            return false;
        }
        $error = null;
        return true;
    }

    public function modify_pro_pic_by_id($user_id, $img_url, &$error)
    {
        try {
            $data = [
                'user_img' => $img_url,
            ];
            $this->db->where('id', $user_id);
            $this->db->update('users', $data);
        } catch (Exception $e) {
            $error = "DB Operation error.";
            return false;
        }
        $error = null;
        return true;
    }

    public function get_user_by_video_id($video_id, &$error, &$data)
    {
        try {
            $query = $this->db->query("select * from users where id in (select user_id from user_upload_video where video_id='$video_id')");
            if ($query->num_rows() === 1)
            {
                $data['id'] = $query->row_array()['id'];
                $data['user_img'] = $query->row_array()['user_img'];
                $data['nick_name'] = $query->row_array()['nick_name'];
            }
        } catch(Exception $e) {
            $error = "DB Operation error.";
            return false;
        }
        $error = null;
        return true;
    }

    public function get_batch_users($ids){
        foreach($ids as $id){
            $this->db->or_where('id', $id);
        }
        $query = $this->db->get('users');
        return $query->result();
    }

    public function activate($username){
        $data = [
            'kill' => 0,
        ];
        $this->db->where('username', $username);
        $this->db->update('users', $data);
    }
}
