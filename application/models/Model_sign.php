<?php
/**
 * 连接数据库:https://codeigniter.org.cn/user_guide/database/connecting.html
 * 查询数据库:https://codeigniter.org.cn/user_guide/database/queries.html
 * 生成查询结果:https://codeigniter.org.cn/user_guide/database/results.html
 * 查询构造器类:https://codeigniter.org.cn/user_guide/database/query_builder.html
 */
class Model_sign extends CI_Model {

    public function __construct(){
        $this->load->database();
    }

    public function signin_mixcm_user(){

        $uid = $_POST['uid'];
        if($uid!=''){
            if($_POST['password']!=''){
                $mixcm = $this->load->database('mixcm',TRUE);
                $mixcm->where('uid = ', $uid);
                $mixcm->or_where('name = ', $uid);
                $mixcm->or_where('username = ', $uid);
                $mixcm->or_where("email_active=1 AND email='$uid'");
                $query = $mixcm->get('users');
                $row = $query->first_row('array');
                if(password_verify($_POST['password'],$row["password"])){
                    $a = array(
                        'state' => '1',
                        'notice' => '登录成功！',
                        'username' => $row['username'],
                        'c' => md5($row['username'].'mixcm520').md5($row['email'].'mixcm520'),
                    );
                }else{
                    $a = array(
                        'state' => '0',
                        'notice' => '密码错误！',
                    );
                }
            }else{
                $a = array(
                    'state' => '0',
                    'notice' => '请输入密码！',
                );
            }
        }else{
            $a = array(
                'state' => '0',
                'notice' => '请输入账号！',
            );
        }
        return json_encode($a);
    }

    public function signup_mixcm_user(){

        $a = array(
            'state' => '1',
            'notice' => '注册成功！',
        );
        
        /* 用户名规则 */
        $mixcm = $this->load->database('mixcm',TRUE);
        $mixcm->where(array(
            'username = ' => $_POST['username'],
        ));
        $mixcm->from('users');
        $conut = $mixcm->count_all_results();
        if($conut){
            $a['state'] = '0';
            $a['notice'] = '已经有人使用了此用户名！';
        }

        if(strlen($_POST['username']) < 4 or strlen($_POST['username']) > 11) {
            $a['state'] = '0';
            $a['notice'] = '请输入大于4字符，且小于11个字符的用户名！';
        }

        $name = array('root','admin','postmaster','master','webmaster','mixcm','administrator','sb','shabi');
        if(in_array($_POST['username'], $name)) {
            $a['state'] = '0';
            $a['notice'] = '非法用户名！';
        }

        if(!preg_match("/^[a-zA-Z\s]+$/", $_POST['username'])) { 
            $a['state'] = '0';
            $a['notice'] = '用户名必须为英文！';
        }

        /* 邮箱格式 */

        $mixcm->where(array(
            'email = ' => $_POST['email'],
            'email_active' => 1,

        ));
        $mixcm->from('users');
        $conut = $mixcm->count_all_results();
        if($conut){
            $a['state'] = '0';
            $a['notice'] = '已经有人绑定了此邮箱！';
        }

        $this->load->helper('email');
        if (!valid_email($_POST['email'])){
            $a = array(
                'state' => '0',
                'notice' => '邮箱格式不正确！',
            );
        }

        if($_POST['password']!=$_POST['password2']){
            $a['state'] = 0;
            $a['notice'] = '两次输入的密码不一致！';
        }
        
        $data = array(
            'password' => '密码',
            'password2' => '确认密码',
            'name' => '笔名',
            'qq' => 'QQ号码',
            'username' => '用户名',
            'email' => '邮箱地址',
        );

        for ($x=0; $x<=5; $x++) {
            if($_POST[array_keys($data)[$x]]==''){
                $a['state'] = 0;
                $a['notice'] = $data[array_keys($data)[$x]].'不能为空！';
                break;
            }
        }

        if($a['state'] == 1){
            $token = md5($_POST['name'].rand(1000000,9999999)).md5($_POST["username"].rand(1000000,9999999));
            $_POST['token'] = json_encode(array(
                'time' => time(),
                'content' => $token,
            ));
            $_POST['email_active'] = 0;
            $_POST['join_time'] = time();
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            unset($_POST['password2']);
            /* 发送邮件 */
            $this->load->library('email');

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.qq.com';
            $config['smtp_user'] = 'chainwon@qq.com';
            $config['smtp_pass'] = 'dvfveszppmczbdhj';
            $config['smtp_port'] = '465';
            $config['smtp_crypto'] = 'ssl';
            $config['validate'] = true;
            $config['newline'] = "\r\n";

            $this->email->initialize($config);
            $this->email->from('chainwon@qq.com', 'Mixcm Account');
            $this->email->to($_POST['email']);
            $this->email->subject('验证你的 Mixcm Account 账号');
            $this->email->message('https://account.mixcm.com/email-verify?username='.$_POST['username'].'&token='.$token);

            if($this->email->send()) {
                $a['notice']='已向你的邮箱 “'.$_POST['email'].'” 发送了一封邮件';
                $a['username'] = $_POST['username'];
                $a['c'] = md5($_POST['username'].'mixcm520').md5($_POST['email'].'mixcm520');
                $mixcm->insert('users', $_POST);
            } else {
                $a['state'] = 0;
                $a['notice']='注册失败，请检查你的邮箱！';
            }
        }

        return json_encode($a);
        
    }

    public function sign_name_mixcm_user(){

        if(!isset($_POST['uid'])){
            exit();
        }

        $uid = $_POST['uid'];
        if($uid!=''){
            $mixcm = $this->load->database('mixcm',TRUE);
            $mixcm->where('uid = ', $uid);
            $mixcm->or_where('name = ', $uid);
            $mixcm->or_where('username = ', $uid);
            $mixcm->or_where("email_active=1 AND email='$uid'");
            $query = $mixcm->get('users');
            $row = $query->first_row('array');
            if($row['uid']!=''){
                $a = array(
                    'state' => '1',
                    'avatar' => 'https://avatar.mixcm.cn/qq/'.$row['qq'].'?s=0',
                );
                if($row["background"]==""){
                    $a['background'] = 'https://i.loli.net/2017/11/18/5a101cf51b693.jpg';
                }else{
                    $a['background'] = preg_replace('#http:#','https:',$row["background"]);
                }
            }else{
                $a = array(
                    'state' => '2',
                    'notice' => '该账号不存在！正在为您注册账号！',
                );
            }
        }else{
            $a = array(
                'state' => '0',
                'notice' => '请输入账号！',
            );
        }
        return json_encode($a);
    }

}