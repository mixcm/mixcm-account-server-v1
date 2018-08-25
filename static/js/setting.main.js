function setting_form() {
    var html = $('button').html();
    $('button').prop('disabled', true);
    $('button').html("<div class=\"mdui-spinner\"></div>");
    mdui.mutation();
    $.ajax({
        type: "POST",
        url: "/ajax/setting_"+$('button').attr("data-type"),
        data: $('form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            mdui.snackbar({
                message: data.notice,
                position: 'right-bottom'
            });
            $('button').prop('disabled', false);
            $('button').html(html); 
            if(data.state==2){
                setTimeout(function () {
                    window.location.href = '/';
                }, 1500);
            }
        }
    })
}

$('.mixcm-nav').children('a').click(function () {
    if(window.request != null){
        window.request.abort();
    }
    $('#mixcm-content').children('.mixcm-container').html("<div class=\"mdui-spinner mdui-spinner-colorful mdui-center\"></div>");
    mdui.mutation();
    window.request = $.ajax({
        type: "POST",
        url: "/ajax/setting_page/"+$(this).attr("data-type"),
        data: $('form').serialize(),
        success: function (data) {
            data = JSON.parse(data);
            history.pushState("", "", data.url);
            $('#mixcm-content').html(data.content);
            mdui.mutation();
            if (typeof ga !== 'undefined'){
                ga('send', 'pageview', location.pathname + location.search);
            }
        }
    })
});