<?php
/**
 * 连接数据库:https://codeigniter.org.cn/user_guide/database/connecting.html
 * 查询数据库:https://codeigniter.org.cn/user_guide/database/queries.html
 * 生成查询结果:https://codeigniter.org.cn/user_guide/database/results.html
 * 查询构造器类:https://codeigniter.org.cn/user_guide/database/query_builder.html
 */
class Model extends CI_Model {

    public function __construct(){
        $this->version = '18.8.25.2';
         
    }
    
    public function get_client_ip (){
        if (getenv('HTTP_CLIENT_IP')){
            $ip=getenv('HTTP_CLIENT_IP');
        }elseif (getenv('HTTP_X_FORWARDED_FOR')){
            $ip=getenv('HTTP_X_FORWARDED_FOR');
        }elseif (getenv('HTTP_X_FORWARDED')){
            $ip=getenv('HTTP_X_FORWARDED');
        }elseif (getenv('HTTP_FORWARDED_FOR')){
            $ip=getenv('HTTP_FORWARDED_FOR');
        }elseif (getenv('HTTP_FORWARDED')){
            $ip=getenv('HTTP_FORWARDED');
        }else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function get_mixcm_user($biao='uid'){
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