<?php
class action
{
		private $username;
		private $password;
		private $email;  	
    	public function register($username,$password,$email)
		{
    		$this->username=$username;
    		$this->password=$password;
    		$this->email=$email;
			if($num_username==0 && $num_email==0){
				return true;
			} else {
				if($num_username!=0) {
					echo "该用户已注册，请更改用户名重新注册";
					return false;
				}
				if($num_email!=0) {
					echo "该邮箱已注册，请更改邮箱重新注册";
				return false;
				}
			}
		}
    
}
?>