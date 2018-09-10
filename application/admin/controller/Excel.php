<?php
namespace app\admin\controller;


use app\common\controller\AdminCheckLoginController;
use PHPExcel;
use PHPExcel_IOFactory;
use think\Db;
use think\Session;

class Excel extends AdminCheckLoginController
{

    protected static $sModelClass = 'ManagerAction';

    public function index()
    {
        $type = $this->request->get('type', '');
        $configs = \app\admin\model\Excel::$sConfig;
        if(!isset($configs[$type])){
            die('server error!');
        }
        $obj = $configs[$type];
        $where = isset($obj['defaultWhere']) ? $obj['defaultWhere'] : '';
        foreach($obj['where'] as $v=>$vValue){
            $d = $this->request->get($v, '');
            if($d!=='' && $d!=' '){
                !empty($where) && $where .= ' AND ';
                switch($v){
                    case 'searchDate':
                        $dates = explode(' - ', $d);
                        $where .= 'c_time>=\''.$dates[0].'\' AND c_time<=\''.$dates[1].'\'';
                        break;
                    case 'user_id':
                        $where .='user_id='.$d;
                        break;
                }
            }
        }
        $objPHPExcel = new PHPExcel();
        $author = Session::get('systemManagerName');
        $objPHPExcel->getProperties()->setCreator($author)
            ->setLastModifiedBy($author)
            ->setTitle($obj['name'])
            ->setSubject($obj['name'])
            ->setDescription($obj['name'])
            ->setKeywords($obj['name'])
            ->setCategory($obj['name']);
        $aliases = explode(',', $obj['alias']);
        $fields = explode(',', $obj['field']);
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $ch = 65;
        foreach($aliases as $v){
            $sheet->setCellValue(chr($ch++).'1', $v);
        }
        $num = 1;
        Db::table($type)->field($obj['field'])->where($where)->order($obj['order'])->chunk(100, function($datas) use (&$num, $objPHPExcel, $fields){
            foreach($datas as $k => $v){
                $num++;
                $sheet = $objPHPExcel->setActiveSheetIndex(0);
                $ch = 65;
                foreach($fields as $f){
                    $sheet->setCellValue(chr($ch++).$num, $v[$f]);
                }
            }
        });

        $this->model->add(['manager_id'=>$this->systemManagerId, 'manager_name'=>Session::get('systemManagerName'),'log'=>'导出了'.$obj['name'].'数据']);
        $objPHPExcel->getActiveSheet()->setTitle(date('Y-m-d'));
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date('Y-m-d').$obj['name'].'数据.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}