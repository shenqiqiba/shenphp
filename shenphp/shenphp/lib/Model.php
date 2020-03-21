<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/19  14:26
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;
use shenphp\lib\Config;
use shenphp\lib\Db;

class Model extends Db
{
    public function __construct()
    {
        $options=Config::all('database');
        parent::__construct($options);
    }

}