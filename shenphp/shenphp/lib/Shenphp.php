<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/17  21:36
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;
if (!defined('SHEN')) {
    header('location:../index.php');
}

use shenphp\lib\Config;
use shenphp\lib\Log;
use shenphp\lib\Page;
class Shenphp
{
    public $assgins=[];

    public function db(){
        $database=Config::all('database');
        return new Db($database);
    }
    public static function run(){
        $t1=microtime(true);
        self::autoload();
        include_once ROOT_PATH.DS.'route.php';
        \shenphp\lib\Route::dispatch();




       if(DEBUG){
          // console_log ('<div style="position:fixed; bottom:0;background-color: black;color: #fff;width: 100%">');
           console_log('=================调试查看数据==========================');
           /*查看引入文件*/
           $included_files = get_included_files();
           $i=0;
           foreach($included_files as $k=> $v){
               console_log ($v);
               $i=$i+1;
           }
           console_log ('一共'.$i.'个文件');

           //console_log ("<pre>".print_r($included_files). "<pre>");

           $bytes = memory_get_peak_usage();
           console_log('内存占用：'.self::formatBytes($bytes));
           /*获取载入的php文件大小*/

           /*查看执行时间*/
           $t2=microtime(true);
           $time1=$t2-$t1;
           console_log('【神奇提示：本次耗费时间：'.($time1).'秒】');
           //console_log ('</div>');
       }

       // \shenphp\lib\Log::addlog(1,'测试下日志');


       // \shenphp\lib\cache::set('AA','WW');
       // \shenphp\lib\cache::set('2AA','2WW');



    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = array("b", "kb", "mb", "gb", "tb");
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . " " . $units[$pow];
    }


    //自动加载
    private static function autoload(){
        //z最先加载核心类
        spl_autoload_register('self::loadShenphp');
        //spl_autoload_register('self::loadModel');
        //指定类 两种方法 'self::loadController'也可以
        //spl_autoload_register(array(__CLASS__,'loadController'));
        //spl_autoload_register('self::loadController');
        //spl_autoload_register('self::loadVendor');
    }

    private static function loadShenphp($namespace){
        $filename=ROOT_PATH.DS.$namespace.'.php';
        if(is_file($filename)){
            include_once $filename;
        }else{
            if(DEBUG){
                set_exception_handler('exception_handler');
                throw new \Exception('【神奇提示：类文件不存在'.$filename.'】');
            }
        }
    }

    public function assign($name,$value){
        $this->assgins[$name]=$value;
    }
    public function display($file=''){
        if(!is_file($file)){
            if(DEBUG){
                set_exception_handler('exception_handler');
                throw new \Exception('【神奇提示：模板文件不存在'.$file.'】');
            }else {
                show404();
            }
            exit();
            return;
        }
        extract($this->assgins);
        include $file;
    }



}