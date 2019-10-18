<?php

namespace app\admin\controller;
use app\admin\model\StoreImg;
use app\admin\model\StoreList;
use think\console\command\make\Model;
use think\facade\Request;
use think\Image;
class Store extends Common{

    //店铺展示操作
    public function index()
    {
        if( Request::instance()->isPost() )
        {
            $where[]='1=1';;
            $page = input('page',1,'intval');
            $province_name = input('province_name','');
            $imei=input('imei','');
            $phone=input('phone','');
            $search=input('search','');
            $limit = input('limit',10,'intval');
            if (!empty($province_name)){
                $where[]="province_name='".$province_name."'";
            }
            if (!empty($phone)){
                $where[]="store_phone='".$phone."'";
            }
            if (!empty($imei)){
            $where[]="store_imei like "."\"%".$imei."%\"";
            }
            if (!empty($search)){
                $where[]="store_principal like "."\"%".$search."%\"";
            }

            $list = StoreList::page($page,$limit,$where);
            return apiJson($list);

        } else {
            $province_list=db('store_list')->group('province_name')->field('province_name')->select();
            $province_data=array_column($province_list,'province_name');
            $this->assign('province',$province_data);
            return $this->fetch();
        }
    }
    //添加店铺操作
    public function add()
    {
        if (Request::instance()->isGet()){
            return $this->fetch('add');
        }else{
            $imei_Arr= $_POST['imei'];
            $lowLen=count($imei_Arr);
            $imeiArr=array_unique($imei_Arr);
            $newLen=count($imeiArr);
            if($newLen != $lowLen){
                $this->return_json('3','IMEI设备编号重复请重新输入',null);
            };
            $ImieData=db('store_list')->group('id')->field('store_imei')->select();
            $imieList=array_column($ImieData,'store_imei');
            foreach ($imieList as $k=>&$v){
                $v=explode(',',$v);
            }
            $imieData = array();
            array_walk_recursive($imieList,function ($v) use(&$imieData){
                $imieData[]=$v;
            });
           foreach ($imeiArr as $key => $vlue){
               if(in_array($vlue,$imieData)){
                    $this->return_json('3','IMEI设备编号已被其他店铺录用，不允许重复录入',null);return;
               }
           }
            $imeis=implode(',',$imeiArr);
            $StoreModel= new StoreList();
            $StoreModel->data([
                'store_name'=>$_POST['store_name'],
                'store_principal'=>$_POST['store_principal'],
                'store_phone'=>$_POST['store_phone'],
                'store_imei'=>$imeis,
                'store_address'=>$_POST['store_address'],
                'province_name'=>$_POST['province_id'],
                'ctime'=>time(),
            ]);
            $res=$StoreModel->save();
            if ($res){
                $Store_id=$StoreModel->id;
                $StoreImg = new StoreImg();
                $list=[
                    ['store_id'=>$Store_id,'img_url'=>$_POST['p_img1']],
                    ['store_id'=>$Store_id,'img_url'=>$_POST['p_img2']],
                    ['store_id'=>$Store_id,'img_url'=>$_POST['p_img3']],
                    ['store_id'=>$Store_id,'img_url'=>$_POST['p_img4']]
                ];
                $status=$StoreImg->saveAll($list);
                if ($status){
                    $this->return_json(1,'上传成功',null);
                }else{
                    $this->return_json(0,'网络错误请在修改页面重新上传图片',null);
                };
            }
        }
    }

    //店铺修改操作
    public function edit()
    {
        if (Request::instance()->isGet()){
            $id=$_GET['id'];
            $store_res=StoreList::where('id','=',"$id")->find()->toArray();
            $store_img=StoreImg::where('store_id','=',"$id")->select()->toArray();
            $store_res['store_imei']=explode(',',$store_res['store_imei']);
            $this->assign('imeis',$store_res['store_imei']);
            $this->assign('Store',$store_res);
            $this->assign('images',$store_img);
            return $this->fetch('edit');
        }else{
            $id = $_POST['store_id'];
            $imei_Arr= $_POST['imei'];
            $lowLen=count($imei_Arr);
            $imeiArr=array_unique($imei_Arr);
            $newLen=count($imeiArr);
            if($newLen != $lowLen){
                $this->return_json('3','IMEI设备编号重复请重新输入',null);
            };
            $ImieData=db('store_list')->where('id','<>',$id)->group('id')->field('id,store_imei')->select();
            $imieList=array_column($ImieData,'store_imei');
            foreach ($imieList as $k=>&$v){
                $v=explode(',',$v);
            }
            $imieData = array();
            array_walk_recursive($imieList,function ($v) use(&$imieData){
                $imieData[]=$v;
            });
            foreach ($imeiArr as $key => $vlue){
                if(in_array($vlue,$imieData)){
                    $this->return_json('3','IMEI设备编号已被其他店铺录用，不允许重复录入',null);return;
                }
            }
            $imeis=implode(',',$imeiArr);
            $SaveData=[
                'store_name'=>$_POST['store_name'],
                'store_principal'=>$_POST['store_principal'],
                'store_phone'=>$_POST['store_phone'],
                'store_imei'=>$imeis,
                'store_address'=>$_POST['store_address'],
                'province_name'=>$_POST['province_id'],
                'mtime'=>time()
            ];
            $ImgList=[
                ['store_id'=>$id,'img_url'=>$_POST['p_img1']],
                ['store_id'=>$id,'img_url'=>$_POST['p_img2']],
                ['store_id'=>$id,'img_url'=>$_POST['p_img3']],
                ['store_id'=>$id,'img_url'=>$_POST['p_img4']]
            ];
            $StoreModel=new StoreList();
            $StoreImg=new StoreImg();
           $res= $StoreModel->save($SaveData,['id'=>$id]);
            if ($res){
                $Delres=StoreImg::where('store_id','=',"$id")->delete();
                $status=$StoreImg->saveAll($ImgList);
                if (!$status){
                    $this->return_json('2','图片更新失败',null);
                }
                $this->return_json('1','店铺信息更新成功',null);
            }else{
                $this->return_json('0','店铺信息更新失败',null);
            }
        }

    }

    //店铺详情页展示
    public function detail()
    {
        $id=$_GET['id'];
        $store_res=StoreList::where('id','=',"$id")->find()->toArray();
        $store_img=StoreImg::where('store_id','=',"$id")->select()->toArray();
        $store_img=array_column($store_img,'img_url');
        $store_res['store_imei']=explode(',',$store_res['store_imei']);
        $this->assign('Store',$store_res);
        $this->assign('images',$store_img);
        return $this->fetch('detail');
    }

    //删除店铺操作
    public function delete()
    {
        $id=$_POST['id'];
        $res=StoreList::where('id','=',"$id")->delete();
        if ($res){
            $this->return_json('1','删除成功',null);
        }else{
            $this->return_json('0','删除失败',null);
        }
    }
    //上传图片操作
    public function upload()
    {
        $file = request()->file('file');
        $path="./Upload/StoreImg";
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
        if($file){
            $info = $file->validate(['size' => 4*1024*1024, 'ext' => 'jpg,png,gif,jpeg'])->move($path);
            if($info){
               $url= $info->getSaveName();
                $img_url=$path.'/'.$url;
                $image= \think\Image::open($img_url);
                $image->thumb(373,254,1 )->save($img_url);
                $this->return_json(1,"上传成功","/Upload/StoreImg/".$url);
            }else{
                // 上传失败获取错误信息
                $Error=$file->getError();
                $this->return_json(0,$Error,null);

            }
        }
    }
    //删除图片操作
    public function DelImg()
    {
        $url='.'.$_POST['src'];
        if(file_exists($url)){
            $res = unlink($url);
            if($res){
                $this->return_json(1,"删除成功",null);
            }else{
                $this->return_json(0,"删除失败",null);
            }
        }else{
           $this->return_json(0,"删除失败",null);
        }
    }

    //公共返回数据接口
    public function return_json($tatus=0,$msg="",$data=null)
    {
        $res=[
            'status'=>$tatus,
            'msg'=>$msg,
            'data'=>$data
        ];
        echo json_encode($res);return;
    }

}
