<?php
namespace app\common\utils;


use Endroid\QrCode\QrCode;
use think\Config;
use think\Db;
use think\Loader;
use think\Response;

class ToolUtils
{

    public static function makeQrcode($str){
        $qrCode =  new QrCode();//创建生成二维码对象
        $qrCode->setText($str)
            ->setSize(224)
            ->setForegroundColor(['r'=>102,'g'=>159,'b'=>242,'a'=>1])
            ->setMargin(16);
        $fileName = md5($str).'.png';
        //先创建文件
        $path = ROOT_PATH . 'public' . DS . 'uploads'.DS.'qrcode'.DS.$fileName;
        $qrCode->writeFile($path);
        return Config::get('upload_file_domain').'/uploads/qrcode/'.$fileName;
    }

    public static function makePdfPatientFile($file)
    {
        if ($file == null) {die('error!');}
        $file['banner'] = empty($file['banner']) ? [] : explode(',', $file['banner']);
        Loader::import('tcpdf', VENDOR_PATH.'tecnickcom'.DS.'tcpdf'.DS);
        $pdf = new \Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('锐视通');
        $pdf->SetTitle('病理单');
        $pdf->SetSubject('病理单');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(50);
        $pdf->SetFont('stsongstdlight', 'B');
        $pdf->AddPage();
        $pdf->SetFontSize(22);
        $pdf->SetY(10);
        $pdf->SetX(15);
        $name = '';
        if ($file['shop_id'] > 0) {
            $hospitalId = Db::table('p_user')->where('user_id=' . $file['shop_id'])->value('hospital_id', 0);
            if ($hospitalId <= 0) {
                die('fail');
            }
            $name = Db::table('p_hospital')->where('hospital_id=' . $hospitalId)->value('hospital_name', '');
        }
        //安徽省实时远程病理诊断咨询报告单
        $name = $name . '远程病理会诊报告单';
        //$name = str_replace(['（', '）'], ['(',')'], $name);
        $pdf->Write(0, $name, '', 0, 'C', true, 0, false, false, 0);
        //$pdf->Cell(0, 20, $name.'远程病理会诊报告单', 0, 0, 'C', 0, '', 0);

// add a page
        $pdf->SetFont('stsongstdlight');
        $pdf->SetFontSize(11);
        $txt = '会诊号：' . date('Ym', strtotime($file['c_time'])) . $file['file_number'];
        $pdf->SetY(25);
        $pdf->SetX(150);
        $pdf->Write(0, $txt, '', 0, 'R', true, 5, false, false, 0);
        $pdf->Line(15, 32, 200, 32);
        $pdf->SetFontSize(12);
        //1
        $pdf->SetY(35);
        $pdf->SetX(15);
        $pdf->Write(0, '姓名：' . $file['patient_name'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetY(35);
        $pdf->SetX(100);
        $pdf->Write(0, '性别：' . $file['sex'], '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetY(35);
        $pdf->SetX(150);
        $pdf->Write(0, '年龄：' . $file['age'].$file['unit'], '', 0, 'L', true, 0, false, false, 0);

        //2
        $pdf->SetY(42);
        $pdf->SetX(15);
        $pdf->Write(0, '原送检单位：' . $file['hospital_name'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetY(49);
        $pdf->SetX(15);
        $pdf->Write(0, '原始档案号：' . $file['section_number'], '', 0, 'L', true, 0, false, false, 0);
        //$pdf->setFontSpacing(1);

        $pdf->SetY(49);
        $pdf->SetX(100);
        $pdf->Write(0, '数目：' . $file['section_count'], '', 0, 'L', true, 0, false, false, 0);

        $pdf->setFontSpacing(0);
        $pdf->SetY(56);
        $pdf->SetX(15);
        $pdf->Write(0, '原病理诊断：', '', 0, 'L', true, 10, false, false, 10);

        $pdf->SetFontSize(10);
        $pdf->SetY(62);
        $pdf->SetX(20);
        $pdf->Write(0, $file['pathology'], '', 0, 'L', false, 0, false, 100, 0, 0, 0);

        $pdf->Line(15, 87, 200, 87);

        $pdf->SetFontSize(12);
        $pdf->SetY(90);
        $pdf->SetX(15);
        $pdf->Write(0, '病理截图：', '', 0, 'L', false, 10, false, false, 0, 0);
        if (isset($file['banner'][0])) {
            $len = strpos($file['banner'][0], 'uploads');
            $image = substr($file['banner'][0], $len);
            $pdf->Image($image, 20, 96, 87, 49);
        }
        if (isset($file['banner'][1])) {
            $len = strpos($file['banner'][1], 'uploads');
            $image = substr($file['banner'][1], $len);
            $pdf->Image($image, 108, 96, 87, 49);
        }
        if (isset($file['banner'][2])) {
            $len = strpos($file['banner'][2], 'uploads');
            $image = substr($file['banner'][2], $len);
            $pdf->Image($image, 20, 146, 87, 49);
        }
        if (isset($file['banner'][3])) {
            $len = strpos($file['banner'][3], 'uploads');
            $image = substr($file['banner'][3], $len);
            $pdf->Image($image, 108, 146, 87, 49);
        }
        $pdf->Line(15, 197, 200, 197);

        $pdf->SetFontSize(12);
        $pdf->SetY(200);
        $pdf->SetX(15);
        $pdf->Write(0, '病理会诊意见：', '', 0, 'L', false, 10, false, false, 0, 0);

        $pdf->SetFontSize(10);
        $pdf->SetY(206);
        $pdf->SetX(20);
        $pdf->Write(0, $file['opinion'], '', 0, 'L', false, 0, false, 100, 0, 0, 0);


        $pdf->SetFontSize(11);
        $pdf->SetY(235);
        $pdf->SetX(15);
        $pdf->Write(0, '初诊医生：' . $file['first_doctor'], '', 0, 'L', false, 10, false, false, 0, 0, ['L' => 50, 'R' => 50]);

        $pdf->SetY(235);
        $pdf->SetX(130);
        $pdf->Write(0, '会诊医生：', '', 0, 'L', false, 10, false, false, 0, 0, ['L' => 25, 'R' => 20]);

        if ($file['second_doctor'] != '') {
            $len = strpos($file['second_doctor'], 'uploads');
            $image2 = substr($file['second_doctor'], $len);
            $pdf->Image($image2, 150, 235, 40, 15);
        }
        //日期
        $pdf->SetFontSize(10);
        $pdf->SetY(255);
        $pdf->SetX(15);
        $pdf->Write(0, '申请时间：' . date('Y-m-d', strtotime($file['apply_time'])), '', 0, 'L', false, 10, false, false, 0, 0);

        if ($file['diagnose_time'] != null) {
            $pdf->SetX(130);
            $pdf->Write(0, '报告时间：' . $file['diagnose_time'], '', 0, 'L', false, 10, false, false, 0, 0);
        }

        //线
        $pdf->Line(15, 260, 200, 260);

        $pdf->SetY(265);
        $pdf->SetX(15);
        $pdf->Write(0, '注：仅供原诊断的的病理医师及临床医生参考。如临床医生或病理医师对本报告有疑问，请及时与会诊专家联系。 ', '', 0, 'L', false, 10, false, false, 0, 0, ['L' => 25, 'R' => 20]);
        return Response::create($pdf->Output('example_001.pdf', 'I'), 'pdf');
    }
}