<?php 
	/**
	 * [[编码转化]]
	 * @param   [[Array]] &$array [[GBK数组]]
	 * @returns [[Array]] [[UTF8数组]]
	 */
	function myIconv(&$array){
		foreach ($array as &$arr){
			if (is_array($arr)){
			 	$arr = myIconv($arr);
			}else {
				$arr = iconv('gbk', 'utf-8', $arr);
			}
		}
		return $array;
	}

	function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){  
	    if(function_exists("mb_substr")){  
	        if($suffix)  
	             return mb_substr($str, $start, $length, $charset)."...";  
	        else 
	             return mb_substr($str, $start, $length, $charset);  
	    }  
	    elseif(function_exists('iconv_substr')) {  
	        if($suffix)  
	             return iconv_substr($str,$start,$length,$charset)."...";  
	        else 
	             return iconv_substr($str,$start,$length,$charset);  
	    }  
	    $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";  
	    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";  
	    $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";  
	    $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";  
	    preg_match_all($re[$charset], $str, $match);  
	    $slice = join("",array_slice($match[0], $start, $length));  
	    if($suffix) return $slice."…";  
	    return $slice;
	}

	function GrabImage($url,$filename="") {   
		if($url != ""){
			if($filename == "") {   
				$ext=strrchr($url,".");   
				if($ext ==".gif" || $ext ==".jpg" || $ext == ".png" || $ext == ".jpeg"){
					$filename=date("dMYHis").$ext;   
				}
				ob_start();   
				readfile($url);   
				$img = ob_get_contents();   
				ob_end_clean();   
				$size = strlen($img);   
				$fp2=@fopen($filename, "a");   
				fwrite($fp2,$img);   
				fclose($fp2);   
				return $filename;
			} 
		}
	}   


 ?>