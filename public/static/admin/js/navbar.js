/**
 * Name:navbar.js
 * Author:Van
 * E-mail:zheng_jinfan@126.com
 * Website:http://kit.zhengjinfan.cn/
 * LICENSE:MIT
 */
layui.define(['jquery'], function(exports) {
    var $ = layui.jquery,
        _modName = 'navbar',
        _win = $(window),
        _doc = $(document);

    var navbar = {
        v: '1.0.0',
        config: {
            elem: undefined,
            filter: 'kitNavbar' //过滤器名称
        },
        set: function(options) {
            var that = this;
            $.extend(true, that.config, options);
            return that;
        },
        /**
         * 绑定特定a标签的点击事件
         */
        bind: function(callback) {
            var that = this,
                _config = that.config;
            if (_config.elem === undefined && _doc.find('ul[kit-navbar]').length === 0) {
                layui.hint().error('Navbar error:请配置Navbar容器.');
                return that;
            }
            var _elem;
            if (_config.elem !== undefined && $(_config.elem).length > 0) {
                _elem = $(_config.elem);
            } else {
                _elem = _doc.find('ul[kit-navbar]');
            }
            _elem.find('a[kit-target]').each(function() {
                var _that = $(this);
                _that.off('click').on('click', function() {
                    var options = _that.data('options');
                    var data;
                    if (options !== undefined) {
                        try {
                            data = new Function('return ' + options)();
                        } catch (e) {
                            layui.hint().error('Navbar 组件a[data-options]配置项存在语法错误：' + options)
                        }
                    } else {
                        data = {
                            icon: _that.data('icon'),
                            id: _that.data('id'),
                            title: _that.data('title'),
                            url: _that.data('url'),
                        };
                    }
                    typeof callback === 'function' && callback(data);
                });
            });
            $('.kit-side-fold').off('click').on('click', function() {
                var _side = _doc.find('div.kit-side');
                if (_side.hasClass('kit-sided')) {
                    _side.removeClass('kit-sided');
                    _side.find('li.layui-nav-item').removeClass('kit-side-folded');
                    _side.find('dd').removeClass('kit-side-folded');
                    _doc.find('div.layui-body').removeClass('kit-body-folded');
                    _doc.find('div.layui-footer').removeClass('kit-footer-folded');
                } else {
                    _side.addClass('kit-sided');
                    _side.find('li.layui-nav-item').addClass('kit-side-folded');
                    _side.find('dd').addClass('kit-side-folded');
                    _doc.find('div.layui-body').addClass('kit-body-folded');
                    _doc.find('div.layui-footer').addClass('kit-footer-folded');
                }
            });
            return that;
        }
    };
    exports('navbar', navbar);
});