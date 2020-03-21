<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  0:06
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */
return [
    'database_type' => 'mysql',
    //库名
    'database_name' => 'bb',
    //服务器 建议使用127.0.0.1/localhost
    'server' => '127.0.0.1',
    'username' => 'root',
    'password' => 'root',

    // [可选配置] 编码和端口
    'charset' => 'utf8',
    'port' => 3306,

    //  表前缀
    'prefix' => 'sq_',

    /*关闭下面的选项能提升速度*/

    // 启用日志记录（默认情况下禁用日志记录以获得更好的性能）
    'logging' => false,
    // [可选]MySQL套接字（不应与服务器和端口一起使用 否则速度奇慢无比）
    /*'socket' => '/tmp/mysql.sock',*/
    // [可选]driver_连接选项，请阅读http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
         //保持连接
        PDO::ATTR_PERSISTENT => true,
        //保留数据库驱动返回的列名。 更多配置参照pdo
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ],
    // [optional] Medoo将在连接到数据库进行初始化后执行这些命令
    'command' => [
        'SET SQL_MODE=ANSI_QUOTES'
    ]

];