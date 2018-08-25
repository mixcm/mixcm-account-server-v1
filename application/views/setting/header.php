<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>个人中心 - Mixcm</title>
    <meta name="description" content="">
    <meta name="keywords" content="Mixcm,自由之翼">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://mixcm.com/static/image/favicon.ico" rel="shortcut icon">
    <link href="https://cdnjs.loli.net/ajax/libs/mdui/0.4.0/css/mdui.min.css" rel="stylesheet">
    <link href="<?=base_url("static/css/setting.main.css?v=0712-1");?>" rel="stylesheet">
</head>

<body>

    <div id="mixcm-header">
        <div class="mixcm-container">
            <div class="mixcm-nav mdui-bottom-nav">
                <a data-type="home" class="mdui-ripple mdui-ripple-white<?php if($page=='home'){echo' mdui-bottom-nav-active';}?>">
                    <i class="mdui-icon material-icons">account_circle</i>
                    <label>个人中心</label>
                </a>
                <a data-type="security" class="mdui-ripple mdui-ripple-white<?php if($page=='security'){echo' mdui-bottom-nav-active';}?>">
                    <i class="mdui-icon material-icons">verified_user</i>
                    <label>账号安全</label>
                </a>
                <a data-type="record" class="mdui-ripple mdui-ripple-white<?php if($page=='record'){echo' mdui-bottom-nav-active';}?>">
                    <i class="mdui-icon material-icons">assignment</i>
                    <label>账号记录</label>
                </a>
            </div>
        </div>
        <div class="mixcm-background" style="background-image:url(<?=$this->Model->get_mixcm_user('background');?>);"></div>
    </div><!-- #mixcm-header -->

    <div id="mixcm-content">    