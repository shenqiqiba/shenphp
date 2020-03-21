<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  21:45
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;

use shenphp\lib\Config;
class Cache
{
    private static $option;
    private static $caches;


    //初始化
    public static function init($options='',$change=false)
    {
        if($options!='' && $change){
            self::$caches=[];
        }
        if(!is_object(self::$caches)){
            if($options==''){
                $option=Config::all('cache');
                self::$option=$option;
                $class=ucfirst(self::$option['type']);
                $class='\\shenphp\\lib\\cache\\' .$class;
                //dump(self::$caches);
                self::$caches=new $class($option);

            }else{
               self::$option=$options;
                $class=ucfirst($options['type']);
                $class='\\shenphp\\lib\\cache\\' .$class;
                self::$caches=new $class($options);
            }
        }


    }


    public static function set($key,$data,$lifetime = 4747){
        self::init();
        return self::$caches->set($key, $data, $lifetime);
    }

    public static function get($key){
        self::init();
        return self::$caches->get($key);
    }

    public static function has($key){
        self::init();
        return self::$caches->has($key);
    }

    public static function del($key){
        self::init();
        return self::$caches->del($key);
    }

    public static function clear(){
        self::init();
        self::$caches->clear();
    }




}