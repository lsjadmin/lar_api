<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserwxModel;
use App\Model\ApiModel;
class UserApicontroller extends Controller
{
    //查询数据库userwx
    public function userInfo(){
      $uid=$_GET['id'];
     // echo $uid;
        $res=UserwxModel::where(['user_id'=>$uid])->first()->toArray();
        //echo'<pre>';print_r($res);echo'</pre>';
        $data=[
            'errno'=>0,
            'msg'=>"ok",
            'data'=>$res
        ];
        echo json_encode($data);
    }
    //post 添加一条数据
    public function create(){
            $info=[
                'name'=>request()->input('name'),
                'email'=>request()->input('email')
            ];
        $id=ApiModel::insertGetId($info);
        //echo $id;die;
        $data=[];
        if($id){
            $data['errno']=0;
            $data['msg']="ok";
            echo json_encode($data);
        }else{
            $data['errno']=50001;
            $data['msg']="no";
            echo json_encode($data);
        }
    }
    //curl(get)
    public function apicurl(){
        $url="http://1809a.apitest.com/api/u?id=16";
        //初始化curl
        $ch=curl_init($url);
        //通过 curl_setopt() 设置需要的全部选项
        curl_setopt($ch, CURLOPT_HEADER, 0);
       //然后使用 curl_exec() 来执行会话，
        curl_exec($ch);
       //执行完会话后使用 curl_close() 关闭会话
        curl_close($ch);

    }
    //curl(post)
    public function apicurlpost(){
        $url="http://1809a.apitest.com/api/apitest";
        //传输的数据（数组形式）
            $postInfo=[
                'name'=>'zhangsan',
                'email'=>'3023668879@qq.com'
            ];
         //传输的数据（字符串形式）
            $post_str=('name=lisi&email=3023668879@qq.com');
        //传输的数据（json格式）
            $post_json=json_encode($postInfo);
         //初始化curl
         $ch=curl_init();
         //通过 curl_setopt() 设置需要的全部选项
         curl_setopt($ch, CURLOPT_URL,$url);
         //禁止浏览器输出 ，使用变量接收
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_POST,1);
         //把数据传输过去
         curl_setopt($ch,CURLOPT_POSTFIELDS,$post_json);
         $res=curl_exec($ch);
         echo $res;
    }
    //中间间
    public function midtime(){
            echo "a";
    }
}
