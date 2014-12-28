<?php


	function check_login($level=2){
		$login_name=session('login_name');
		if($login_name=='root'||$login_name=='redrock'||$login_name=='worker'){
			check_level($level);
		}else{
			
			header("location:".U("Admin:login/index"));
		}
	}	

	function check_level($level){
		$name = session('login_name');
		if($name && C($name.".level") >= $level){
			
		}else{
			
			echo '<script language="javascript"> 
			alert("your level is not enough,go back"); 
			window.history.back(-1); 
			</script> ';
			exit();
		}
		
	}

?>