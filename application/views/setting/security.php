      

        <form class="mixcm-setting">
            <h2>邮箱</h2>
            <input <?php
            if($this->Model->get_mixcm_user('email_active') == 1){
                echo 'class="yes"';
            }else{
                echo 'class="no" mdui-tooltip="{content: \'未绑定\'}"';
            }
            ?> name="email" type="email" value="<?=$this->Model->get_mixcm_user('email');?>">
            <?php
            if($this->Model->get_mixcm_user('email_active') == 1){
                echo '<i class="mdui-icon material-icons yes" mdui-tooltip="{content: \'已绑定\'}">check_circle</i>';
            }else{
                echo '<i class="mdui-icon material-icons no">cancel</i>';
            }
            ?>
            <button class="mdui-btn mdui-ripple mdui-ripple-white mdui-center" onclick="setting_form('<?=$page;?>');" name="save" type="button">保存设置</button>
        </form>

