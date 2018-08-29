<?php
/**
 * https://codeigniter.org.cn/user_guide/helpers/url_helper.html
 * 加载静态内容:https://codeigniter.org.cn/user_guide/tutorial/static_pages.html
 */
class Main extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Model');
        $this->load->helper('url_helper');
    }

    public function view($page='home'){
        $this->load->model('Model_sign');

        if (!file_exists(APPPATH.'views/pages/'.$page.'.php')){
            show_404();
        }
        
        $data['page'] = $page;

        $this->load->model('Model_setting');
        $data['notice'] = $this->Model_setting->check_email();
        
        $this->load->view('pages/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('pages/footer', $data);
    }

    public function setting($page='home'){
        /* if($this->Model->get_mixcm_user()==''){  
            echo '<script>window.location.href="'.base_url().'";</script>';
        }*/
        $this->load->model('Model_setting');
        
        if (!file_exists(APPPATH.'views/setting/'.$page.'.php')){
            show_404();
        }

        $data['page'] = $page;
        
        $this->load->view('setting/header', $data);
        $this->load->view('setting/'.$page, $data);
        $this->load->view('setting/footer', $data);
    }

}