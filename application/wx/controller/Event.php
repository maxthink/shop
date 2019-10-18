<?php

namespace app\wx\controller;

use think\Controller;

class Event {

	//处理文字事件
	public function text($content){
		if( preg_match('/.*优惠券.*/', $content) ){
			return '点击领取优惠券  http://csgxjk.com/wx/coupon/quan <a href="http://csgxjk.com/wx/coupon/quan">点击领取优惠券</a>';
		}
		return '';
	}

	public function action($event)
	{
		switch($event)
		{
			case '':
				return '';
			default:
				return '';
		}
	}
}
