     
        
        <div class="mixcm-item mixcm-user">
            <div class="mixcm-background" style="background-image:url(<?=$this->Model->get_mixcm_user('background');?>);"></div>
            <div class="mixcm-avatar"><img src="<?=$this->Model->get_mixcm_user('avatar');?>"></div>
            <div class="mixcm-info">
                <h1><?=$this->Model->get_mixcm_user('name'); ?> LV<?=$this->Model->get_mixcm_user('grade');?>（<?=$this->Model->get_mixcm_user('experience');?>/<?=$this->Model->get_mixcm_user('next_experience');?>）</h1>
                <p><?=$this->Model->get_mixcm_user('bio'); ?></p>
            </div>
        </div>
        <form class="mixcm-setting">
            <h2>笔名（拥有用户名后刷新可修改）</h2>
            <input name="name" value="<?=$this->Model->get_mixcm_user('name'); ?>"<?php if($this->Model->get_mixcm_user('username')==''){echo ' readonly="readonly"';} ?>>
            <h2>用户名（用于登录，设置后每日登录获得经验翻倍）</h2>
            <input name="username" value="<?=$this->Model->get_mixcm_user('username'); ?>"<?php if($this->Model->get_mixcm_user('username')!=''){echo ' readonly="readonly"';} ?>>
            <h2>个性签名</h2>
            <textarea name="bio"><?=$this->Model->get_mixcm_user('bio'); ?></textarea>
            <h2>QQ</h2>
            <input name="qq" value="<?=$this->Model->get_mixcm_user('qq'); ?>">
            <h2>性别</h2>
            <select name="sex" mdui-select>
                <option <?php if($this->Model->get_mixcm_user('sex') == '秀吉'){echo 'selected="selected"';} ?>>秀吉</option>
                <option <?php if($this->Model->get_mixcm_user('sex') == '汉纸'){echo 'selected="selected"';} ?>>汉纸</option>
                <option <?php if($this->Model->get_mixcm_user('sex') == '妹纸'){echo 'selected="selected"';} ?>>妹纸</option>
            </select>
            <h2>背景图片地址（图床：<a href="https://sm.ms/" target="_blank">https://sm.ms/</a>）</h2>
            <input name="background" value="<?=$this->Model->get_mixcm_user('background'); ?>">
    
            <button class="mdui-btn mdui-ripple mdui-ripple-white mdui-center" onclick="setting_form('<?=$page;?>');" name="save" type="button">保存设置</button>
        </form>
