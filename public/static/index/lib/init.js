var t = Date.now();

jQuery.kingAjax = function(obj, successFuc, failFuc){
    if (obj.isCommit) {
        return;
    }
    obj.isCommit = true;
    $.ajax({
        type: "POST",
        url: obj.url,
        data:  obj.data,
        async: true,
        error: function (request) {
            alertMsg('未知错误，刷新再试');
            obj.isCommit = false;
        },
        success: function (data) {
            var resData = typeof data.data == 'undefined' ? {} : data.data;
            if (data.code == 1) {
                alertMsg(data.msg, function(){
                    if(successFuc != null && typeof successFuc != 'undefined'){
                        successFuc(data);
                    }else if(resData.hasOwnProperty('url')){
                        window.location.href = resData.url;
                    }
                    obj.isCommit = false;
                });
            }else if (data.code == -1) {
                if(window.top==window.self){window.location.href = resData.url;}else{window.parent.location.href = resData.url;}
            } else {
                if(failFuc != null && typeof failFuc != 'undefined'){
                    failFuc(data);
                }else if(resData.hasOwnProperty('url')){
                    window.location.href = resData.url;
                }else{
                    alertMsg(data.msg);
                }
                obj.isCommit = false;
            }
        }
    });
    return false;
};

function alertMsg(msg, end){
    var obj = typeof end == 'undefined' ?
        {content:msg, skin:'msg', time:2} : {content:msg, skin:'msg', time:2, end:end};
    return layer.open(obj);
}

function getVerify(span, obj){
    //获取短信验证码
    var $span = $(span);
    var spanText = $span.html();
    if(spanText == '获取验证码' || spanText == '重新获取'){
        $.kingAjax(obj,function(res){
            var time = 120;
            var t=setInterval(function () {
                time--;
                $span.html(time+"秒");
                if (time==0) {
                    clearInterval(t);
                    $span.html("重新获取");
                }
            },1000)
        })
    }
}
function uploadFile(img, from, id, typeid, success){
    var fm = new FormData();
    fm.append('img', img);
    fm.append('from', from);
    fm.append('action', id);
    fm.append('typeid', typeid);
    $.ajax({
        url: '/index/file/uploadImg',
        type: 'POST',
        data: fm,
        contentType: false, //禁止设置请求类型
        processData: false, //禁止jquery对DAta数据的处理,默认会处理
        //禁止的原因是,FormData已经帮我们做了处理
        success: function (result) {
            if (result.code == 1) {
                success(result);
            }else if(result.code == -1){
                if(window.top==window.self){
                    window.location.href = result.data.url;
                }else{
                    window.parent.location.href = result.data.url;
                }
            } else {
                alertMsg(result.msg);
            }
        }
    });
}
$(function(){
    $('.goBack').click(function(){
        history.go(-1);
    })
});

