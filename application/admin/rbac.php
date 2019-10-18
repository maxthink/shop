<?php
/**
 * 权限管理
 */
return [
    'login' =>[
        'index' => '登录后台',
        'login' => '登录后台',
        'logout' => '退出后台',
    ],
    'index' =>[
        'clear' => '清除缓存',
        'system'  => '系统设置',
    ],
    'admin' =>[
        'index' => '查看管理员',
        'add' => '添加管理员',
        'update' => '编辑管理员信息',
        'del' => '锁定管理员'
    ],
    'dashboard' =>[
        'index' => '查看仪表盘->控制台',
        'analysis'=> '查看仪表盘->分析页',
        'welcome'=>'欢迎页',
    ],
    'article' =>[
        'index' => '查看文章列表',
        'add'   => '添加文章',
        'edit'  => '编辑文章',
        'dele'  => '删除文章',
        'move'  => '移动文章分类',
        'copy'  => '转载文章',
    ],
    'product' =>[
        'index' => '查看产品列表',
        'add'   => '添加产品',
        'edit'  => '编辑产品',
        'dele'  => '删除产品',
        'move'  => '移动产品分类',
    ],
    'page' =>[
        'index' => '查看单页列表',
        'add'   => '添加单页',
        'edit'  => '编辑单页',
        'dele'  => '删除单页',
    ],
    'nav' =>[
        'index' => '查看导航列表',
        'add'   => '添加导航',
        'edit'  => '编辑导航',
        'dele'  => '删除导航',
    ],
    
    'role' =>[
        'index' => '查看角色',
        'add' => '添加角色',
        'update' => '编辑角色',
        'dele' => '删除角色',
        'authtree'=>'编辑角色权限',
    ],
    'banner' => [
        'index' => '查看幻灯广告',
        'add'   => '添加幻灯广告',
        'edit'  => '编辑幻灯广告',
        'dele'  => '删除幻灯广告',
        'banlist' => '查看幻灯广告图片',
        'adddetail' => '添加幻灯图片集',
        'editdetail' => '编辑幻灯图片集',
        'deledetail' => '删除幻灯图片集',
    ],
    'flink' =>[
        'index' => '查看友情链接列表',
        'annindex' => '查看公告列表',
        'add' => '添加友链/公告',
        'edit' => '编辑友链/公告',
        'dele' => '删除友链/公告'
    ],
    'comment' =>[
        'index' => '留言管理',
        'add' => '回复留言',
        'dele' => '删除留言'
    ],
    'log' =>[
        'index' => '查看操作日志',
    ],
    'device' =>[
        'index' => '查看设备列表',
        'update'=> '更改设备信息',
        'lock'  => '锁定设备',
        'work'  => '远程启动/停止设备'
    ],
    'devtype' =>[
        'index' => '查看设备分类列表',
        'update'=> '更改设备分类信息'
    ],
    'bill' =>[
        'index' => '查看账单列表',
        'count'=> '查看设备营业额',
    ],
    'bind' =>[
        'index' => '查看绑定关系列表',
        'update'=> '审核绑定关系',
        'unbind'=> '解除绑定关系',
    ],
    'cash' =>[
        'index' => '查看提现申请列表',
        'export'=> '导出提现申请数据',
        'update'=> '客服审核提现申请',
        'finance'=> '财务审核提现申请',
        'pay'=>'财务添加付款信息'
    ],
    'menu'=>[
        'index'=>'菜单管理首页',
        'add' => '添加菜单项',
        'update'=>'修改菜单项'
    ],
    'setmeal'=>[
        'index'=>'套餐列表',
        'add' => '添加套餐',
        'update'=>'修改套餐',
        'del'=>'删除套餐'
    ],

];
