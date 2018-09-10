jQuery.fn.serializeJson = function () {
    var serializeObj = {};
    $(this.serializeArray()).each(function () {
        serializeObj[this.name] = this.value;
    });
    return serializeObj;
};
jQuery.kingAjax = function(form){
    var url = $(form).attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data:  $(form).serialize(),
        async: true,
        beforeSend: function () {jz = layer.load(0, {shade: false});},
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
    return false;
};
jQuery.kingAjaxFormNotJump = function(form, successFuc){
    var url = $(form).attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data:  $(form).serialize(),
        async: true,
        beforeSend: function () {jz = layer.load(0, {shade: false});},
        error: function (request) {layer.close(jz); layer.alert('网络错误',{icon:2}, function(index){layer.close(index);});},
        success: function (data) {layer.close(jz);
            if (data.code == 1) {
                layer.msg(data.msg, {icon: 1, time: 2000}, function(){
                    if(successFuc != null && typeof successFuc != undefined){successFuc(data);}
                });
            }else if (data.code == -1) {
                if(window.top==window.self){window.location.href = data.data.url;}else{window.parent.location.href = data.data.url;}
            } else {
                layer.msg(data.msg, {icon: 2, time: 2000});
            }
        }
    });
    return false;
};
jQuery.kingAjaxNotJump = function(url, data, successFuc){
    $.ajax({
        type: "POST",
        url: url,
        data:  data,
        async: true,
        beforeSend: function () {jz = layer.load(0, {shade: false});},
        error: function (request) {layer.close(jz);layer.alert('网络错误',{icon:2}, function(index){layer.close(index);});},
        success: function (data) {layer.close(jz);
            if (data.code == 1) {
                layer.msg(data.msg, {icon: 1, time: 2000}, function(){
                    if(successFuc != null && typeof successFuc != undefined){successFuc(data);}
                });
            }else if (data.code == -1) {
                if(window.top==window.self){window.location.href = data.data.url;}else{window.parent.location.href = data.data.url;}
            } else {
                layer.msg(data.msg, {icon: 2, time: 2000});
            }
        }
    });
    return false;
};
jQuery.kingAjaxNotJumpError = function(url, data, successFuc, failFuc){
    $.ajax({
        type: "POST",
        url: url,
        data:  data,
        async: true,
        beforeSend: function () {jz = layer.load(0, {shade: false});},
        error: function (request) {layer.close(jz);layer.alert('网络错误',{icon:2}, function(index){layer.close(index);});},
        success: function (data) {layer.close(jz);
            if (data.code == 1) {
                layer.msg(data.msg, {icon: 1, time: 2000}, function(){
                    if(successFuc != null && typeof successFuc != undefined){successFuc(data);}
                });
            }else if (data.code == -1) {
                if(window.top==window.self){window.location.href = data.data.url;}else{window.parent.location.href = data.data.url;}
            } else {
                layer.msg(data.msg, {icon: 2, time: 2000}, function(){
                    if(failFuc != null && typeof failFuc != undefined){failFuc(data);}
                });
            }
        }
    });
    return false;
};
jQuery.kingAjaxNotMsg = function(url, data, successFuc){
    $.ajax({
        type: "POST",
        url: url,
        data:  data,
        async: true,
        error: function (request) {layer.alert('网络错误',{icon:2}, function(index){layer.close(index);});},
        success: function (data) {
            if (data.code == 1) {
                if(successFuc != null && typeof successFuc != undefined){successFuc(data);}
            }else if (data.code == -1) {
                if(window.top==window.self){window.location.href = data.data.url;}else{window.parent.location.href = data.data.url;}
            } else {
                layer.msg(data.msg, {icon: 2, time: 2000});
            }
        }
    });
    return false;
};
jQuery.kingAjaxJump = function(url, data){
    $.ajax({
        type: "POST",
        url: url,
        data:  data,
        async: true,
        beforeSend: function () {jz = layer.load(0, {shade: false});},
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
    return false;
};