<?php

	namespace Admin\Controller;
	use Think\Controller;
	use Think\Model;
	class FinderController extends Controller {

        /**
         * [[添加兼职信息试图渲染]]
         */
        public function jobAdd() {
        	if (empty($_SESSION['login_name'])) {
        		$this->redirect('login/login');
        	}
        	if (!empty($_POST)) {
        		$Job = M('job', '', 'DB_CONFIG1');
        		$jobInfo = I('post.');
        		$jobInfo['job_post_date'] = time();
        		if ($Job->add($jobInfo)) {
        			$this->redirect('joblist');
        		}
        	}else {
            	$this->display('job');
        	}
        }


		public function jobList(){
			if (empty($_SESSION['login_name'])) {
      		$this->redirect('login/login');
      	}
			$Job = M('job', '', 'DB_CONFIG1');
			$count = $Job->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count, 6);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$show = $Page->show();// 分页显示输出
			$Jobs = $Job->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('jobs', $Jobs);
			$this->assign('page',$show);// 赋值分页输出
			$this->display('Finder/joblist');
		}

		public function jobDel($jid){
			if (empty($_SESSION['login_name'])) {
        		$this->redirect('login/login');
        	}
			if (isset($jid)) {
				$Job = M('job', '', 'DB_CONFIG1');
				$Job->delete($jid);
				$this->redirect('Finder/joblist');
			}
		}


		public function jobUpdate($id){
			if (empty($_SESSION['login_name'])) {
        		$this->redirect('login/login');
        	}
			$Job = M('job', '', 'DB_CONFIG1');
			if (!empty($_POST)) {
				$jonData = I('post.');
				if($Job->where("job_id = '$id'")->save($jonData)){
					$this->redirect('joblist');
				}
			}else {
				$jobInfo = $Job->where("job_id = '$id'")->find();
				$this->assign('jobInfo', $jobInfo);
				$this->display('jobUpdate');
			}
		}


		/**
		 * [[_getHotGoods 返回从41取出的数据]]
		 * @return [Array] [所有的热门商品]
		 */
		public function _getHotGoods(){
			$hotGoods = array();
			foreach(C('CONDITIONS') as $v){
				foreach($this->_getByCondition($v) as $good){
					array_push($hotGoods, $good);
				}
			}
			return $this->_dealGettedGoods($hotGoods);
		}


		/**
		 * [_getByCondition 根据条件取出不同类别的热门商品]
		 * @param  [String] $condition [条件]
		 * @return [Array] [不同类别的热门商品]
		 */
		private function _getByCondition($condition){
			return M('goods', '', 'DB_CONFIG2') // 41
				   ->where($condition)
				   ->join('picture ON goods.gid = picture.gid')
				   ->group('goods.gid')
				   ->order('goods.goodClicK DESC')
				   ->limit(9)
				   ->select();
		}


		/**
		 * [[_dealGettedGoods 对热门商品进行处理]]
		 * @param   [[Array]] $result [[所有热门商品]]
		 * @returns [[Array]] [[返回所有数据]]
		 */
		private function _dealGettedGoods($hotGoods){
			$Goods = M('good', '' ,'DB_CONFIG1'); // 125
			foreach($hotGoods as $good){ // 存入本地数据库
				$Goods->goods_gid = $good['gid'];
				$Goods->goods_name = $good['goodName'];
				$Goods->goods_intro = $good['goodIntro'];
				$Goods->goods_save_time = time();
				$Goods->add($data);
			}
			return $hotGoods;
		}


		public function test(){
			foreach(C('CONDITIONS') as $v){
				echo $v."===";
			}
		}


	}


?>