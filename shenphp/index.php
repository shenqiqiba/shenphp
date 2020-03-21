<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/17  20:22
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('SHEN',true);//定义是否从入口文件进入
define('SHENPHP_VERSION', '0.1.0');
define('ROOT_PATH',__DIR__);//定义常量 根目录
define('APP_PATH',ROOT_PATH.DS.'app');//开启调试
define('SHENPHP_PATH',ROOT_PATH.DS.'shenphp');//定义常量 核心路径
define('DEBUG',true);//开启调试
include_once SHENPHP_PATH.DS.'function.php';//引入核心函数

define('ADMIN_VIEW',ROOT_PATH.DS.'admin'.DS.'view'.DS);//定义后台视图
define('OFFICIAL_SERVICE_HOST', 'http://shenqiyu.com/');//官方服务域名
//define('ADMIN_VIEW',APP_PATH.DS.'view'.DS);//定义后台视图
define('INDEX_VIEW',__DIR__.DS.'diy'.DS.'view');//定义前台视图

ini_set('date.timezone','Asia/Shanghai');//设置一下站点的时区
ini_set('display_error','On');//开启调试
include_once ROOT_PATH.DS.'common.php';//准备启动框架


include_once SHENPHP_PATH.DS.'base.php';//准备启动框架
