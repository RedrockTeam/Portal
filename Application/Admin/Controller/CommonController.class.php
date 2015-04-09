<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {

	/*后台公共活动*/
    public function index(){
		check_login();
		$to = I("get.to","hot_activity");
		
		$model = M($to,"","DB_CONFIG1");
		
		$count      = $model->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,15);
		$Page->setConfig('header','共 <span style="font-weight:bold;color:#FF6600;"> %TOTAL_ROW% </span>条记录');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('prev','上一页');
		$Page->setConfig('theme','%HEADER% <span style="font-weight:bold;color:blue;">%NOW_PAGE%</span>/<span style="font-weight:bold;">%TOTAL_PAGE%</span>页  %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$this->assign('database',$to);// 赋值分页输出
		//$this->ajaxReturn($list);
		//print_r($show);
        $this->display("index/index");
    }
	
	/*公共修改页模板*/
	public function edit_show(){
		check_login();
		$id = I("get.edit_id","");
		$table = I("get.table");
		if($id!= NULL && $table!=NULL){
			$model = M($table,"","DB_CONFIG1");
			$data=$model->where("id = ".$id)->find();
			//print_r($data);
			$this->assign("data",$data);
			$this->assign("table",$table);
			$this->assign("url",U("home:common/edit_data"));
			$this->assign("back",U("home/common/index/to/".$table));
			$this->display("index/edit_show");
		}else{
			$this->error("非法操作！");
		}
	} 
	
	/*公共修改页*/
	public  function edit_data(){
		check_login();
		$post = I("post.");
		if($post["id"]==""){
			$post['id']=-1;
		}
		
		$rtManageArray  =  array_count_values($post); 
		if(!empty($rtManageArray[''])){
			//print_r($post);
			 $this->error("信息不完全");//存在为空的情况
			//exit(0);
		}else{
		
			  if($post["table"]=="creative_platform" && $_FILES["photo"]["name"]== ''){
					$this->error("未上传文件!");
			  }
			  
			 if($_FILES["photo"]["name"]!=""){
				$config = array(
				'maxSize'    =>    1024000,
				'rootPath'   =>    './Uploads/',
				'savePath'   =>    $post['table'],
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    true,
				'subName'    =>    array('',''),
				);
				
				if($post["table"]=="creative_platform"){
					if($post["detil"]=="fiction"){
						$content = array('txt','doc','7z','zip','rar');
					}else{
						$content = array('jpg', 'gif', 'png', 'jpeg');
					}
					$config['exts'] = $content;
				}
				
				$upload = new \Think\Upload($config);// 实例化上传类
				$info   =   $upload->upload();
				
				if(!$info) {// 上传错误提示错误信息
					$this->error($upload->getError());
				}else{// 上传成功 获取上传文件信息
					
						$post["path"] = $info["photo"]['savepath'].$info["photo"]['savename'];
						//echo $post["img"];
				}
			}
			
			$table = $post["table"];
			unset($post["table"]); 
			$model =  M($table,"","DB_CONFIG1");
			$post["time"] = time();
			//print_r($post);
			if($post["id"]!=-1){
				$model->save($post);
			}else{
				unset($post["id"]); 
				$model->add($post);
			}
			$this->success("操作成功！",U("Admin/common/index/to/".$table));
			
		}	
	}
	
	public function delete(){
		check_login();
		$id = I("get.id",'','number_int');
		$table = I("get.table",'');
		$field = I("get.field",'id');
		if($id != "" && $table != ''){
			$M = M($table);
			$M->where($field .' = '. $id )->delete();
			$this->success('删除成功!',U("Admin/common/index/to/".$table));
		}else{
			$this->error('非法操作');
		}
	}
	
	
	/*主页图片上传*/
	public function upload_redrock_img(){
		check_login();
		$interface = A('Home/interface');
		$img_rdjj = $interface->return_img(1);
		$img_rdjj_top = $interface->return_img(2);
		$img_rdjj_bottom = $interface->return_img(3);
		$img_wxc = $interface->return_img(4);
		$img_wxc_top = $interface->return_img(5);
		$img_wxc_bottom = $interface->return_img(6);
		$img_fiction = $interface->return_img(7);
		
		$this->assign("img_rdjj",$img_rdjj['data']);//热点焦距
		$this->assign("img_rdjj_top",$img_rdjj_top['data']);
		$this->assign("img_rdjj_bottom",$img_rdjj_bottom['data']);
		
		$this->assign("img_wxc",$img_wxc['data']);//微相册
		$this->assign("img_wxc_top",$img_wxc_top['data']);
		$this->assign("img_wxc_bottom",$img_wxc_bottom['data']);
		
		$this->assign("jwxx",$jwxx['data']);//教务信息
		$this->assign("img_fiction",$img_fiction['data']);//微小说
		//print_r();
		$this->assign("upload_img",U("common/upload"));//微小说
		$this->display('img/index');
	}
	
	public function upload(){
		check_login();
		$data['id']=I('post.id');
		$data['title']=I('post.title');
		$data['url']=I('post.url');
		if($data['title']&&$data['id']&&$data['url']){
			$config = array(
				'maxSize'    =>    3145728,
				'rootPath'   =>    './Uploads/',
				'savePath'   =>    'gateway_img/',
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    false,
				'subName'    =>    array('',''),
			);
			$upload = new \Think\Upload($config);// 实例化上传类
		
			// 上传文件 
			$info   =   $upload->upload();
			if(!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			}else{// 上传成功
				$data['img_path'] = $info['photo']['savepath'].$info['photo']['savename'];
				$allPath = './Uploads/'.$data['img_path'];
				//print_r($data );
				M('gateway_img')->where('id='.$data['id'])->save($data);
				
				$image = new \Think\Image(); 
				$do = $image->open($allPath);
				
				
				$font[0] ='Just By Redrock';
				$font[1] ='Just For You';
				$font[2] ='Just Do IT';
				$chose_int = ($data['id']-1)%3;
				
				if($data['id'] == 1   || $data['id'] == 2  || $data['id'] == 3 || 
					$data['id'] == 10 || $data['id'] == 11 || $data['id'] == 12){
					$the_width = 290;
					$the_height = 350;
					$font_size = 15;
					$color = '#009999';
					$do->thumb($the_width, $the_height,\Think\Image::IMAGE_THUMB_CENTER)->save($allPath);
				}else if($data['id'] == 19 || $data['id'] == 20){
					$the_width = 290;
					$the_height = 195;
					$font_size = 8;
					$font[0] ='Redrock';
					$font[1] ='Redrock';
					$font[2] ='Redrock';
					$color = (($data['id']%2)?'#FF0000':'#000000');
					$do->thumb($the_width, $the_height,\Think\Image::IMAGE_THUMB_CENTER)
				   ->text($font[$chose_int],'./Public/zt/InkBarb.ttf',$font_size,$color,\Think\Image::IMAGE_WATER_NORTHEAST )->save($allPath);
				}else{
					$the_width = 290;
					$the_height = 175;
					$font_size = 15;
					$color = '#009999';
					$do->thumb($the_width, $the_height,\Think\Image::IMAGE_THUMB_CENTER)->save($allPath);
				}/* */
				
				$this->success('修改成功！',"admin/common/upload_redrock_img");
			}
		}else{
			$this->error("非法操作！");
		}
	}
	
	
}