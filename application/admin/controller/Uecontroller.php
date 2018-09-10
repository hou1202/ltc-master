<?php
namespace app\admin\controller;


class Uecontroller
{

    public function index()
    {
        $CONFIG = [
            /* 上传图片配置项 */
            "imageActionName" => "uploadimage", /* 执行上传图片的action名称 */
            "imageFieldName" => "upfile", /* 提交的图片表单名称 */
            "imageMaxSize" => 2048000, /* 上传大小限制，单位B */
            "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
            "imageCompressEnable" => true, /* 是否压缩图片,默认是true */
            "imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
            "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
            "imageUrlPrefix" => "", /* 图片访问路径前缀 */
            "imagePathFormat" => '/ueditorupload/image/'.date('Ydm').'/' . md5(time() . rand(1, 10000)), /* 上传保存路径,可以自定义保存路径和文件名格式 */

            /* 涂鸦图片上传配置项 */
            "scrawlActionName" => "uploadscrawl", /* 执行上传涂鸦的action名称 */
            "scrawlFieldName" => "upfile", /* 提交的图片表单名称 */
            "scrawlPathFormat" => '/ueditorupload/image/' .date('Ydm').'/' . md5(time() . rand(1, 10000)), /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "scrawlMaxSize" => 2048000, /* 上传大小限制，单位B */
            "scrawlUrlPrefix" => "", /* 图片访问路径前缀 */
            "scrawlInsertAlign" => "none",

            /* 截图工具上传 */
            "snapscreenActionName" => "uploadimage", /* 执行上传截图的action名称 */
            "snapscreenPathFormat" => '/ueditorupload/image/' .date('Ydm').'/' . md5(time() . rand(1, 10000)), /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "snapscreenUrlPrefix" => "", /* 图片访问路径前缀 */
            "snapscreenInsertAlign" => "none", /* 插入的图片浮动方式 */

            /* 抓取远程图片配置 */
            "catcherLocalDomain" => ["127.0.0.1", "localhost", "img.baidu.com"],
            "catcherActionName" => "catchimage", /* 执行抓取远程图片的action名称 */
            "catcherFieldName" => "source", /* 提交的图片列表表单名称 */
            "catcherPathFormat" => '/ueditorupload/image/' .date('Ydm').'/' . md5(time() . rand(1, 10000)), /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "catcherUrlPrefix" => "", /* 图片访问路径前缀 */
            "catcherMaxSize" => 2048000, /* 上传大小限制，单位B */
            "catcherAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

            /* 上传视频配置 */
            "videoActionName" => "uploadvideo", /* 执行上传视频的action名称 */
            "videoFieldName" => "upfile", /* 提交的视频表单名称 */
            "videoPathFormat" => '/ueditorupload/video/' .date('Ydm').'/' . md5(time() . rand(1, 10000)), /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "videoUrlPrefix" => "", /* 视频访问路径前缀 */
            "videoMaxSize" => 102400000, /* 上传大小限制，单位B，默认100MB */
            "videoAllowFiles" => [
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

            /* 上传文件配置 */
            "fileActionName" => "uploadfile", /* controller里,执行上传视频的action名称 */
            "fileFieldName" => "upfile", /* 提交的文件表单名称 */
            "filePathFormat" => '/ueditorupload/file/' .date('Ydm').'/' . md5(time() . rand(1, 10000)), /* 上传保存路径,可以自定义保存路径和文件名格式 */
            "fileUrlPrefix" => "", /* 文件访问路径前缀 */
            "fileMaxSize" => 51200000, /* 上传大小限制，单位B，默认50MB */
            "fileAllowFiles" => [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ], /* 上传文件格式显示 */

            /* 列出指定目录下的图片 */
            "imageManagerActionName" => "listimage", /* 执行图片管理的action名称 */
            "imageManagerListPath" => '/ueditorupload/image', /* 指定要列出图片的目录 */
            "imageManagerListSize" => 20, /* 每次列出文件数量 */
            "imageManagerUrlPrefix" => "", /* 图片访问路径前缀 */
            "imageManagerInsertAlign" => "none", /* 插入的图片浮动方式 */
            "imageManagerAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

            /* 列出指定目录下的文件 */
            "fileManagerActionName" => "listfile", /* 执行文件管理的action名称 */
            "fileManagerListPath" => '/ueditorupload/file', /* 指定要列出文件的目录 */
            "fileManagerUrlPrefix" => "", /* 文件访问路径前缀 */
            "fileManagerListSize" => 20, /* 每次列出文件数量 */
            "fileManagerAllowFiles" => [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ]
        ];
        $action = $_GET['action'];

        switch ($action) {
            case 'config':
                $result = json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = include("../extend/uedit/action_upload.php");
                break;

            /* 列出图片 */
            case 'listimage':
                $result = include("../extend/uedit/action_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include("../extend/uedit/action_list.php");
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = include("../extend/uedit/action_crawler.php");
                break;

            default:
                $result = json_encode([
                    'state' => '请求地址出错'
                ]);
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode([
                    'state' => 'callback参数不合法'
                ]);
            }
        } else {
            echo $result;
        }
    }
}
