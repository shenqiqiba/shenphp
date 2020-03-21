<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/19  14:05
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;


class Cookie
{
    private static $_prefix = 'shenqi';
    private static $_time = 3600;
    //设置指定的COOKIE值
    //$expire 过期时间,默认为0,表示随会话时间结束
    //cookie的过期时间必须是time()+时间
    public static function set($key, $value, $expire = 3600){
        self::$_time=\shenphp\lib\Config::get('config','cookie_time');
        if($expire==0){
            $expire=time()+self::$_time;
        }else{
            $expire=time()+$expire;
        }
        self::$_prefix=\shenphp\lib\Config::get('config','cookie_prefix');
        $key = self::$_prefix . $key;
        setcookie($key, rawurlencode($value), $expire);
        $_COOKIE[$key] = $value;
    }

    public static function get($key, $default = NULL){
        $key = self::$_prefix . $key;
        if(empty($_COOKIE[$key])){
            return false;
        }else{
            return $_COOKIE[$key];
        }

    }
    //删除
    public static function del($key){
        $key = self::$_prefix . $key;
        if(empty($_COOKIE[$key])){
            return;
        }
        setcookie($key, '', time() - 2592000);
        unset($_COOKIE[$key]);

    }

}