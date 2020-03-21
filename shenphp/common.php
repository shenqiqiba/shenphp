<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/19  19:39
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

/*公共函数 全局调用*/
///提取文章的第一章图片
function getimg($comtent){
    preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i', $comtent, $matches);
    if(count($matches)!==0){
        return $matches[1];}else{
        return '/static/images/pic/ap'.rand(1,10).'.jpg';
    }
}
/*获取文件大小*/
function getsize($size)
{
    $arr=array('B','KB','M','G','T');
    //$arr['B','KB','M','G','T'];
    $i=0;
    while($size>=1024){
        $size=$size/1024;
        $i++;
    }
    return round($size,2).$arr[$i];
}
/*判断是不是图片*/
function isImage($filename)
{
    $types = '.gif|.jpeg|.png|.bmp';  //定义检查的图片类型
    if(file_exists($filename)){
        $info = @getimagesize($filename);
        if ($info==false){
            return false;
        }
        $ext = image_type_to_extension($info['2']);
        return stripos($types,$ext);
    }else{
        return false;
    }
}
//检测目录是否存在 不存在就创建
function markdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!markdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}
//缓存导航无限极 带子类
function getnaviqiantai(){
    $cache=new \shenphp\lib\Cache();
    if($cache::has('naviqiantailistTree')){
        $typelist=$cache::get('naviqiantailistTree');
    }else{
        $database=\shenphp\lib\Config::all('database');
        $db=new \shenphp\lib\Db($database);
        $typelist=$db->select('navi','*',["ORDER" => ["navi_sort" => "ASC"],]);
       // $typelist=$db::name('navi')->order('navi_sort asc')->select();
       // $tool=new \shenphp\core\Tool();
        $tool=new \extend\Tool();
        $typelist=$tool->generateTree($typelist,'navi_id','navi_pid');
        $cache::set('naviqiantailistTree',$typelist);
    }
    return  $typelist;
}
//缓存导航无限极 不带子类 后台用
function getnaviTrees(){
    $cache=new \shenphp\lib\Cache();
    if($cache::has('navilistTree')){
        $typelist=$cache::get('navilistTree');
    }else{
        $database=\shenphp\lib\Config::all('database');
        $db=new \shenphp\lib\Db($database);
        $typelist=$db->select('navi','*',["ORDER" => ["navi_sort" => "ASC"],]);
        $tool=new \extend\Tool();
        $typelist=$tool->getTree($typelist,0,0,'navi_id','navi_pid');
        $cache::set('navilistTree',$typelist);
    }
    return  $typelist;
}
//缓存无限极分类
function gettypeTrees(){
    $cache=new \shenphp\lib\Cache();
    if($cache::has('typelistTree')){
        $typelist=$cache::get('typelistTree');
    }else{
        $database=\shenphp\lib\Config::all('database');
        $db=new \shenphp\lib\Db($database);
        $typelist=$db->select('type','*',["ORDER" => ["type_sort" => "ASC"],]);
        $tool=new \extend\Tool();
        $typelist=$tool->getTree($typelist,0,0,'type_id','type_pid');
        $cache::set('typelistTree',$typelist);
    }
    return  $typelist;
}
//获取用户组id的用户组名
function getgroupnames($group_id){
    if($group_id==0){
        return '无用户组';
    }
    $groups=getgroupTrees();
    return $groups[$group_id]['group_name'];
}
//获取分类id的分类名
function gettypenames($type_id){
    if($type_id==0){
        return '顶级分类';
    }
    $cache=new \shenphp\lib\Cache();
    if($cache::has('typelistidTree')){
        $newlist=$cache::get('typelistidTree');
    }else{
        $database=\shenphp\lib\Config::all('database');
        $db=new \shenphp\lib\Db($database);
        $typelist=$db->select('type','*');
        $newlist=[];
        foreach($typelist as $k=>$v){
            $newlist[$v['type_id']]=$v;
        }
        $cache::set('typelistidTree',$newlist);
    }
    //dump($newlist);//<?php echo
    if(isset($newlist[$type_id]['type_name'])){
        return $newlist[$type_id]['type_name'];
    }else{
        return '分类不存在';
    }


}
//获取用户组 id排序
function getgroupTrees(){
    $cache=new \shenphp\lib\Cache();
    if($cache::has('grouplistidTree')){
        $newlist=$cache::get('grouplistidTree');
    }else{
        $database=\shenphp\lib\Config::all('database');
        $db=new \shenphp\lib\Db($database);
        $typelist=$db->select('group','*');
        $newlist=[];
        foreach($typelist as $k=>$v){
            $newlist[$v['group_id']]=$v;
        }
        $cache::set('grouplistidTree',$newlist);
    }
    return $newlist;

}
//无限极分类
function gettypeidTrees(){
    $cache=new \shenphp\lib\Cache();
    if($cache::has('typelistidTree')){
        $newlist=$cache::get('typelistidTree');
    }else{
        $database=\shenphp\lib\Config::all('database');
        $db=new \shenphp\lib\Db($database);
        $typelist=$db->select('type','*');
        $newlist=[];
        foreach($typelist as $k=>$v){
            $newlist[$v['type_id']]=$v;
        }
        $cache::set('typelistidTree',$newlist);
    }
    return  $newlist;
}
if(!function_exists('isadmin')) {
    function isadmin(){
        $admin=\shenphp\lib\Session::has('admin');
        $user_name=\shenphp\lib\Session::get('user_name');
        if($user_name && $admin){
            return true;
        }else{
            return false;
        }
    }

}


function  addhook(){

}


function sq_substring($str, $lenth, $start=0)//返回设定字数
{
    $str=strip_tags($str);
    $str=str_replace(PHP_EOL, '', $str);
    $str=str_replace('\n','',$str);
    $str=str_replace('<br>','',$str);
    $str=str_replace('<br/>','',$str);
    $str=str_replace('  ','',$str);
    $str=str_replace(' ','',$str);
    $len = strlen($str);
    $r = array();
    $n = 0;
    $m = 0;

    for($i=0;$i<$len;$i++){
        $x = substr($str, $i, 1);
        $a = base_convert(ord($x), 10, 2);
        $a = substr( '00000000 '.$a, -8);

        if ($n < $start){
            if (substr($a, 0, 1) == 0) {
            }
            else if (substr($a, 0, 3) == 110) {
                $i += 1;
            }
            else if (substr($a, 0, 4) == 1110) {
                $i += 2;
            }
            $n++;
        }
        else{
            if (substr($a, 0, 1) == 0) {
                $r[] = substr($str, $i, 1);
            }else if (substr($a, 0, 3) == 110) {
                $r[] = substr($str, $i, 2);
                $i += 1;
            }else if (substr($a, 0, 4) == 1110) {
                $r[] = substr($str, $i, 3);
                $i += 2;
            }else{
                $r[] = ' ';
            }
            if (++$m >= $lenth){
                break;
            }
        }
    }
    return  join('',$r);
}
