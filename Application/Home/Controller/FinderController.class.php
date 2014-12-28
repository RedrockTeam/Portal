<?php

	namespace Home\Controller;
	use Think\Controller;
	use Think\Model;
	/**
	 * Code: Excelsior
	 * Date: 2014/11/10
	 * Version: 1.0
	 */
	class FinderController extends Controller {

		/**
		 * [[对外接口]]
		 * @returns [[Array]] [[发现版块所有数据]]
		 */
		public function index(){
			return array($this->_findHotGoods(), $this->_findJobs(), $this->_findSoftWare());
		}


		/**
		 * [[_findHotGoods 返回热门商品]]
		 * @returns [[Array]] [[所有热门商品]]
		 */
		private function _findHotGoods(){
			try{
				$Goods = M('good', '' ,'DB_CONFIG1'); // 125
				$lastGood = $Goods->find($Goods->max('goods_id')); // 最后一条数据
				$isUpdate = time() - $lastGood['goods_save_time']; // 是否更新
				if($isUpdate > 3600 * 24){
					$hotGoods = R('Admin/Finder/_getHotGoods');
					if ($hotGoods != Null) {
						return $hotGoods;
					}else {
						return $Goods->limit($Goods->count() - 27, 27)->select(); // 从本地取出数据
					} // 从41取出数据
				}else {
					return $Goods->limit($Goods->count() - 27, 27)->select(); // 从本地取出数据
				}
			}catch (PDOException  $e){
				return $Goods->select();
			}
		}


		/**
		 * [[_findJobs 返回兼职信息]]
		 * @returns [[Array]] [[兼职]]
		 */
		private function _findJobs(){
			return M('job', '', 'DB_CONFIG1') // 125
				   ->field('job_id, job_company, job_post_date, job_address')
				   ->limit(5)
				   ->select();
		}


		/**
		 * [_findSoftWare 返回软件推荐]
		 * @return [Array] [二维数组]
		 */
		private function _findSoftWare(){
			return M('zhuanti', '', 'DB_CONFIG1')->select(); // 125
		}

	}

?>