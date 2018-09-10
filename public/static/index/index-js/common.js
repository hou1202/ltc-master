function changeCity(cityId, cityName){
    $('#city_id').val(cityName);
    $.kingAjaxNotMsg('/index/common/hospitals', {city_id:cityId}, function(data){
        var hospitalOpt = '';
        if(data.data.length > 0){
            $.kingAjaxNotMsg('/index/common/departments', {hospital_id:data.data[0].hospital_id}, function(data){
                var departmentOpt = '';
                if(data.data.length >0){
                    for(var i in data.data){
                        departmentOpt += '<option value="'+data.data[i].department_id+'">'+data.data[i].department_name+'</option>';
                    }
                }else{
                    departmentOpt = '<option value="">该医院暂无科室</option>';
                }
                $('#department_id').html(departmentOpt);
            });
            for(var i in data.data){
                hospitalOpt += '<option value="'+data.data[i].hospital_id+'">'+data.data[i].hospital_name+'</option>';
            }
        }else{
            hospitalOpt = '<option value="">该地区暂无医院</option>';
        }
        $('#hospital_id').html(hospitalOpt);
    })
}
function updateCaptcha(img){
    $(img).attr('src', '/index/captcha/index?time='+Date.parse(new Date()));
}
function getVerify(span, verifyType){
    //获取短信验证码
    var $span = $(span);
    var spanText = $span.html();
    if(spanText == '获取验证码' || spanText == '重新获取'){
        $.kingAjaxNotJumpError('/index/verify/get', {type:verifyType, captcha:$('#captcha').val(), mobile:$('#mobile').val()}, function(){
            var time = 120;
            setTimeout(function(){$span.addClass("msgs1")},1000);
            var t=setInterval(function () {
                time--;
                $span.html(time+"秒");
                if (time==0) {
                    clearInterval(t);
                    $span.html("重新获取");
                    $span.removeClass("msgs1");
                }
            },1000)
        }, function(){
            $('#captchaImg').click();
        });
    }
}
function alertErrorMsg(msg){
    layer.msg(msg, {icon: 2, time: 1500});
    return false;
}
function alertSuccessMsg(msg){
    layer.msg(msg, {icon: 1, time: 1500});
    return false;
}
function stopEvent(){
    window.event? window.event.cancelBubble = true : e.stopPropagation();
    window.event? window.event.returnValue = false : e.preventDefault();
}
function goHistory(){
    window.history.go(-1);
}
function closeAllLayer(){
    layer.closeAll();
}
function ajaxPostCommonJump(url, data){
    var jz;
    $.ajax({
        type: "POST",
        url: url,
        data:  data,
        async: true,
        beforeSend: function () {jz = layer.load(0, {shade: false});},
        error: function (request) {layer.close(jz);layer.alert('网络错误',{icon:2}, function(index){layer.close(index);});},
        success: function (data) {
            layer.close(jz);
            if (data.code == 1) {
                layer.msg(data.msg, {icon: 1, time: 1500},function(){parent.window.location.href = data.data.url;});
            }else if(data.code == -1){
                layer.msg(data.msg, {icon: 2, time: 2000}, function(){window.location.href = '/index/login/index.html';});
            } else {
                layer.msg(data.msg, {icon: 2, time: 2000});
            }
        }
    });
}
function printPdf(fileId){
    window.open('/index/patient_file/pdf?file_id='+fileId);
}
$(function(){
    var msgNotRead = document.getElementById('msgNotRead');
    if(msgNotRead!=null) {
        $.post('/index/msg/notReadCount', {time: Date.parse(new Date())}, function (data) {
            if(data.data>0) {
                $(msgNotRead).html(data.data);
                $(msgNotRead).show();
            }
        });
        setInterval(function () {
            $.post('/index/msg/notReadCount', {time: Date.parse(new Date())}, function (data) {
                if(data.data>0) {
                    $(msgNotRead).html(data.data);
                    $(msgNotRead).show();
                }
            });
        }, 5000);
    }
    $('#userInfoBtn').click(function(){
        window.location.href = '/index/user/index';
    });
    $('#logoutBtn').click(function(){
        var jz;
        $.ajax({
            type: "POST",
            url: '/index/user/logout',
            data:  {time:Date.parse(new Date())},
            async: true,
            beforeSend: function () {jz = layer.load(0, {shade: false});},
            dataType: 'json',
            error: function (request) {layer.close(jz);layer.alert('网络错误',{icon:2}, function(index){layer.close(index);});},
            success: function (data) {layer.close(jz);
                if (data.code == 1) {
                    layer.msg(data.msg, {icon: 1, time: 2000}, function(){
                        window.location.href = data.data.url;
                    });
                }else if (data.code == -1) {
                    if(window.top==window.self){window.location.href = data.data.url;}else{window.parent.location.href = data.data.url;}
                }else {
                    layer.msg(data.msg, {icon: 2, time: 2000});
                }
            }
        });
    });
});
function saveScanImgs(imgs){
    if(imgs.length>0) {
        var value = $('input[name=scan_imgs]').val();
        var values = value == '' ? new Array() : value.split(',');
        for (var i in imgs) {
            values.push(imgs[i].src);
            var section = '<section class="up-section" style="width: 200px; height: 160px;"><img class="up-img" data-url="'+imgs[i].src+'" src="' + imgs[i].src + '" data-id="' + imgs[i].id + '"><a href="javascript:void(0)"><img class="close-upimg" src="/static/admin/images/a7.png"><span class="up-span"></span></a> </section>';
            $('#scan_imgs').append(section);
        }
        $('input[name=scan_imgs]').val(values.join(','));
    }
}