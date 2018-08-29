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
            if (document.referrer != '') {
                window.location.href = document.referrer;
            } else {
                window.location.href = '/setting/home';
            }
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
            if (data.state == 1) {
                $('img').attr("src", data.avatar);
                $(".mixcm-bk").css("background-image", "url(" + data.background + ")");
            } else {
                $('img').attr("src", 'https://cdn.chainwon.com/img/sign.png');
                $(".mixcm-bk").css("background-image", "");
            }
        }
    })
});
$("button[name='signin']").click(function () {
    var html = $(this).html();
    $('button').prop("disabled", true);
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
            if (data.state == 1) {
                sso_mixcm('https://account.mixcm.com/ajax/sso', data.username, data.c);
                sso_mixcm('https://www.chainwon.com/ajax/sso', data.username, data.c);
            }
            $('button').prop("disabled", false);
            $('button').html(html);
        }
    })
});
$("button[name='signup']").click(function () {
    var html = $(this).html();
    $('button').prop("disabled", true);
    $('button').html('<div class="mdui-spinner"></div>');
    mdui.mutation();
    $.ajax({
        type: "POST",
        url: "/ajax/signup",
        data: $('form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            mdui.snackbar({
                message: data.notice,
                position: 'right-bottom'
            });
            if (data.state == 1) {
                sso_mixcm('https://account.mixcm.com/ajax/sso', data.username, data.c);
                sso_mixcm('https://www.chainwon.com/ajax/sso', data.username, data.c);
            }
            $('button').prop("disabled", false);
            $('button').html(html);
        }
    })
});

function alert(msg) {
    mdui.alert(msg);
}