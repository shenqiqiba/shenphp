<?php

/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  21:45
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */
namespace shenphp\lib\cache;
class File
{
    /*保存路径*/
    private $cache_dir = ROOT_PATH.'/content/cache/';
    private $prefix='shenqi';
    private $expire=3600;

    /*初始化*/
    public function __construct($option)
    {
        $this->cache_dir=$option['path'];
        $this->prefix=$option['prefix'];
        $this->expire=$option['time'];
    }

    /**获取缓存 */
    public function get($key)
    {
        $key=$this->prefix.$key;
        $file_name = $this->getFileName($key);
        $lines    = file($file_name);
        $lifetime = array_shift($lines);
        $lifetime = (int) trim($lifetime);
        if ($lifetime !== 0 && $lifetime < time()) {
            @unlink($file_name);
            return false;
        }
        $serialized = join('', $lines);
        $data       = unserialize($serialized);
        return $data;
    }
    /*判断缓存是否存在*/
    public function has($key){
        $key=$this->prefix.$key;
        $file_name = $this->getFileName($key);
        if (!is_file($file_name) || !is_readable($file_name)) {
            return false;
        }
        //判断是否过期 过期删除返回假
        $lines    = file($file_name);
        $lifetime = array_shift($lines);
        $lifetime = (int) trim($lifetime);
        if ($lifetime !== 0 && $lifetime < time()) {
            @unlink($file_name);
            return false;
        }
        //
        return true;
    }
    /*清空缓存*/
    public function clear(){
        $path=$this->cache_dir;
        $this->deldir($path);
    }

    /*删除文件夹*/
    private function deldir($path){
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach($p as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($path.$val)){
                        //子目录中操作删除文件夹和文件
                        $this->deldir($path.$val.'/');
                        //目录清空后删除空文件夹
                        @rmdir($path.$val.'/');
                    }else{
                        //如果是文件直接删除
                        @unlink($path.$val);
                    }
                }
            }
        }
    }

    /*删除缓存*/
    public function del($key)
    {
        $key=$this->prefix.$key;
        $file_name = $this->getFileName($key);
        return @unlink($file_name);
    }

    /*设置缓存*/
    public function set($key, $data, $lifetime = 4747)
    {
        $key=$this->prefix.$key;
        if($lifetime==4747){
            //如果没有设定缓存时间 那就全局为准
            $lifetime=$this->expire;
        }
        $dir = $this->getDirectory($key);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
        $file_name  = $this->getFileName($key);
        $lifetime   = time() + $lifetime;
        $serialized = serialize($data);
        $result     = file_put_contents($file_name, $lifetime . PHP_EOL . $serialized);
        if ($result === false) {
            return false;
        }
        return true;
    }


    /*取得文件路径*/
    protected function getDirectory($key)
    {
        $hash = sha1($key, false);
        $dirs = array(
            $this->cache_dir,
            substr($hash, 0, 2),
            substr($hash, 2, 2)
        );
        return join(DIRECTORY_SEPARATOR, $dirs);
    }

    /*获取文件名*/
    protected function getFileName($key)
    {
        $directory = $this->getDirectory($key);
        $hash      = sha1($key, false);
        $file      = $directory . DIRECTORY_SEPARATOR . $hash . '.cache';
        return $file;
    }

}