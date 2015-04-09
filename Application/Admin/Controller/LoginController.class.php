<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
	
    	$verify =U('Admin:login/verify');
    	$this->assign('verify',$verify);
    	
    	$this->assign('check_login',U('Admin:login/check_login'));
    	$this->assign('index',U('Admin:Finder/joblist'));
    	
    	$this->display('login');
	}
		
	public function verify()
	{
			$config = array(
					'fontSize' => 15, // 验证码字体大小
					'length' => 4, // 验证码位数
					//'imageH' => 60,
					//'imageW' => 200,
			);
			 
			$Verify = new \Think\Verify($config);
			$Verify->entry();
	}
		
	
	public function check_login(){
		$username = I('post.username');
		$password = I('post.password');
		$code = I('post.verify');
		if($username && $password && $code){
			$verify = new \Think\Verify();
			if($verify->check($code))
			{
				
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
				echo "验证码错误!";
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