<?php

class Ajax extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Model');
        $this->load->helper('url_helper');
    }

    public function signin() {
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Origin: https://account.mixcm.com/");
        if(!isset($_POST['password'])){
            show_404();
        }else{
            $this->load->model('Model_sign');
            if(!isset($_POST['username'])){
                echo $this->Model_sign->signin_mixcm_user(); 
            }else{
                echo $this->Model_sign->signup_mixcm_user(); 
            }
        }
    }

    public function sso() {
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Origin: https://account.mixcm.com/");
        if(isset($_POST['username']) and isset($_POST['c'])){
            setcookie("username", $_POST['username'], time()+3600*24*30, "/", ".mixcm.com");
            setcookie("c", $_POST['c'], time()+3600*24*30, "/", ".mixcm.com");
            echo 'true';
        }else{
            echo 'false';
        }
    }

    public function sso_user() {
        if(isset($_POST['biao'])){
            echo $this->Model->get_mixcm_user($_POST['biao']);
        }else{
            echo $this->Model->get_mixcm_user();
        }
    }

    public function sign_name() {
        $this->load->model('Model_sign');
        echo $this->Model_sign->sign_name_mixcm_user(); 
    }

    public function setting_home() {
        $this->load->model('Model_setting');
        echo $this->Model_setting->save_home($this->Model->get_mixcm_user()); 
    }

    public function setting_security() {
        $this->load->model('Model_setting');
        echo $this->Model_setting->save_security($this->Model->get_mixcm_user()); 
    }

    public function setting_page($page='home') {
        $data['page'] = $page;

        $a = array(
            'state' => '1',
            'url' => base_url('setting/'.$page),
            'content' => $this->load->view('setting/'.$page, $data, TRUE),
        );

        echo json_encode($a);
    }

}