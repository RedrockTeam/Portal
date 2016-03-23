<?php
namespace Home\Controller;
use Think\Controller;
class InterfaceController extends Controller {
	/*公共模板接口 $table,$num,$array="",$db_config = "DB_CONFIG1",$id="id",$sc="desc",$type="select"*/
	private function common_interface($table,$num,$array="",$db_config = "DB_CONFIG1",$id="id",$sc="desc",$type="select",$where="1=1"){
		if($num != "" && $table!=""){
			$model = M($table,"",$db_config);
			$data=$model->field($array)->where($where)->order($id." ".$sc)->limit($num)->$type();
			
			
			if(empty($data)){
				//print_r($post);
				$false = array(
					status	=>	0,
					info  	=>	"此数据不存在！请检查请求参数。",
				);
				 return($false);//存在为空的情况
				//exit(0);
			}else{
				$success =array(
					status	=>	200,
					data  	=>	$data,
				);
				return ($success);//存在为空的情况
			}
		}else{
			$false = array(
					status	=>	0,
					info  	=>	'请求参数不正确！',
			);
			return ($false);
		}
	}
	
	
    /*返回common_interface函数信息*/
	public function base_common_interface($id=1,$num=5){
		$chose["hot_activity"] = "hot_activity";
		$chose["club_activity"] = "club_activity";
		$chose["match_imformation"] = "match_imformation";
		$table = $chose[$id];
		$array=array('id','title','introduce','time','url','path');
		$data = $this->common_interface($table,$num,$array,'');
		Return ($data);
	}
	
	/*返回本地缓存的bt信息*/
	public function local_bt_back($num){
		$table = "bt_info_lt";
		$data = $this->common_interface($table,$num,$array,"DB_CONFIG1");
		return ($data);
	}
	
	/*返回11抓的bt信息*/
	public function return_bt($num){
		$table = "bt_info_lt";
		$data = $this->common_interface($table,$num,$array,"DB_CONFIG2","id","desc");
		Return ($data);
	}
	
	/*返回bbs的信息*/
	public function return_bbs($num,$type,$config="DB_CONFIG3"){
		$table = "pre_forum_post";
		if($type==1){
			$where['fid'] = array('EQ','69');
			$where['subject']  = array('NEQ','');
		}else if($type==2){
			$where="1=1";
		}else if($type==3){
			$where['fid'] = 69;
			$where['subject']  = array('NEQ','');
			$array=array('subject','tid');
			$config="DB_CONFIG1";
		}else if($type==4){
			$where['fid']  = array('NEQ','69');
			$where['subject']  = array('NEQ','');
			$array=array('subject','tid');
			$config="DB_CONFIG1";
		}
		$data = $this->common_interface($table,$num,$array,$config,"dateline","desc","select",$where);
		Return ($data);
	}
	/*返回bbs的portal信息*/
	public function return_bbs_portal($num,$catid){
		$config = 'DB_CONFIG3';
		$table = "pre_portal_article_title";
		$where['catid'] = $catid;
		
		$data = $this->common_interface($table,$num,$array,$config,"aid","desc","select",$where);
		Return ($data);
	}
	
	/*返回原创平台*/
	public function return_creative($num,$type){
		$table = "creative_platform";
		$where['detil'] = $type;
		$data = $this->common_interface($table,$num,$array,$config,"id","desc","select",$where);
		Return ($data);
	}
	/*新增*/
	/*返回教务信息*/
	public function return_jwxx($num){
		$table = "jwxx";
		$data = $this->common_interface($table,$num,$array,"DB_CONFIG1","main_id","desc","select");
		Return ($data);
	}
	
	/*返回图片信息*/
	public function return_img($type){
		$num =3;
		$table = "gateway_img";
		$where['type'] = $type;
		$data = $this->common_interface($table,$num,$array,"DB_CONFIG1","id","asc","select",$where);
		Return ($data);
	}
	
		/*返回重邮新闻信息*/
	public function return_cyxw($num){
		$table = "cyxw";
		$data = $this->common_interface($table,$num,$array,"DB_CONFIG1","main_id","desc","select");
		Return ($data);
	}
	
	
		/*后台调用图片信息*/
	public function return_admin_img(){
		$num =100;
		$table = "gateway_img";
		$data = $this->common_interface($table,$num,$array,"DB_CONFIG1","id","asc","select");
		Return ($data);
	}
}