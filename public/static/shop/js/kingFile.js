function getObjectURL(file) {
    var url = null ;
    if (window.createObjectURL!=undefined) { // basic
        url = window.createObjectURL(file) ;
    } else if (window.URL!=undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file) ;
    } else if (window.webkitURL!=undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file) ;
    }
    return url ;
}
$(function(){
    $('.img-box').on('click', '.up-span', function(){
        var imgSrc = $(this).parent().prev().attr('src');
        if(typeof imgSrc == undefined){
            return false;
        }
        layer.open({
            type: 1,
            area: '1000px',
            content: '<img src="'+imgSrc+'" width="900">' //这里content是一个普通的String
        });
    });
});

function setFileUpload(from, id, maxLength, width, height, typeid){
    var idDiv = '#'+id;
    $(idDiv).on('change', 'input[name="'+id+'File[]"]', function(){
        var $span = $(this).parent().parent().find('span');
        $span.text('图片上传中...');

        var fm = new FormData();
        var img = $(this)[0].files[0];
        var $img = $(this).parent().prev();
        var $parent = $(this).parent();
        fm.append('img', img);
        fm.append('from', from);
        fm.append('action', id);
        fm.append('typeid', typeid);
        $.ajax({
                url: '/shop/file/uploadImg',
                type: 'POST',
                data: fm,
                contentType: false, //禁止设置请求类型
                processData: false, //禁止jquery对DAta数据的处理,默认会处理
                //禁止的原因是,FormData已经帮我们做了处理
                success: function (result) {
                    if (result.code == 1) {
                        $span.text('图片上传成功');
                        var fileLength = $('input[name="' + id + 'File[]"]').length;
                        //var url = getObjectURL(img);
                        $img.attr('src', result.data.url);
                        $img.attr('data-id', result.data.id);
                        var value = $('input[name='+id+']').val();
                        if(value!=''){
                            value += ',';
                        }
                        $('input[name='+id+']').val(value+result.data.url);
                        if (fileLength < maxLength) {
                            $(idDiv).append('<section class="up-section" style="width: '
                                + width + 'px;height: '
                                + height + 'px;"><img class="up-img" src="/static/admin/images/a11.png"><a href="javascript:void(0)"><input type="file" name="' + id + 'File[]" id="' + id + 'File' + (fileLength + 1) + '"></a><span class="up-span-short">尺寸：' + width + '*' + height + '</span></section>');
                        }
                        $parent.append('<img class="close-upimg" src="/static/admin/images/a7.png"><span class="up-span"></span>');
                    }else if(result.code == -1){
                        if(window.top==window.self){
                            window.location.href = result.data.url;
                        }else{
                            window.parent.location.href = result.data.url;
                        }
                    } else {
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 2000
                        });
                    }
                }
            });
    });

    $(idDiv).on('click', '.close-upimg', function(){
        var $img = $(this);
        layer.confirm('确定删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var $srcImg = $img.parent().parent().find('img');
            var imgId = $srcImg.attr('data-id');
            var srcUrl = $srcImg.attr('src');
            $.ajax({
                url: '/shop/file/delImg',
                type: 'POST',
                data: {id:imgId},
                //禁止的原因是,FormData已经帮我们做了处理
                success: function (result) {
                    if (result.code == 1) {
                        $img.parent().parent().remove();
                        var isAdd  = true;
                        var fileLength = 0;
                        $('input[name="'+id+'File[]"]').each(function(){
                            fileLength++;
                            $(this).attr('id',id+'File'+fileLength);
                            if($(this).val() == "") {
                                isAdd = false;
                            }
                        });
                        var value = $('input[name='+id+']').val();
                        var values = value.split(',');
                        for (i=0;i<values.length ;i++ )
                        {
                            if(values[i] == srcUrl){
                                values.splice(i, 1);
                            }
                        }
                        $('input[name='+id+']').val(values.join(','));
                        if(fileLength<maxLength && isAdd) {
                            $(idDiv).append('<section class="up-section" style="width: '
                                +width+'px;height: '
                                +height+'px;"><img class="up-img" src="/static/admin/images/a11.png"><a href="javascript:void(0)"><input type="file" name="'+id+'File[]" id="'+id+'File'+ (fileLength+1)+'"></a><span class="up-span-short">尺寸：'+width+'*'+height+'</span></section>');
                        }
                        layer.msg('删除成功', {icon: 1});
                    }else if(result.code == -1){
                        if(window.top==window.self){
                            window.location.href = result.data.url;
                        }else{
                            window.parent.location.href = result.data.url;
                        }
                    } else {
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 2000
                        });
                    }
                }
            });


        }, function(){

        });
    });
}