<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/17  21:04
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

if(!function_exists('json')){
    function json($val=[]){
        return json_encode($val,JSON_UNESCAPED_UNICODE);
    }
}

if(!function_exists('input')){
    function input($name=''){
        $param=array_merge($_POST,$_GET);
        if(empty($name)){
            return $param;
        }
        if(isset($param[$name])){
            return $param[$name];
        }else{
            return 0;
        }
    }
}

function console_log($data)
{
    if (is_array($data) || is_object($data))
    {
        echo("<script>console.log('".json_encode($data)."');</script>");
    }
    else
    {
        echo("<script>console.log('".$data."');</script>");
    }
}
//抛出异常
function url($url,$get=[]){
    $type=shenphp\lib\Config::get('route','type');
    $url_suffix=shenphp\lib\Config::get('route','url_suffix');
    $type=2;
    if($type==1){
        $urlarr=explode('/',$url);
        $newarry['a']=array_pop($urlarr);
        $newarry['c']=array_pop($urlarr);
        $newarry['m']=array_pop($urlarr);
        $newarry=array_merge($newarry,$get);
        $url=http_build_query($newarry);

        $url='/?'.urldecode($url);
        return $url;
    }

    if($type==2){
        $urlarr=explode('/',$url);
        $newurl='/';
        foreach($urlarr as $k=>$v){
            $newurl=$newurl.$v.'/';
        }
        if(!empty($get)){

            foreach($get as $k=>$v){
                $newurl=$newurl.$k.'/'.$v.'/';
            }
        }
        return $newurl;
    }
    if($type==3){
        $route_pos = array_keys(Route::$callbacks, $url);

        if(is_array($route_pos)){
            // $route_pos=$route_pos[0];
            //$keys=array_keys($items,'vegetable');
            $route_pos=end($route_pos);
        }

        $urlbegin=Route::$routes[$route_pos];
        $urlend='';
        foreach($get as $k=>$v){
            if(strpos($urlbegin,"(:".$k.')')!==false){
                $urlbegin=str_replace("(:".$k.')',$v,$urlbegin);
            }else{
                $urlend=$urlend.'/'.$k.'/'.$v;
            }
        }
        $newurl=$urlbegin.$urlend.Config::get('route','url_suffix');
        $newurl=preg_replace("/\(.*?\)/","",$newurl);
        $newurl=str_replace("//","/",$newurl);
        $newurl=str_replace("//","/",$newurl);
        $newurl=str_replace("//","/",$newurl);
        return $newurl;
    }


}

function exception_handler($exception) {
    echo "错误原因: " , $exception->getMessage(), "\n";
}

function show404(){
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
    echo '页面不存在404->神奇CMS预留';
}
if(!function_exists('dump')) {
    function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE)
    {
        $label = (null === $label) ? '' : rtrim($label) . ':';
        if ($var instanceof Model || $var instanceof ModelCollection) {
            $var = $var->toArray();
        }

        ob_start();
        var_dump($var);

        $output = ob_get_clean();
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);

        if (PHP_SAPI == 'cli') {
            $output = PHP_EOL . $label . $output . PHP_EOL;
        } else {
            if (!extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, $flags);
            }
            $output = '<pre>' . $label . $output . '</pre>';
        }
        if ($echo) {
            echo($output);
            return;
        }
        return $output;
    }
}