<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  0:26
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */
\shenphp\lib\Route::get('/','app/index@index');
\shenphp\lib\Route::get('/cc','app/index@index');
\shenphp\lib\Route::get('/cc/id/(:id)','app/index@index');
//\shenphp\lib\Route::get('/cc/id/(:id)/page/(:page)','app/index@index');
/*
dump($matched);
$geturll=explode('/',$uri);
$countt=count($geturll);
$ii=0;
while($ii<$countt){
    $geturlfenge[$geturll[$ii]]=$geturll[$ii+1];
    $ii=$ii+2;
}*/
//\shenphp\lib\Route::get('/bbcc/s/id/(:any)','app/index@index');
//\shenphp\lib\Route::get('/bbcc/s/id/(:id)/(:page)','app/index@index');