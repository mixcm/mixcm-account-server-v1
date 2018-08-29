<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?=$this->Model_setting->get_title($page); ?> - Mixcm Account</title>
    <meta name="description" content="">
    <meta name="keywords" content="Mixcm,自由之翼">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://mixcm.com/static/image/favicon.ico" rel="shortcut icon">
    <link href="https://cdnjs.loli.net/ajax/libs/mdui/0.4.0/css/mdui.min.css" rel="stylesheet">
    <link href="<?=base_url("static/css/setting.main.css?v=0712-1");?>" rel="stylesheet">
</head>

<body>

    <div id="mixcm-header">
        <div class="mixcm-container" style="height: 100%;"> 
            <div class="mixcm-nav mdui-tab" mdui-tab>
                <a href="#home" class="mdui-ripple mdui-ripple-white<?php if($page=='home'){echo' mdui-tab-active';}?>">
                    个人信息
                </a>
                <a href="#security" class="mdui-ripple mdui-ripple-white<?php if($page=='security'){echo' mdui-tab-active';}?>">
                    账号安全
                </a>
                <a href="#record" class="mdui-ripple mdui-ripple-white<?php if($page=='record'){echo' mdui-tab-active';}?>">
                    账号记录
                </a>
            </div>
        </div>
        <div class="mixcm-background" style="background-image:url(<?=$this->Model->get_mixcm_user('background');?>);"></div>
    </div><!-- #mixcm-header -->

    <div id="mixcm-content">    
        <div class="mixcm-container mixcm-item">
            <div id="<?=$page?>">