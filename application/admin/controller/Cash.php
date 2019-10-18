<?php
/**
 * 提现, 审核模块
 */

namespace app\admin\controller;

use app\common\model\Cash as Mcash;
use think\facade\Request;

class Cash extends Common
{
    /**
     * 申请列表
     */
    public function index()
    {
        if( Request::instance()->isPost() ) {    //post 方式, 获取数据
            $page = input('page',1,'intval');
            $limit = input('limit',10,'intval');
            
            $uid = input('s1','','intval');
            $status = input('s2','','intval');

            $where[]= "1=1";
            if( $status ){
                $where[]= "status&".$status;
            }

            if($uid){
                $where[]='uid='.$uid;
            }
            
            $dmodel = new Mcash();
            $list = $dmodel->list_admin($page,$limit,$where);
            //echo $dmodel->getLastSql();
            
            return apiJson($list);
            
        } else {    //一般 get 请求, 展示页面

            $this->assign('Mcash',new Mcash() );
            return $this->fetch();
        }
        
    }

    //导出数据
    public function export()
    {
        if( Request::instance()->isPost() ) {    //post 方式, 获取数据
            
            $uid = input('s1','','intval');
            $status = input('s2','','intval');

            $where[]= "1=1";
            if( $status ){
                $where[]= "status&".$status;
            }

            if($uid){
                $where[]='uid='.$uid;
            }
            
            $dmodel = new Mcash();
            $data = $dmodel->list_admin( 0, 0, $where );
            //echo $dmodel->getLastSql();
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', '申请人');
            $sheet->setCellValue('B1', '提现金额');
            $sheet->setCellValue('C1', '提现手续费');
            $sheet->setCellValue('D1', '提现实际到账');
            $sheet->setCellValue('E1', '申请时间');
            $sheet->setCellValue('F1', '开户银行');
            $sheet->setCellValue('G1', '开户名');
            $sheet->setCellValue('H1', '银行账户');
            $sheet->setCellValue('I1', '纳税人识别号');
            
            $count = count($data);  //计算有多少条数据
            for ($i = 2; $i <= $count+1; $i++) {
                $sheet->setCellValue('A' . $i, $data[$i-2]['realname']); 
                $sheet->setCellValue('B' . $i, $data[$i-2]['money']/100); 
                $sheet->setCellValue('C' . $i, $data[$i-2]['service']/100);
                $sheet->setCellValue('D' . $i, $data[$i-2]['money_true']/100);                 
                $sheet->setCellValue('E' . $i, date('Y-m-d H:i:s',$data[$i-2]['time_apply']) );
                $sheet->setCellValue('F' . $i, '`'.$data[$i-2]['account_bank']);
                $sheet->setCellValue('G' . $i, $data[$i-2]['account_name']);
                $sheet->setCellValue('H' . $i, '`'.$data[$i-2]['account_number']);
                $sheet->setCellValue('I' . $i, '`'.$data[$i-2]['account_tax']);
            }
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.'提现申请_'.date('Y-m-d').'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    }

    /**
     * 客服审核
     */
    public function update()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');

            try
            {
                $M = Mcash::get($params['id']);
                if(null!==$M){
                    $M->status = $params['status'];
                    $M->comment = $params['comment'];
                    $M->time_verify = time();
                    $M->timeline = time();
                    $M->save();
                    return apiJson('',0,'修改成功');
                }else{
                    return apiJson('',1,'修改失败, 没找到该记录');
                }
            } catch (\Exception $e) {
                return apiJson('',1,'修改失败'.$e->getMessage());
            }
             
        } else {
            return apiJson('',1,'没有参数');
        }
    }

    /**
     * 财务审核
     */
    public function finance()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');
            try
            {
                $M = Mcash::get($params['id']);
                if(null!==$M){
                    $M->status = $params['status'];
                    $M->comment = $params['comment'];
                    $M->time_finance = time();
                    $M->timeline = time();
                    $M->save();
                    return apiJson('',0,'修改成功');
                }else{
                    return apiJson('',1,'修改失败, 没找到该记录');
                }
            } catch (\Exception $e) {
                return apiJson('',1,'修改失败'.$e->getMessage());
            }
             
        } else {
            return apiJson('',1,'没有参数');
        }
    }

    //添加支付信息
    public function pay()
    {
        if ( Request::instance()->isPost() ) {

            $params = input('post.');
 
            try
            {
                $M = Mcash::get($params['id']);
                $M->status = Mcash::CASH_PAYED;
                $M->paybill = $params['paybill'];
                $M->time_pay = time();
                $M->timeline = time();
                
                if ( $M->save() ) {
                    return apiJson('',0,'修改成功');
                }else{
                    return apiJson('',1,'修改失败'.$M->getError());
                }
            } catch (\Exception $e) {
                return apiJson('',1,'修改失败'.$e->getMessage());
            }
             
        } else {
            return apiJson('',1,'没有参数');
        }
    }
    
}
