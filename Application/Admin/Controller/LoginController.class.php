<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
	
    public function index(){
    	$this->assign('check_login',U('Admin:login/check_login'));
    	$this->assign('index',U('Admin:Finder/joblist'));
    	$this->display('login');
	}
		

	public function check_login(){
		$username = I('post.username');
		$password = I('post.password');
		if($username && $password && $code){
			if(C($username)){
			
				if( C($username.".password") == $password){
					session('login_name',$username);
					echo 'success';
				}else{
						echo '密码错误';
				}
				
			}else{
				echo '账号错误';
			}
		}else{
			echo "非法操作";
		}
		
	}

	public function logout(){
		if (!empty($_SESSION['login_name'])) {
			session('login_name', null);
		}
		$this->redirect('login/login');
	}
}