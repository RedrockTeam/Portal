<?php
	namespace Home\Controller;
	use Think\Controller;
	class IndexController extends Controller {

		/**
		 * [[唯一入口]]
		 */
		public function index(){
			$Finder = R('Finder/index'); // 发现板块
			$Study = R('Study/index'); // 助学版块
			$Weather = R('Weather/index'); // 天气
			
			$interface = A("interface");
			$hot_activity = $interface ->base_common_interface('hot_activity',3);//热门活动
			$club_activity = $interface ->base_common_interface('club_activity',3);//社团活动
			$match_imformation = $interface ->base_common_interface('match_imformation',10);//比赛信息
			$bbs_wd = $interface->return_bbs(5,3,$config="DB_CONFIG1");//bbs问答
			$bbs_lt = $interface->return_bbs(14,4,$config="DB_CONFIG1");//bbs论坛
			$bt = $interface->local_bt_back(7);//bt
			$create_fiction = $interface->return_creative(4,'fiction');//微小说
			$create_moving = $interface->return_creative(4,'moving');//微电影

			$jwxx = $interface->return_jwxx(14);//教务信息
			$cyxw = $interface->return_cyxw(14);/*重邮新闻*/
			$img_rdjj = $interface->return_img(1);//热点焦距
			$img_rdjj_top = $interface->return_img(2);//小图上
			$img_rdjj_bottom = $interface->return_img(3);//小图下
			$img_wxc = $interface->return_img(4);//微相册
			$img_wxc_top = $interface->return_img(5);//小图上
			$img_wxc_bottom = $interface->return_img(6);//小图下
			$img_fiction = $interface->return_img(7);//微小说


			$goods = array(
				array(
					'goods_name' => 'ipad mini一代转卖',
					'goods_src' => 'images/market/1.png'
				),
				array(
					'goods_name' => '三星笔记本低价出售',
					'goods_src' => 'images/market/2.png'
				),
				array(
					'goods_name' => 'iphone5 出售',
					'goods_src' => 'images/market/3.png'
				),
				array(
					'goods_name' => 'hello kitty水杯出售',
					'goods_src' => 'images/market/4.png'
				),
				array(
					'goods_name' => '晨光彩绘笔整盒出售',
					'goods_src' => 'images/market/5.png'
				),
				array(
					'goods_name' => '吉他配品',
					'goods_src' => 'images/market/6.png'
				),
				array(
					'goods_name' => '森马女装秋季新款',
					'goods_src' => 'images/market/7.png'
				),
				array(
					'goods_name' => '浙江产秋冬品牌高级棉袜',
					'goods_src' => 'images/market/8.png'
				),
				array(
					'goods_name' => '西装 女 34号 9.5成新',
					'goods_src' => 'images/market/9.png'
				)
			);

			$this->assign('weather', $Weather);

			$this->assign('img_rdjj', $img_rdjj['data']);
			$this->assign('img_rdjj_top', $img_rdjj_top['data']);
			$this->assign('img_rdjj_bottom', $img_rdjj_bottom['data']);
			$this->assign('jwxx', $jwxx['data']);
			$this->assign('cyxw', $cyxw['data']);
			$this->assign('img_fiction', $img_fiction['data']);//微小说图
			$this->assign('create_fiction', $create_fiction['data']);//微小说
			$this->assign('create_moving', $create_moving['data']);//微电影

			$this->assign('img_wxc', $img_wxc['data']);
			$this->assign('img_wxc_top', $img_wxc_top['data']);
			$this->assign('img_wxc_bottom', $img_wxc_bottom['data']);
			
			

			$this->assign('goods', $goods);
			$this->assign('jobs', $Finder[1]);
			$this->assign('softwares', $Finder[2]);
			$this->assign('lectures', $Study[0]);
			$this->assign('download', $Study[1]);

			$this->assign('hotActivity', $hot_activity['data']);
			$this->assign('wd' ,$bbs_wd['data']);
			$this->assign('bbs_lt', $bbs_lt['data']);
			$this->assign('clubActivity', $club_activity['data']);
			$this->assign('match', $match_imformation['data']);
			$this->assign('torrent', $bt['data']);
			$this->display('index');

		}
		
		
		/*
		 * 
		 */
		public function personal(){
			if (session('userInfo')) {
				$this->display('personal');
			} else {
				$this->error("请先登录!");
			}
		}


		/**
		 * [login 用户登录]
		 * @return [Json] [状态]
		 */
		public function userLogin(){
			if (!empty($_POST)) {
				$userNumber = I('post.userNumber');
				$idNum = I('post.idNum');
				$userInfo = $this->_checkUser(trim($userNumber), trim($idNum));
				$userInfo = myIconv($userInfo);
				if ($userInfo) {
					session('userInfo', $userInfo);
					return $this->ajaxReturn(array('status'=>200, 'info'=>'登录成功'));
				}else {
					return $this->ajaxReturn(array('status'=>0, 'info'=>'用户名或密码错误'));
				}
			}
		}


		public function logout(){
			session('userInfo', Null);
			$data = array(
				'status' => 200,
				'info' => 'success'
			);
			$this->ajaxReturn($data);
		}

		/**
		 * [checkUser 验证用户]
		 * @param  [Number] $userNumber [学号]
		 * @param  [Number] $idNum      [身份证后六位]
		 * @return [Void]             [Null]
		 */
		private function _checkUser($userNumber, $idNum){
			$User = M('uc_userinfo', '', 'DB_CONFIG4');
			$userInfo = $User->where("usernumber = '$userNumber'")->find();
			return $userInfo != Null ? substr($userInfo['idnum'], strlen($userInfo['idnum']) - 6, 6) == $idNum ? $userInfo : false : false;
		}



	}



?>