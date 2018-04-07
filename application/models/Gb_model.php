<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.08.17
 * Time: 17:23
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Gb_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_posts($id = NULL, $count = NULL)
    {
        if($id == 'last')
        {
            $query = $this->db->select()->order_by('id', 'DESC')->limit(1)->get('gb');
            return $query->row_array();
        }
        else if ($id != NULL) {
            if ($count != NULL) {
                $query = $this->db->get('gb',$count,$id);
            }
            else {
                $query = $this->db->get_where('gb',array('id' => $id));
            }
        } else {
            if ($count != NULL) {
                $query = $this->db->get('gb',$count);
            } else {
                $query = $this->db->get('gb');
            }
        }
        return $query->result_array();
    }

    public function add_post()
    {
        $post = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'message' => $this->input->post('message')
        );
        $this->db->insert('gb', $post);
    }

    public function delete_post($id)
    {
        $this->db->delete('gb', array('id' => $id));
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $query = $this->db->get_where('users',array('username' => $username, 'pass_hash' => md5($password)));
        $arr = $query->row_array();
        if(empty($arr)) {
            return FALSE;
        }
        else {
            $this->session->set_userdata('logged_in', TRUE);
            return TRUE;
        }
    }
}