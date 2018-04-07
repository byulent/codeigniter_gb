<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.08.17
 * Time: 6:54
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Gb extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gb_model');
        $this->lang->load('gb');
        $this->load->helper('language');
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('html', 'url'));
        $this->form_validation->set_error_delimiters('<li>','</li>');
    }

    public function index()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url().'gb';
        $config['total_rows'] = $this->db->count_all('gb');
        $config['per_page'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<nav aria-label="Page navigation" class="text-center"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2))? $this->uri->segment(2) : NULL;
        $p = ($page*$config['per_page'] == 0)? NULL : ($page-1)*$config['per_page'];
        $data['posts'] = $this->gb_model->get_posts($p,$config['per_page']);
        $this->load->view('guestbook', $data);
        //echo "Здесь будет гостевая книга на Codeigniter";
    }

    public function send()
    {
        $this->form_validation->set_rules('name', 'lang:name', 'required');
        $this->form_validation->set_rules('email', 'lang:email', 'required|valid_email');
        $this->form_validation->set_rules('message', 'lang:message', 'required|htmlspecialchars');

        if ($this->form_validation->run() == FALSE)
        {
            $data = array(
                'msg' => $this->alert('<p><strong>При отправке сообщения произошли следующие ошибки:</strong></p>
<ul>'.validation_errors().'</ul>'),
                'fields' => array()
            );
            foreach ($this->form_validation->error_array() as $key => $value) {
                $data['fields'][] = $key;
            }
            echo json_encode($data);
        }
        else
        {
            $this->gb_model->add_post();
            $post = $this->gb_model->get_posts('last');
            $data  = array(
                'msg' => $this->alert('Сообщение успешно отправлено','success'),
                'post' => '<div class="comment"><div class="row">
                        <h4 class="col-sm-6"><a href="mailto:'.$post['email'].'">'.$post['name'].'</a>&nbsp;ID: '.$post['id'].'
                        <span class="label label-info">Новый</span></h4><h4 class="text-right col-sm-6">'.date('d.m.Y H:i:s', strtotime($post['datetime'])).'</h4>
</div>
<div class="well">'.$post['message'].'</div></div>'
            );
            echo json_encode($data);
        }
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Имя', 'required');
        $this->form_validation->set_rules('password', 'Пароль', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $data = array(
                'msg' => $this->alert('<p><strong>При отправке сообщения произошли следующие ошибки:</strong></p>
<ul>'.validation_errors().'</ul>'),
                'fields' => array()
            );
            foreach ($this->form_validation->error_array() as $key => $value) {
                $data['fields'][] = $key;
            }
        }
        else
        {
            if ($this->gb_model->login() == TRUE) {
                $data = array(
                    'msg' => $this->alert('Вход успешен','success'),
                    'logged_in' => TRUE
                );
            }
            else
            {
                $data = array(
                    'msg' => $this->alert('Неверный логин или пароль'),
                    'fields' => array('username', 'password')
                );
            }
        }
        echo json_encode($data);
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        header('Location: '.base_url().'gb');
    }

    public function delete($id)
    {
        $this->gb_model->delete_post($id);
        $data = array(
            'msg' => $this->alert('Сообщение успешно удалено','success')
        );
        echo json_encode($data);
    }

    private function alert($message, $status = 'error')
    {
        $classes = array(
            'error' => 'danger',
            'success' => 'success'
        );
        return '<div class="alert alert-'.$classes[$status].' alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
'.$message.'</div>';
    }
}