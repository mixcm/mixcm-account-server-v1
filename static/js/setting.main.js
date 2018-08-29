function setting_form(type) {
    var html = $('button').html();
    $('button').prop('disabled', true);
    $('button').html("<div class=\"mdui-spinner\"></div>");
    mdui.mutation();
    $.ajax({
        type: "POST",
        url: "/ajax/setting_"+type,
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
    var id = $(this).attr('href');
    if($(id).length > 0) {
        if(window.request != null){
            window.request.abort();
        }
        window.request = $.ajax({
            type: "POST",
            url: "/account/index.php/ajax/setting_page_info/"+id.replace(/#/,""),
            data: $('form').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                history.pushState("", data.title, data.url);
                $('title').html(data.title)
                if (typeof ga !== 'undefined'){
                    ga('send', 'pageview', location.pathname + location.search);
                }
            }
        })
    }else{
        if(window.request != null){
            window.request.abort();
        }
        $('#mixcm-content').children('.mixcm-container').append('<div id="'+id.replace(/#/,"")+'"></div>');
        $(id).html("<div class=\"mdui-spinner mdui-spinner-colorful mdui-center\"></div>");
        mdui.mutation();
        window.request = $.ajax({
            type: "POST",
            url: "/account/index.php/ajax/setting_page/"+id.replace(/#/,""),
            data: $('form').serialize(),
            success: function (data) {
                data = JSON.parse(data);
                history.pushState("", data.title, data.url);
                $('title').html(data.title)
                $(id).html(data.content);
                mdui.mutation();
                if (typeof ga !== 'undefined'){
                    ga('send', 'pageview', location.pathname + location.search);
                }
            }
        })
    }
});