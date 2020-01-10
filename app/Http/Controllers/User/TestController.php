<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\TestModel;
class TestController extends Controller
{
    public function reg()
    {	
        // echo phpinfo();die;
        // echo 1;die;
    	$name=request()->input('name');
    	$email=request()->input('email');
    	$pwd=request()->input('pwd');
    	$pwd=password_hash($pwd, PASSWORD_DEFAULT);
    	//验证姓名 
    	$bool=TestModel::where('name',$name)->first();
    	if ($bool) {
    		return json_encode(['msg'=>'姓名重复']);die;
    	}
    	$data=[
    		'name'=>$name,
    		'email'=>$email,
    		'pwd'=>$pwd
    	];


    	$res=TestModel::create($data);
    	if ($res) {
    		return json_encode(['code'=>1,'msg'=>'添加成功']);
    	}else{
    		return json_encode(['code'=>444,'msg'=>'注册失败']);


    	}
    }


    public function login()
    {
		$code=request()->input('code');
    	$pwd=request()->input('pwd');
    	if (strpos($code, "@")) {
    		//按邮箱查
    		$bool=TestModel::where('email',$code)->first();
    		if ($bool) {
    			$res=password_verify($pwd,$bool->pwd);
    			if ($res) {
    				//把信息存在redis中
    				$token=md5(uniqid().rand(1000,9999)); 
    				Redis::set($bool->email,$token,7200); 
                   
                    echo $token;
    				return json_encode(['code'=>1,'msg'=>'登录成功']);


    			}else{
    				return json_encode(['code'=>444,'msg'=>'登录失败']);
    			}
    		}else{
    			return json_encode(['code'=>444,'msg'=>'输入的信息错误']);
    		}
    	}else{
    		$bool=TestModel::where('name',$code)->first();
    		if ($bool) {
    			$res=password_verify($pwd,$bool->pwd);
    			if ($res) {
    				//把信息存在redis中
                    $token=md5(uniqid().rand(1000,9999)); 
                    Redis::set($bool->name,$token,7200); 
                   
                    echo $token;
    				return json_encode(['code'=>1,'msg'=>'登录成功']);
    			}else{
    				return json_encode(['code'=>444,'msg'=>'登录失败']);
    			}
    		}else{
    			return json_encode(['code'=>444,'msg'=>'输入的信息错误']);
    		}
    		echo "名字";
    	}
	}


    public function getinfo()
    {
    	//根据传递过来的token 去redis里查询 
    	$name=request()->input('name');
    	$token=request()->input('token');
    	$rtoken=Redis::get($name);
    	if (!$rtoken) {
    		return json_encode(['code'=>444,'msg'=>'没有缓存信息请重新登录']);die;
    	}


    	if ($token==$rtoken) {
    		return json_encode(['code'=>1,'msg'=>'您请求的信息已成功']);die;
    	}else{
    		return json_encode(['code'=>444,'msg'=>'查询不到您的信息请重新登录']);
    	}


    }


}

