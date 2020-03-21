<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  21:17
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;

use shenphp\lib\Config;
class Log
{
    private static $path;
    private static $open;
    public static function init()
    {
        self::$path=Config::get('log','path');
        self::$open=Config::get('log','open');

    }
    public static function addlog($level, $message){
        self::init();
        if(self::$open){
            if(!is_dir(self::$path)){
                @mkdir(self::$path,0777,true);
            }
            $text=$level.'['.date('Y-m-d H:i:s',time()).']'.$message.';'.PHP_EOL;
            $filename=self::$path.date('Ymd',time()).'.php';
            dump($filename);
            $handle=fopen($filename,"a+");
            $str=fwrite($handle,$text);
            fclose($handle);
        }
    }

}