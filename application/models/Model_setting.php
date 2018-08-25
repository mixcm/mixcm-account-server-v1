<?php

class Model_setting extends CI_Model {

    public function __construct(){
        $this->load->database();
    }
    
    public function save_home($uid){

        if(!isset($_POST['username'])){
            return json_encode(array(
                'state' => '0',
                'notice' => '用户名格式不正确！',
            ));
            exit();
        }

        $mixcm = $this->load->database('mixcm',TRUE);

        $a = array(
            'state' => '1',
            'notice' => '保存成功！',
        );

        $mixcm->where('uid = ', $uid);
        $query = $mixcm->get('users');
        $row = $query->first_row('array');
        if($row['username']!='' and $_POST['username']!=$row['username']){
            $a['state'] = '0';
            $a['notice'] = '用户名不可修改！';
        }
        
        /* 用户名规则 */
        $mixcm->where(array(
            'username = ' => $_POST['username'],
            'uid != ' => $uid,
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

        if($row['name']!=$_POST['name']){
            if($row['username']!=''){
                $a['state'] = '2';
                $a['notice'] = '用户名修改成功！你需要重新登录账号！';
            }else{
                $a['state'] = '0';
                $a['notice'] = '必须拥有用户名才能修改笔名！';
            }
        }

        if($a['state'] == '1' or $a['state'] == '2'){
            $data = json_decode(strip_tags(json_encode($_POST)));
            $mixcm->where('uid = ', $uid);
            $mixcm->update('users', $data); 
        }

        return json_encode($a);
    }

    public function save_security($uid){ 

        $a = array(
            'state' => '1',
            'notice' => '保存成功！',
        );

        if(!isset($_POST['email'])){
            $a['state']=0;
            $a['notice']='邮箱格式不正确！';
            return json_encode($a);
            exit();
        }

        $mixcm = $this->load->database('mixcm',TRUE);

        $mixcm->where('uid = ', $uid);
        $query = $mixcm->get('users');
        $row = $query->first_row('array');

        $this->load->helper('email');
        if (!valid_email($_POST['email'])){
            $a = array(
                'state' => '0',
                'notice' => '邮箱格式不正确！',
            );
        }

        if(($row['email_active']==0 or $row['email']!=$_POST['email']) and $a['state']==1){
            $token=json_decode($row['token'],true);
            if($token['content']=='' or !isset($token['content']) or (time()-$token['time'])>3600*24){
                $token=array(
                    'time' => time(),
                    'content' => md5($row['name'].rand(1000000,9999999)).md5($row["username"].rand(1000000,9999999)),
                );
            }
            
            $this->load->library('email');

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.qq.com';
            $config['smtp_user'] = 'chainwon@qq.com';
            $config['smtp_pass'] = 'cmkoijnecijjbdbb';
            $config['smtp_port'] = '465';
            $config['smtp_crypto'] = 'ssl';
            $config['validate'] = true;
            $config['newline'] = "\r\n";

            $this->email->initialize($config);
            $this->email->from('chainwon@qq.com', 'Mixcm Account');
            $this->email->to($_POST['email']);
            $this->email->subject('验证你的 Mixcm Account 账号');
            $this->email->message('https://account.mixcm.com/email-verify?uid='.$row['uid'].'&token='.$token['content']);

            if ($this->email->send()) {
                $a['notice']='已向你的邮箱 “'.$_POST['email'].'” 发送了一封邮件';
            } else {
                $a['1']='failed';
            }

            $mixcm->where('uid = ', $uid);
            $mixcm->update('users', array(
                'email' => $_POST['email'],
                'email_active' => 0,
                'token' => json_encode($token),
            ));
        }

        return json_encode($a);
    }

    public function check_email(){

        if(!isset($_GET['uid']) and !isset($_GET['username'])){
            return '<i class="mdui-icon material-icons no">cancel</i> 验证失败！';
            exit();
        }

        $mixcm = $this->load->database('mixcm',TRUE);

        if(isset($_GET['uid'])){
            $mixcm->where('uid = ', $_GET['uid']);
        }elseif(isset($_GET['username'])){
            $mixcm->where('username = ', $_GET['username']);
        }
        
        $query = $mixcm->get('users');
        $row = $query->first_row('array');
        $token = json_decode($row['token'],true);
        if($token['content']==$_GET['token']){
            if((time()-$token['time'])<3600*24){
                if(isset($_GET['uid'])){
                    $mixcm->where('uid = ', $_GET['uid']);
                }elseif(isset($_GET['username'])){
                    $mixcm->where('username = ', $_GET['username']);
                }
                $mixcm->update('users', array(
                    'email' => $row['email'],
                    'email_active' => 1,
                ));
                return '<i class="mdui-icon material-icons yes">check_circle</i> 你的 Mixcm Account 已与邮箱绑定成功！';
            }else{
                return '<i class="mdui-icon material-icons no">cancel</i> Token 已失效，请重新发送验证邮件！';
            }
        }else{
            return '<i class="mdui-icon material-icons no">cancel</i> Token 不匹配，请重新发送验证邮件！';
        }
    }

}