<?php
namespace app\admin\model;
use app\common\model\Common;

class StoreList extends Common  {

    public function getCtimeAttr($value)
    {
        $citme = date("Y-m-d H:i:s",$value);
        return $citme;
    }

}