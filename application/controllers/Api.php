<?php

class Ajax extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Model');
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

    public function ssoUser($biaa='uid') {
        if(isset($_COOKIE['username'])){
            $username = $_COOKIE['username'];
            $mixcm = $this->load->database('mixcm',TRUE);
            $mixcm->where('username = ', $username);
            $query = $mixcm->get('users');
            $row = $query->first_row('array');
            if ($_COOKIE['c'] == md5($row['username'].'mixcm520').md5($row['email'].'mixcm520')){

                if(date('Y-m-d',$row['last_seen_time'])!=date('Y-m-d')){    
                    $experience=rand(6,10);
                    if($row['username']!=''){
                        $experience=$experience*2;
                    }elseif($row['email_active']==1){
                        $experience=$experience*2;
                    }
                    if(isset($_POST['ip'])){
                        $ip = $_POST['ip'];
                    }else{
                        $ip = $this->get_client_ip();
                    }
                    $data = array(
                        'last_seen_time' => time(),
                        'last_seen_ip' => $ip,
                        'experience' => $row['experience']+$experience,
                    );
                    $mixcm->where('username = ', $username);
                    $mixcm->update('users', $data);
                }

                if(floor(sqrt(sqrt(($row["experience"]))))>1){
                    $row['grade'] = floor(sqrt(($row["experience"]/10)));
                }else{
                    $row['grade'] = 1;
                }
                if($row["background"]==""){
                    $row['background'] = 'https://i.loli.net/2017/11/18/5a101cf51b693.jpg';
                }else{
                    $row['background'] = preg_replace('#http:#','https:',$row["background"]);
                }
                $row['avatar'] = 'https://avatar.mixcm.cn/qq/'.$row['qq'];
                $row['next_experience'] = pow($row['grade']+1,2)*10;
                
                if($biao=='*'){
                    return json_encode($row);
                }else{
                    return $row[$biao];
                }
            }
        }
    }

}