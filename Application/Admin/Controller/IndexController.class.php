<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

	/*后台公共活动*/
    public function index(){
		// check_login(1);
		$url[1] = U("admin/common/upload_redrock_img");
		$url[2] = U("admin/common/index/to/hot_activity");
		$url[3] = U("admin/common/index/to/club_activity");
		$url[4] = U("admin/common/index/to/match_imformation");
		$url[5] = U("admin/common/index/to/creative_platform");
		$url[6] = U("admin/login/index");
		$this->assign("url",$url);
		$this->display('index');
	}
}