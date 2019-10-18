<?php

/**
 * 操作日志
 */

namespace app\admin\controller;

use think\facade\Request;
use app\admin\model\Log as SM;

class Log extends Common {

    public function index() {

        if (Request::instance()->isPost()) {

            $page = input('post.page/d', 1);
            $size = input('post.limit/d', 15);
            $startDate = input('post.startDate', '');
            $endDate = input('post.endDate', '');
            $uid = input('post.Account', 0, 'intval');

            $where = [];
            if ('' !== $startDate && preg_match('/\d{4}-\d{2}-\d{2}/', $startDate)) {
                $where[] = 'time >= ' . strtotime($startDate);
            }
            if ('' !== $endDate && preg_match('/\d{4}-\d{2}-\d{2}/', $endDate)) {
                $where[] = 'time <= ' . strtotime($endDate);
            }
            if (0 !== $uid) {
                $where[] = 'uid = ' . $uid;
            }

            $list = SM::page($page, $size, $where);

            return apiJson($list);
        } else {
            return $this->fetch();
        }
    }

}
