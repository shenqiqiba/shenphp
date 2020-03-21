<?php
/**
 * 来源：https://github.com/noahbuscher/macaw
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/17  22:21
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;

/**
 * @method static Route get(string $route, Callable $callback)
 * @method static Route post(string $route, Callable $callback)
 * @method static Route put(string $route, Callable $callback)
 * @method static Route delete(string $route, Callable $callback)
 * @method static Route options(string $route, Callable $callback)
 * @method static Route head(string $route, Callable $callback)
 */

class Route
{
    public static $module='app';//默认模块
    public static $controller='index';//默认控制器
    public static $action='index';//默认方法


    public static $halts = false;
    public static $routes = array();
    public static $methods = array();
    public static $callbacks = array();
    public static $maps = array();
    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*',
    );
    public static $error_callback;
    //大概流程 静态变量 循环插入规则
    //然后运行方法 调用当前url和路由规则匹配的规则进行实例化
    //没有找到则返回失败

    /**
     * 定义带回调和方法的路由
     */

    public static function __callstatic($method, $params) {

        if ($method == 'map') {
            /*这句的意思是 数组里面的每个元素 都执行一次strtoupper方法(所有字母转化成大写)*/
            $maps = array_map('strtoupper', $params[0]);
            $uri = strpos($params[1], '/') === 0 ? $params[1] : '/' . $params[1];
            $callback = $params[2];
        } else {
            $maps = null;
            $uri = strpos($params[0], '/') === 0 ? $params[0] : '/' . $params[0];
            $callback = $params[1];
        }
        //array_push 向前一个数组 插入后一个字符串
        array_push(self::$maps, $maps);
        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }


    public static function dispatch($routetype=0){
        /*三个模式
        传统模式：index.php?m=index&a=index&c=index
        pathinfo:index.php?m/index/a/index/c/index
        路由模式:匹配
        参数获取：前两个$get 路由模式 ：get获取 数组形式 没有标明 但是按照顺序
        */
        if($routetype==0){
            $type=Config::get('route','type');
            $routetype=$type;
        }

        if($routetype==1){
            if (isset($_SERVER['REQUEST_URI'])){
                //http://shenphp.com/index.php?m=app&a=index&c=index&qq=5
                //http://shenphp.com/m=app&a=index&c=index&qq=5
                //http://shenphp.com/?m=app&a=index&c=index&qq=5
                if(isset($_GET['m'])){
                    self::$module=$_GET['m'];
                }else{
                    self::$module='app';//默认模块
                }

                if(isset($_GET['c'])){
                    self::$controller=ucfirst($_GET['c']);
                }else{
                    self::$controller=ucfirst('index');//默认控制器
                }

                if(isset($_GET['a'])){
                    self::$action=$_GET['a'];
                }else{
                    self::$action='index';//默认方法
                }
                $mol=self::$module;
                $col=self::$controller;
                $act=self::$action;
                $namesps=$mol.DS.'controller'.DS.$col;
                $ctrl=new $namesps();
                if(!is_object($ctrl)) {
                    if (DEBUG) {
                        set_exception_handler('exception_handler');
                        throw new \Exception('【神奇提示：没有找到' . $col . '类】');
                    }
                }
                $ctrl->{$act}($_GET);
                $found_route = true;
                return true;
            }
            $mol=self::$module;
            $col=self::$controller;
            $act=ucfirst(self::$action);
            $namesps=$mol.DS.'controller'.DS.$col;
            $ctrl=new $namesps();
            $ctrl->{$act}($_GET);
            $found_route = false;
            return false;

        }


        if($routetype==2){
            //http://shenphp.com/index.php?/app/index/index/qq/5
            //http://shenphp.com/app/index/index/qq/5
            //http://shenphp.com/?/app/index/index/qq/5
            if (isset($_SERVER['REQUEST_URI'])){
                $pathStr = ltrim($_SERVER['REQUEST_URI'], "/index.php");
                $pathStr = ltrim($pathStr, "/index.php?");
                $pathStr = ltrim($pathStr, "/");
                $pathStr = ltrim($pathStr, "/?");

                $path = explode('/', trim($pathStr, '/'));//去掉多余的分隔符

                $num=count($path);
                if($num<3){
                    $mol=self::$module;
                    $col=self::$controller;
                    $act=ucfirst(self::$action);
                    $pathurl=[];
                }else{
                    $i=0;
                    $pathurl=[];
                    //前三个分别是模块 控制器和方法 后面的才是参数
                    $mol=$path[0];
                    $col=ucfirst($path[1]);
                    $act=ucfirst($path[2]);
                    self::$module=$mol;
                    self::$controller=$col;
                    self::$action=$act;
                    $i=3;
                    while($i<$num){
                        if(isset($path[$i+1]) && isset($path[$i])){
                            $pathurl[$path[$i]]=$path[$i+1];
                        }

                        $i=$i+2;
                    }
                }
                $namesps=$mol.DS.'controller'.DS.$col;

                $ctrl=new $namesps();
                if(!is_object($ctrl)) {
                    if (DEBUG) {
                        set_exception_handler('exception_handler');
                        throw new \Exception('【神奇提示：没有找到' . $col . '类】');
                    }
                }
                $_GET=array_merge($pathurl,$_GET);
                $ctrl->{$act}($pathurl);
                $found_route = true;
                return true;
            }
            $mol=self::$module;
            $col=self::$controller;
            $act=ucfirst(self::$action);
            $namesps=$mol.DS.'controller'.DS.$col;
            $ctrl=new $namesps();
            $ctrl->{$act}($_GET);
            $found_route = false;
            return false;
        }

        if($routetype==3){
            self::dispatchurl();
        }

    }


    /**
     * 如果找不到路由，则定义回调
     */
    public static function error($callback) {
        self::$error_callback = $callback;
    }
    public static function haltOnMatch($flag = true) {
        self::$halts = $flag;
    }

    /**
     * 为给定的请求运行回调
     */
    public static function dispatchurl(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        //$GLOBALS['config']['url_suffix'] 后缀
        //@rtrim 移除尾部空白或者指定字符
        $url_suffix=Config::get('route','url_suffix');
        $uri= rtrim($uri,$url_suffix);

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);
        $found_route = false;
        self::$routes = preg_replace('/\/+/', '/', self::$routes);

        // 检查是否定义了路由 这里的$routes是一个数组路由规则
        //uri= "/index.php" $routes=array(1) { [0]=> string(1) "/" }   在$routes路由规则里面查看uri是否存在 存在就继续
        if (in_array($uri, self::$routes)) {
            $route_pos = array_keys(self::$routes, $uri);
            foreach ($route_pos as $route) {
                // 使用任意选项匹配GET和POST请求
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY' || (!empty(self::$maps[$route]) && in_array($method, self::$maps[$route]))) {
                    $found_route = true;

                    // 如果路由不是对象
                    if (!is_object(self::$callbacks[$route])) {

                        // 根据 /分隔符抓取所有零件
                        $parts = explode('/',self::$callbacks[$route]);
                        // 收集数组的最后一个索引
                        $last = end($parts);
                        // 获取控制器名称和方法调用
                        $segments = explode('@',$last);
                        //循环获取 组合 不包括类名和方法
                        $zucontroller='';
                        foreach($parts as $v){
                            if($v!==$last){
                                $zucontroller=$zucontroller.DS.$v;
                            }
                        }
                        // 实例化控制器 加上类名和方法
                        $zucontroller=$zucontroller.DS.'controller'.DS.$segments[0];
                        $controller = new $zucontroller();
                        // 调用方法
                        $controller->{$segments[1]}();

                        if (self::$halts) return;
                    } else {
                        // 调用方法结束
                        call_user_func(self::$callbacks[$route]);

                        if (self::$halts) return;
                    }
                }
            }
        } else {
            // 检查是否定义了路由 直接调用
            $pos = 0;
            foreach (self::$routes as $route) {
                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY' || (!empty(self::$maps[$pos]) && in_array($method, self::$maps[$pos]))) {
                        $found_route = true;

                        // 删除$matched[0]，因为[1]是第一个参数。

                        array_shift($matched);

                        if (!is_object(self::$callbacks[$pos])) {
                            // Grab all parts based on a / separator
                            $parts = explode('/',self::$callbacks[$pos]);
                            // Collect the last index of the array
                            $last = end($parts);
// 获取控制器名称和方法调用
                            $segments = explode('@',$last);
                            //循环获取 组合 不包括类名和方法
                            $zucontroller='';
                            foreach($parts as $v){
                                if($v!==$last){
                                    $zucontroller=$zucontroller.DS.$v;
                                }
                            }

                            // 实例化控制器 加上类名和方法
                            $zucontroller=$zucontroller.DS.'controller'.DS.$segments[0];
                            $controller = new $zucontroller();
                            // 调用方法

                            // Fix multi parameters

                            if (!method_exists($controller, $segments[1])) {
                                if(DEBUG){
                                    set_exception_handler('exception_handler');
                                    throw new \Exception('【神奇提示：没有找到控制器或者方法'.$controller.$segments[1].'】');
                                }
                            } else {
                                $_GET=$matched;
                                call_user_func_array(array($controller, $segments[1]), $matched);
                            }

                            if (self::$halts) return;
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);

                            if (self::$halts) return;
                        }
                    }
                }
                $pos++;
            }
        }

        //如果没有匹配到
        if (!$found_route){

        }


        // 如果找不到路由，请运行错误回调
        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo '页面不存在404->神奇CMS预留';
                };
            } else {
                if (is_string(self::$error_callback)) {
                    self::get($_SERVER['REQUEST_URI'], self::$error_callback);
                    self::$error_callback = null;
                    self::dispatch();
                    return ;
                }
            }
            call_user_func(self::$error_callback);
        }
    }

}