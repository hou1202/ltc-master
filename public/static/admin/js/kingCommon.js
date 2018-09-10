function exportExcel(table){
    window.open('/admin/excel/index.html?type='+table+'&'+$('#searchForm').serialize());
}

function openMap(){
    layer.open({
        id:'layerMap',
        type: 2,
        title: '选择地址',
        content: '/admin/text_type/map.html',
        btn: ['保存', '取消'],
        area: ['1000px', '600px'],
        maxmin: true,
        yes: function (index) {
            var obj=document.getElementById("layui-layer-iframe"+index).contentWindow;
            //获取地址
            var address = obj.document.getElementById("keyword").value;
            var longitude = obj.document.getElementById("lngX").value;
            var latitude = obj.document.getElementById("latY").value;
            if(address == ''){
                layer.alert('请填写地址',{icon:2}, function(index){
                    layer.close(index);
                });
                return false;
            }
            if(longitude == '' || latitude == ''){
                layer.alert('请选择经纬度',{icon:2}, function(index){
                    layer.close(index);
                });
                return false;
            }
            $('input[name=address]').val(address);
            $('input[name=longitude]').val(longitude);
            $('input[name=latitude]').val(latitude);
            layer.close(index);
        }
    });
}

function selectArticle(inputId){
    layer.open({
        id:'layerArticle',
        type: 2,
        title: '选择文章',
        content: '/admin/text_type/article.html',
        btn: ['保存', '取消'],
        area: ['1000px', '600px'],
        maxmin: true,
        yes: function (index) {
            var obj=document.getElementById("layui-layer-iframe"+index).contentWindow;
            //获取地址
            var newsId = obj.document.getElementById("newsId").value;
            if(newsId==''){
                return layerError('请选择文章');
            }
            $(inputId).val(newsId);
            layer.close(index);
        }
    });
}