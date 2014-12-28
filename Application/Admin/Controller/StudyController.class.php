<?php

	namespace Admin\Controller;
	use Think\Controller;
	/**
	 * 助学
	 */
	class StudyController extends Controller {

		/**
		 * [lectureList 演讲列表视图]
		 * @return [Null] [无]
		 */
		public function lectureList(){
			if (empty($_SESSION['login_name'])) {
        		$this->redirect('login/login');
        	}
			$Lecture = M('lecture', '', 'DB_CONFIG1'); // 125
			$count = $Lecture->count();// 查询满足要求的总记录数
			$Page = new \Think\Page($count, 6);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$show = $Page->show();// 分页显示输出
			$lectures = $Lecture->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('lectures',$lectures);
			$this->assign('page',$show);// 赋值分页输出
			$this->display('lectureList');
		}


		/**
		 * [lectureAddView 添加一个演讲信息]
		 * @return [Null] [无]
		 */
		public function lectureAdd(){
			if (empty($_SESSION['login_name'])) {
        		$this->redirect('login/login');
        	}
			$this->display('addView');
		}


		/**
		 * [lectureEditView 编辑一个演讲信息]
		 * @param  [Number] $id [演讲的ID]
		 * @return [Null]     [无]
		 */
		public function lectureEditView($id){
			if (empty($_SESSION['login_name'])) {
        		$this->redirect('login/login');
        	}
			$lectureInfo = M('lecture', '', 'DB_CONFIG1')->find($id);
			$this->assign('info', $lectureInfo);
			$this->display('editView');
		}


		/**
		 * [lectureEdit 演讲信息的添加/更改]
		 * @return [Null] [无]
		 */
		public function lectureEdit(){
			$lectureInfo = I('post.'); // 讲座信息
			if(!(empty($lectureInfo) && empty($_FILES))){
				$Lecture = M('lecture', '', 'DB_CONFIG1'); // 125
				if($lectureInfo['l_big_src'] != Null && $lectureInfo['l_id'] != Null) {
					$Lecture->where("l_id = {$lectureInfo['l_id']}")->save($lectureInfo); // 只更新内容
					$this->redirect('lectureList');
				}else {
					$picInfo = $this->_picUpload($lectureInfo, $_FILES); // 得到上传文件的信息
					$bigSrc = './Public/'.$picInfo['savepath'].$picInfo['savename']; // 上传的原始图片路径
					$picInfo['bigSrc'] = $lectureInfo['l_big_src'] = $bigSrc;
					$thumbSrc = $this->_buildThumb($picInfo, C('THUMB_W'), C('THUMB_H')); //  生成缩略图
					if($thumbSrc){
						$lectureInfo['l_thumb_src'] = $thumbSrc;
						if ($lectureInfo['l_id'] != Null) { // 再次上传图片
							$Lecture->where("l_id = {$lectureInfo['l_id']}")->save($lectureInfo);
							$this->redirect('lectureList');
						}else { // 添加时上传图片
							if($Lecture->add($lectureInfo)){
								$this->redirect('lectureList');
							}
						}
					}
				}
			}
		}


		/**
		 * [picUpload 上传演讲图片]
		 * @param  [Array] $lectureInfo [演讲信息]
		 * @param  [Array] $fileInfo    [上传的文件信息]
		 * @return [Array]              [上传文件信息]
		 */
		private function _picUpload($lectureInfo, $fileInfo){
			$config = array(
				'rootPath' => './Public/',
				'savePath' => 'Upload/',
				'exts' => array('jpg', 'gif', 'png', 'jpeg'),
				'saveName' => $lectureInfo['l_date'].'---'.time()
			);
			$Upload = new \Think\Upload($config);
			return $Upload->uploadOne($fileInfo['l_pic']); // 返回上传文件的信息
		}


		/**
		 * [buildThumb 生成缩略图]
		 * @param  [Array] $picInfo [原始图片信息]
		 * @param  [Number] $width   [缩略图宽]
		 * @param  [Number] $height  [缩略图高]
		 * @return [String]          [缩略图路径]
		 */
		private function _buildThumb($picInfo, $width, $height){
			$Image = new \Think\Image();
			$Image->open($picInfo['bigSrc']); // 打开原始图片
			$Image->thumb($width, $height); // 生成缩略图
			$thumbSrc = './Public/'.$picInfo['savepath'].'thumb__'.$picInfo['savename']; // 缩略图路径
			$Image->save($thumbSrc); // 储存缩略图
			return $thumbSrc;
		}


		 /**
		  * [[删除图片]]
		  * @param [[Number]] $id [[演讲的ID]]
		  */
		 public function picdel($id){
		 	$this->_delete($id, false) ? $this->redirect('lectureList') : $this->redirect('lectureList');
		 }


		 /**
		  * [[Description]]
		  * @param [[Type]] $id [[Description]]
		  */
		 public function lecdel($id){
		 	$this->_delete($id, true) ? $this->redirect('lectureList') : $this->redirect('lectureList');
		 }

		/**
		 * [[Description]]
		 * @param   [[Number]] $id   [[资源ID]]
		 * @param   [[Boolean]] $flag [[True/false]]
		 * @return  [[Boolean]]       [[true/false]]
		 */
		private function _delete($id, $flag){
			$Lecture = M('lecture', '', 'DB_CONFIG1'); // 125
			$bigSrc = $Lecture->where("l_id = '$id'")->getField('l_big_src'); // 原始图路径
			$thumbSrc = $Lecture->where("l_id = '$id'")->getField('l_thumb_src'); // 缩略图路径
			if($flag){
				$result = $Lecture->delete($id); // 删除所有
			}else {
				$result = $Lecture->where("l_id = '$id'")->setField(array('l_big_src'=>'', 'l_thumb_src'=>'')); // 删除
			}
			if($result){
				if(file_exists($bigSrc) && file_exists($thumbSrc)){
					$bigDel = unlink($bigSrc);
					$thumbDel = unlink($thumbSrc);
					return ($bigDel && $thumbDel) ? true : false;
				}
			}
		}

		public function resourceView(){
			$Resource = M('resource', '', 'DB_CONFIG1');
			$this->assign('resources', $Resource->select());
			$this->display('Study/resource');
		}

		/**
		 * [[从120获得热门资源帖子列表]]
		 * @returns [[Array]] [[返回资源帖]]
		 */
		public function getResource(){
			try{
				$Resource = M('resource', '', 'DB_CONFIG1');
				$resources = M('pre_forum_post', '', 'DB_CONFIG3')
				             ->field('pre_forum_post.tid, pre_forum_post.pid, pre_forum_post.subject, pre_forum_post.dateline, pre_forum_thread.views')
				             ->where('pre_forum_post.fid = 42 AND pre_forum_post.first = 1')
				             ->join("pre_forum_thread ON pre_forum_post.tid = pre_forum_thread.tid")
				             ->order('pre_forum_post.dateline desc')
				             ->select();
				if($resources != Null){ 
					foreach(myIconv($resources) as $resource){
						$data['r_pid'] = $resource['pid'];
						$data['r_subject'] = $resource['subject'];
						$data['r_dateline'] = $resource['dateline'];
						$data['r_views'] = $resource['views'];
						$data['r_link'] = "http://202.202.43.120/bbs/forum.php?mod=viewthread&tid=".$resource['tid']."&extra=page%3D1";
						$data['r_save_date'] = time();
						if($Resource->add($data)){
							array_push($result, $data);
						}
					}
					return $result; // 返回抓到的数据 
				}else {
					return $Resource->limit($Resource->count() - 9, 9)->select(); // 如果没有抓到则从本地数据库返回
				}
			}catch (PDOException  $e){
				return $Resource->limit($Resource->count() - 9, 9)->select(); // 如果错误返回本地数据
			}
		}

		/**
		 * [reDel 删除无用资源帖]
		 * @param  [type] $id [description]
		 * @return [type]     [description]
		 */
		public function reDel($id){
			$Resource = M('resource', '', 'DB_CONFIG1');
			if ($Resource->delete($id)) {
				$this->redirect('Study/resourceView');
			}
		}

	}










?>