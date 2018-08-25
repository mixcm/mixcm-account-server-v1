function sso_mixcm(url, username, c) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            'username': username,
            'c': c,
        },
        success: function (data) {
            console.log(data);
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true,
    })
}
$("input[name='uid']").blur(function () {
    $.ajax({
        type: "POST",
        url: "/ajax/sign_name",
        data: {
            'uid': $("input[name='uid']").val(),
        },
        success: function (data) {
            data = JSON.parse(data);
            if(data.state == 1){
                $('img').attr("src",data.avatar); 
                $(".mixcm-bk").css("background-image","url("+data.background+")");
                if($("input[name='password2']").length > 0) {
                    $("input[name='password2']").remove();
                    $("input[name='name']").remove();
                    $("input[name='qq']").remove();
                    $("input[name='username']").remove();
                    $("input[name='email']").remove();
                    $('form').prepend(`<input name="uid" type="text" placeholder="用户名 / 邮箱">`);
                }
              	$("button").html('立即登录');
            }else if(data.state == 2){
                $('img').attr("src",'https://cdn.chainwon.com/img/sign.png'); 
                $(".mixcm-bk").css("background-image","");
                mdui.snackbar({
                    message: data.notice,
                    position: 'right-bottom'
                });
                if($("input[name='password2']").length <= 0) {
                    $("input[name='uid']").remove();
                    $('input').after(`<input name="password2" type="password" placeholder="确认密码">
                    <input name="name" type="text" placeholder="笔名">
                    <input name="qq" type="text" placeholder="QQ号码">
                    <input name="username" type="text" placeholder="用户名">
                    <input name="email" type="email" placeholder="邮箱地址">`);
                  	$("button").html('立即注册');
                }    
            }else{
                $('img').attr("src",'https://cdn.chainwon.com/img/sign.png'); 
                $(".mixcm-bk").css("background-image","");
                mdui.snackbar({
                    message: data.notice,
                    position: 'right-bottom'
                });
            }
        }
    })
});
$('button').click(function() {
  	var html = $(this).html();
  	$('button').prop("disabled",true);
    $('button').html('<div class="mdui-spinner"></div>');
  	mdui.mutation();
    $.ajax({
        type: "POST",
        url: "/ajax/signin",
        data: $('form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            mdui.snackbar({
                message: data.notice,
                position: 'right-bottom'
            });
            $('button').prop("disabled",false);
            $('button').html(html);
            if (data.state == 1) {
                sso_mixcm('https://account.mixcm.com/ajax/sso', data.username, data.c);
                sso_mixcm('https://www.chainwon.com/ajax/sso', data.username, data.c);
                
                    if (document.referrer != '') {
                        window.location.href = document.referrer;
                    }else{
                        window.location.href = '/setting/home';
                    }
            }
        }
    })
});