<?php

class AuthModel extends CI_Model {
    function check_login($email, $password){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('password',$password);
        // $this->db->status('status', 0);

        $query = $this->db->get();

        if($query->num_rows()>0){
            return $query->result_array();

        }
        else{
            return 'user not found';
        }
    }
    function signup($data){
        $this->db->insert('users', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return "User registered successfully";
        } else {
            $error = $this->db->error();
            return "Error Message: " . $error['message'];
        }
    }

    function getUsers() {
        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 'No users found';
        }
    }
    
}