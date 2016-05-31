<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller {
	/*抓教务信息*/
	public function regist_jwxx(){
		
		$array['page'] = 1;
		$contents = $this->get_api(C('api_jwlb'),$array);
		$array = json_decode($contents,1);
		$data = $array['data'];
		//$M = M("jwxx");
		$M = M("jwxx","","DB_CONFIG1");
		foreach(array_reverse($data) as $head){//倒叙插入数据库
			$has = $M->where('id="'.$head['id'].'"')->find();
			//print_r($has);
			if(empty($has)){
				$d['id'] = $head["id"];
				$detil = json_decode( $this->get_api(C('api_jwnc'), $d) ,1);
				
				$regist["time"]=strtotime($head["date"]);
				$regist["title"]=$detil["data"]['title'];
				$regist["id"]=$detil["id"];
				$regist["content"]=$detil["data"]['content'];
				$regist["read"] = $head["read"];
				$M->add($regist);
				//print_r($regist);
			}else{
				echo ($has['id']."已存在。\n");
			}
		}
		echo "抓抓抓OK~";
	}
	
		/*抓重邮动态信息*/
	public function regist_cyxw(){
		
		$array['page'] = 1;
		$contents = $this->get_api(C('api_cyxw'),$array);
		$array = json_decode($contents,1);
		$data = $array['data'];
		
		$M = M("cyxw","","DB_CONFIG1");
		foreach(array_reverse($data) as $head){//倒叙插入数据库
			$has = $M->where('id="'.$head['id'].'"')->find();
			
			if(empty($has)){
				$d['id'] = $head["id"];
				$detil = json_decode( $this->get_api(C('api_cync'), $d) ,1);
				
				$regist["time"]=$head["date"];
				$regist["title"]=$head["title"];
				$regist["id"]=$detil["id"];
				$regist["content"]=$detil["data"]['content'];
				$regist["pics"] = $detil["data"]['pics'][0];
				$M->add($regist);
				print_r($regist);
			}else{
				echo ($has['id']."已存在。\n");
			}
		}
		echo "抓抓抓OK~";
	}
	
	/*抓11的BT信息*/
	public function regist_bt(){
		$local_bt = M("bt_info_lt","","DB_CONFIG1");
		$bt_11 = A("interface");
		$data = $bt_11->return_bt(20);
		
		foreach(array_reverse($data['data']) as $key => $value){
			$has = $local_bt->where("id=".$value['id'])->find();
			if(empty($has)){
				$local_bt->add($value);
				print_r($value);
				echo "<br><br>";
			}else{
				echo "id:".$value['id']."已存在<br/>";
			}
		}
		$this->regist_bt_img();
		echo "BT抓取完毕";
	}
	
	/*抓bt图片*/
	public function regist_bt_img(){
		$bt_img = M('bt_info_lt','',"DB_CONFIG1");
		$img = $bt_img->limit(3)->order("id desc")->select();
		foreach( $img as $key=>$value){
			$file =  file_get_contents($value['p_store_name']);
			$str = substr($value['p_store_name'],32);
			file_put_contents('./Uploads/pic/'.$str ,$file);
			echo $str.'缓存成功<br/>';
		}
		
	}

	
	/*抓BBS forum_post信息*/
	public function regist_bbs_forum_post(){
		$local_bbs = M("pre_forum_post","","DB_CONFIG1");
		$bbs_11 = A("interface");
		$data = $bbs_11->return_bbs(20,2);
		$wd_data = $bbs_11->return_bbs(20,1);
		foreach(	($data['data']) as $key => $value){
			$has = $local_bbs->where("pid=".$value['pid'])->find();
			if(empty($has)){
			
				$value['author']=mb_convert_encoding($value['author'],'UTF-8','GBK');
				$value['subject']=mb_convert_encoding($value['subject'],'UTF-8','GBK');
				$value['message']=mb_convert_encoding($value['message'],'UTF-8','GBK');
					
				$local_bbs->add($value);
				print_r($value);
				echo "<br><br>";
			}else{
				echo "id:".$value['pid']."已存在<br/>";
			}
		}
		
		foreach(	($wd_data['data']) as $key => $value){
			$has = $local_bbs->where("pid=".$value['pid'])->find();
			if(empty($has)){
				$value['author']=mb_convert_encoding($value['author'],'UTF-8','GBK');
				$value['subject']=mb_convert_encoding($value['subject'],'UTF-8','GBK');
				$value['message']=mb_convert_encoding($value['subject'],'UTF-8','GBK');
				$local_bbs->add($value);
				print_r($value);
				echo "<br><br>";
			}else{
				echo "id:".$value['pid']."已存在<br/>";
			}
		}
		echo "BBS抓取完毕";
	}
	
	/*抓BBS portal信息*/
	public function regist_bbs_portal(){
		$local_bbs = M("pre_portal_article_title","","DB_CONFIG1");
		$bbs_11 = A("interface");
		$data[2] = $bbs_11->return_bbs_portal(20,2);
		$data[3] = $bbs_11->return_bbs_portal(20,3);
		$data[4] = $bbs_11->return_bbs_portal(20,4);
		$data[5] = $bbs_11->return_bbs_portal(20,5);
		for($i=2;$i<=5;$i++){
			foreach(	($data[$i]['data']) as $key => $value){
				$has = $local_bbs->where("aid=".$value['aid'])->find();
				if(empty($has)){
					$value['title']=mb_convert_encoding($value['title'],'UTF-8','GBK');
					$value['author']=mb_convert_encoding($value['author'],'UTF-8','GBK');
					$value['from']=mb_convert_encoding($value['from'],'UTF-8','GBK');
					$value['summary']=mb_convert_encoding($value['summary'],'UTF-8','GBK');
					$local_bbs->add($value);
					print_r($value);
					echo "<br><br>";
				}else{
					echo "id:".$value['aid']."已存在<br/>";
				}
			}
		}
	}
	
	/*curl通用函数*/
	private function get_api($web,$post=''){
		header('Content-Type:application/json; charset=utf-8');
		$send="";
		foreach($post as $p=>$value){
			
			$send .= '&'.$p."=".$value;
		}
		$curlPost = $send;
		// 初始化一个curl对象	
		$curl = curl_init();

		// 设置url
		curl_setopt($curl,CURLOPT_URL,$web);

		// 设置参数，输出或否
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

		//数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$curlPost);

		// 运行curl，获取网页。
		$contents = curl_exec($curl);
		// 关闭请求
		curl_close($curl);
		return $contents;
	}
	
	/*加载函数*/
	public function run(){
		/*抓教务信息*/
		$this->regist_jwxx();
		/*抓重邮动态信息*/
		$this->regist_cyxw();	
		/*抓11的BT信息*/
		$this->regist_bt();
		/*抓bt图片*/
		$this->regist_bt_img();
		/*抓BBS forum_post信息*/
		$this->regist_bbs_forum_post();
		/*抓BBS portal信息*/
		$this->regist_bbs_portal();
	}
	
	
	
}