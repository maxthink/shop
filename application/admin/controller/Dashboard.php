<?php
/**
 * 仪表盘 控制器
 */

namespace app\admin\controller;

use think\Db;
use think\facade\Session;
use app\admin\model\Category;

class Dashboard extends Common {

    /**
     * 控制台
     */
    public function index() {
        
        //环境
        $mysql_version = Db::query('SELECT VERSION() as version');
        $soft_env = input('server.SERVER_SOFTWARE');
        $php = phpversion();
        $software['php'] = $php;
        $software['os'] = PHP_OS;
        $software['env'] = $soft_env;
        $software['mysql'] = $mysql_version[0]['version'];
        $software['gd'] = extension_loaded('gd')?'是':'否';
        $this->assign('software',$software);
        //是否删除install
        $flag = file_exists('install');
        //获取登录记录
        $uid = Session::get('userinfo.id');
        $login_list = Db::name('log')->where( 'userid='.$uid.' and type&1' )->order('time DESC')->limit(10)->select();
        //var_dump($login_list);exit;
        $this->assign('login_list',$login_list);
        //统计产品、文章总数量
        $product_count = Db::name('product')->where('status',0)->count();
        $article_count = Db::name('article')->where('status',0)->count();
        //网站主题
        $pc_theme = get_system_value('site_theme');
        $mobile_theme = get_system_value('site_mobile_theme');
        $this->assign(['pc_theme' => $pc_theme,'mobile_theme' => $mobile_theme]);
        $this->assign('product_count',$product_count);
        $this->assign('article_count',$article_count);
        //获取单页栏目
        $single_page = Category::where('modelid', 2)->field('id,name,ename')->select();
        $this->assign('single_page', $single_page);
        $this->assign('page_count',count($single_page));//单页数量
        $this->assign('flag', $flag);
        return $this->fetch();
        
    }

    /*
     * 分析页
     */
    public function analysis() {
        return $this->fetch();
    }

    /**
     * 欢迎页
     */
    public function welcome() {
        return $this->fetch();
    }

    

}
