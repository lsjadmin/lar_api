<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ApiModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{
    //注册
    public function adduser(){
        $pass1=request()->input('pass1');
        $pass2=request()->input('pass2');
        $email=request()->input('email');
        if($pass1!==$pass2){
                $response=[
                    'errno'=>'50002',
                    'msg'=>'密码和确认密码不一致'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        
        //把密码转换一下（哈希）
        $pass=password_hash($pass1,PASSWORD_BCRYPT);
        //查询数据库
        $res=ApiModel::where(['email'=>$email])->first();
        if($res){
            $response=[
                'errno'=>'50003',
                'msg'=>'此邮箱已经存在'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
       
        //添加数据库
        $info=[
            'name'=>request()->input('name'),
            'email'=>$email,
            'pass'=>$pass,
            'age'=>request()->input('age'),
            'time'=>time()
        ];
        $id=ApiModel::insertGetId($info);
        if($id){
            $response=[
                'errno'=>'0',
                'msg'=>'ok'
            ];
        }else{
            $response=[
                'errno'=>'50004',
                'msg'=>'储存失败'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }
    //登陆
    public function login(){
        $email=request()->input('email');
        $pass=request()->input('pass');
        $res=ApiModel::where(['email'=>$email])->first();
        if($res){   
            if(password_verify($pass,$res->pass)){
                //登陆正确获得token(存到redis)
                $key="lar_login_token.$res->api_id";
                $token=$this->postlogintoken($res->api_id);
               // echo $token;die;l
                Redis::set($key,$token);
                Redis::expire($key,604800);
                $response=[
                    'errno'=>'0',
                    'msg'=>'ok',
                    'token'=>[
                        'token'=>$token,
                    ]
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }else{
                $response=[
                    'errno'=>'50010',
                    'msg'=>'密码不正确'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            $response=[
                'errno'=>'50011',
                'msg'=>'用户不存在'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }
    //获得登陆用的 token
    function postlogintoken($id){
        $range=Str::random(10);
        $token=substr(sha1(time().$id.$range),5,15); //sha1 计算字符串散值 （加密差不多）
        return $token;
    }
    //用户中心
    public function user(){
        echo "aaaaaa";
    }

}
