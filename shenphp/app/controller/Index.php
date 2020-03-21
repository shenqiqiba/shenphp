<?php

/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/17  22:58
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */
namespace app\controller;


use shenphp\lib\Shenphp;

class Index extends Shenphp
{
    public function index($param=[],$aa=0){

        dump('it is controller');
        dump(url('app/index/index',['page'=>3,'id'=>5,'qq'=>3333]));
        dump($param);
        dump($_GET);

        //dump($param);
        //dump($_GET);
        //dump($aa);
    }

}