var message,app;
layui.config({base: '/static/admin/js/'}).use(['app', 'message', 'form'], function() {
     var $ = layui.jquery,
        form = layui.form;
    //将message设置为全局以便子页面调用
    message = layui.message;
    app = layui.app;
    //主入口
    app.set({type: 'iframe'}).init();

    //锁屏
    $(document).keydown(function (event) {
        var e = event || window.event;
        var k = e.keyCode || e.which;

        if (e.keyCode === 76 && e.altKey) {
            lock($, layer);
        }
    });
    $('#lock').on('click', function () {
        lock($, layer);
    });
    $('#updatePwd').click(function () {
        var content = $('#update-pass-temp').html();
        layer.open({
            type: 1,
            title: '修改密码',
            content: content,
            btn: ['保存', '取消'],
            area: ['600px', '400px'],
            maxmin: true,
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: '/admin/manager/resetPwd',
                    data: {'password1': $('#password1').val(), 'password2': $('#password2').val()},
                    async: true,
                    beforeSend: function () {
                        jz = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
                    },
                    error: function (request) {
                        layer.close(jz);
                        layer.alert('网络错误', {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    },
                    success: function (data) {
                        //关闭加载层
                        layer.close(jz);
                        if (data.code == 1) {
                            layer.msg(data.msg, {
                                icon: 1,
                                time: 1500
                            }, function () {
                                window.location.href = data.data.url;
                            });
                        } else if (data.code == -1) {
                            window.location.href = '/admin/login/index';
                        } else {
                            layer.alert(data.msg);
                        }
                    }
                });
            }
        });
    });

    $('#logout').click(function () {
        $.ajax({
            type: "GET",
            url: '/admin/login/logout',
            async: true,
            beforeSend: function () {
                jz = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
            },
            error: function (request) {
                layer.close(jz);
                layer.alert('网络错误', {icon: 2}, function (index) {
                    layer.close(index);
                });
            },
            success: function (data) {
                //关闭加载层
                layer.close(jz);
                if (data.code == 1) {
                    layer.msg(data.msg, {
                        icon: 1,
                        time: 1500
                    }, function () {
                        window.location.href = data.data.url;
                    });
                } else if (data.code == -1) {
                    window.location.href = '/admin/login/login';
                } else {
                    layer.alert(data.msg);
                }
            }
        });
    });
});



function lock($, layer) {
    //自定页
    layer.open({
        title: false,
        type: 1,
        closeBtn: 0,
        anim: 6,
        content: $('#lock-temp').html(),
        shade: [0.9, '#393D49'],
        success: function (layero, lockIndex) {

            //给显示用户名赋值
            layero.find('div#lockUserName').text($('#lockUserName').text());
            layero.find('input[name=lockPwd]').on('focus', function () {
                    var $this = $(this);
                    if ($this.val() === '输入密码解锁..') {
                        $this.val('').attr('type', 'password');
                    }
                })
                .on('blur', function () {
                    var $this = $(this);
                    if ($this.val() === '' || $this.length === 0) {
                        $this.attr('type', 'text').val('输入密码解锁..');
                    }
                });
            //在此处可以写一个请求到服务端删除相关身份认证，因为考虑到如果浏览器被强制刷新的时候，身份验证还存在的情况
            $.post('/admin/login/logout', function (data) {
                //验证成功
                if (data.code == 1) {
                    //console.log(data.msg);
                }
            }, 'json');
            //绑定解锁按钮的点击事件
            layero.find('button#unlock').on('click', function () {
                var $lockBox = $('div#lock-box');

                var userName = $lockBox.find('div#lockUserName').text();
                var pwd = $lockBox.find('input[name=lockPwd]').val();
                if (pwd === '输入密码解锁..' || pwd.length === 0) {
                    layer.msg('请输入密码..', {
                        icon: 2,
                        time: 1000
                    });
                    return;
                }
                unlock(userName, pwd);
            });
            /**
             * 解锁操作方法
             * @param {String} 用户名
             * @param {String} 密码
             */
            var unlock = function (un, pwd) {
                //这里可以使用ajax方法解锁
                $.post('/admin/login/login', {username: un, password: pwd}, function (data) {
                    //验证成功
                    if (data.code == 1) {
                        //关闭锁屏层
                        layer.close(lockIndex);
                    } else {
                        layer.msg('密码输入错误..', {icon: 2, time: 1000});
                    }
                }, 'json');
            };
        }
    });
}


