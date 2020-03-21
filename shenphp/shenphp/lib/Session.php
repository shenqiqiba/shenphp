<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/19  13:56
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;


class Session
{

    private static $prefix='shenqi';


    //初始化
    public static  function chushihua($lifeTime=0,$prefix='')
    {
        if($lifeTime==0){
            $lifeTime = \shenphp\lib\Config::get('config','session_time');
        }
        if($prefix==''){
            self::$prefix=\shenphp\lib\Config::get('config','session_prefix');
        }else{
            self::$prefix=$prefix;
        }
        if (session_status() !==PHP_SESSION_ACTIVE) {
            session_start();
        }

    }
    //设置
    public static function set($key,$value=''){
        self::chushihua();
        $key=self::$prefix.$key;
        $_SESSION[$key]=$value;
        return ;
    }
    //获取
    public static function get($key){
        self::chushihua();
        $newkey=self::$prefix.$key;
        if(self::has($key)){
            return $_SESSION[$newkey];
        }else{
            return false;
        }
    }

//判断是否存在
    public static function has($key){
        self::chushihua();
        $key=self::$prefix.$key;
        if(empty($_SESSION[$key])){
            return false;
        }else{
            return true;
        }

    }

    //删除
    public static function delete($key){
        self::chushihua();
        $key=self::$prefix.$key;
        unset($_SESSION[$key]);
        return;
    }

    //清空
    public static function delall(){
        self::chushihua();
        $_SERVER=[];
        setcookie(session_name(),'',time()-3600,'/');
        session_destroy();
        session_unset();

    }
}