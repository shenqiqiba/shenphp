<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/17  23:49
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;


class Config
{
    private static $config_map=[];
    //获取配置中的单个配置
    public static function get($name,$value){
        if(isset(self::$config_map[$name])){
            return self::$config_map[$name][$value];
        }
        $filename=ROOT_PATH.DS.'config'.DS.$name.'.php';
        if(is_file($filename)){
            $stringe=include_once $filename;
            //储存
            self::$config_map[$name]=$stringe;
            if(isset($stringe[$value])){
                return $stringe[$value];
            }else{
                if(DEBUG){
                    set_exception_handler('exception_handler');
                    throw new \Exception('【神奇提示：'.$filename.'中的'.$value.'属性不存在】');
                }
            }
        }else{
            if(DEBUG){
                set_exception_handler('exception_handler');
                throw new \Exception('【神奇提示：'.$filename.'文件不存在】');
            }
        }

    }
    //获取整个配置
    public static function all($name){
        if(isset(self::$config_map[$name])){
            return self::$config_map[$name];
        }
        $filename=ROOT_PATH.DS.'config'.DS.$name.'.php';
        if(is_file($filename)){
            $stringe=include_once $filename;
            //储存
            self::$config_map[$name]=$stringe;

            return $stringe;
        }else{
            if(DEBUG){
                set_exception_handler('exception_handler');
                throw new \Exception('【神奇提示：'.$filename.'文件不存在】');
            }
        }

    }

}