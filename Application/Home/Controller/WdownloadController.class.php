<?php
namespace Home\Controller;
use Think\Controller;
class WdownloadController extends Controller {
    public function index(){
		$id = I('get.id','');
		if($id){
			$table = M('creative_platform',"","DB_CONFIG1");
			$data = $table->where('id='.$id.' and detil = "fiction"')->find();
			if($data){
				$file_path= "Uploads/".$data['path'];
				$file_size = filesize($file_path);
				header("Content-type: application/octet-stream");
				header("Accept-Ranges: bytes");
				header("Accept-Length:".$file_size);
				header("Content-Disposition: attachment; filename=".$data['title'].".txt");
				
				$fp = fopen($file_path,"r");
				$buffer_size = 1024;
				$cur_pos = 0;
				
				ob_clean();
				flush();//防文件损坏 乱码 这两个很重要!!!!!!!!!!!!!
				
				while(!feof($fp)&&$file_size-$cur_pos>$buffer_size)
				{
					$buffer = fread($fp,$buffer_size);
					
					echo $buffer;
					$cur_pos += $buffer_size;
				}
				
				$buffer = fread($fp,$file_size-$cur_pos);//结束符
				echo $buffer;
				fclose($fp);
				return true;
			}else{
				$this->error('404 NOT SET');
			}
		}else{
			$tthis->error('404 NOT Find');
		}
    }
}