function layerError(msg){
    layer.msg(msg, {icon: 2, time: 2000});
    return false;
}
function layerSuccess(msg){
    layer.msg(msg, {icon: 1, time:2000});
    return false;
}
function indexAdd(){
    window.location.href = './add?'+$('#searchForm').serialize();
}