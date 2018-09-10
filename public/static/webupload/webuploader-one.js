function uploadOne(uploadId, uploadText, WebUploader, removeId) {
    var uploaderPoster = WebUploader.create({
        auto: true,
        swf: '/static/webupload/Uploader.swf',
        disableGlobalDnd: false,
        server: "/admin/file/uploadOne",
        pick: uploadId + ' .webuploader-container',
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        fileNumLimit: 1,
        fileSizeLimit: 1024 * 1024,    // 1 M
        fileSingleSizeLimit: 1024 * 1024    // 1 M
    });
    var $list = $(uploadId + ' .uploader-list');
    var thumbnailWidth = 150;
    var thumbnailHeight = 200;
    uploaderPoster.on('fileQueued', function (file) {
        var $li = $(
            '<div id="' + file.id + '" class="file-item thumbnail">' +
            '<img>' +
            '<div class="info">' + file.name + '</div>' +
            '</div>'
        ), $img = $li.find('img');
        $list.append($li);
        uploaderPoster.makeThumb(file, function (error, src) {
            if (error) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }
            $img.attr('src', src);
        }, thumbnailWidth, thumbnailHeight);
    });
    uploaderPoster.on('uploadProgress', function (file, percentage) {
        var $li = $('#' + file.id),
            $percent = $li.find('.progress span');
        if (!$percent.length) {
            $percent = $('<p class="progress"><span></span></p>')
                .appendTo($li)
                .find('span');
        }
        $percent.css('width', percentage * 100 + '%');
    });
    uploaderPoster.on('uploadSuccess', function (file, data) {
        $('#' + file.id).addClass('upload-state-done');
        $(uploadText).val(data.data);
        if ($(removeId) != undefined) {
            $(removeId).remove();
        }
        $(uploadId + ' .fileMsg').text('上传成功');
    });
    uploaderPoster.on('uploadError', function (file) {
        var $li = $('#' + file.id),
            $error = $li.find('div.error');
        if (!$error.length) {
            $error = $('<div class="error"></div>').appendTo($li);
        }
        $error.text('上传失败');
    });
    uploaderPoster.on('uploadComplete', function (file) {
        $('#' + file.id).find('.progress').remove();
    });
}