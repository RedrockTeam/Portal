<?php

	namespace Home\Controller;
	use Think\Controller;
	class StudyController extends Controller {

		/**
		 * [[对外接口]]
		 * @returns [[Array]] [[助学版块所有数据]]
		 */
		public function index(){
			return array($this->_findLecture(), $this->_findResource());
		}


		/**
		 * [[_findLecture 返回演讲信息]]
		 * @returns [[Array]] [[演讲信息]]
		 */
		private function _findLecture(){
 			return M('lecture', '', 'DB_CONFIG1')->select(); // 125
		}


		/**
		 * [[_findResource 返回论坛资源帖子列表]]
		 * @returns [[Array]] [[资源帖子列表]]
		 */
		private function _findResource(){
			try{
				$Resource = M('resource', '', 'DB_CONFIG1'); // 125
				$lastResource = $Resource->find($Resource->max('r_id')); // 最后一条记录
				if ($lastResource == Null) {
					return R('Admin/Study/getResource');
				}else {
					$isUpdate = time() - $lastResource['r_save_date']; // 是否更新
					return $isUpdate > 3600 * 24 ? R('Admin/Study/getResource') : $Resource->limit($Resource->count() - 4, 4)->select();
				}
			}catch (PDOException  $e){
				return $Resource->limit($Resource->count() - 4, 4)->select();
			}
		}

		public function test(){
			print_r($this->_findResource());
		}

	}










?>