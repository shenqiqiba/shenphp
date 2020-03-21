<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  22:33
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib\cache;


class Memcached
{
    protected $options = [
        'host' => '127.0.0.1',
        'port' => 11211,
        'expire' => 0,
        'timeout' => 0, // 超时时间（单位：毫秒）
        'prefix' => 'shenqi',
        'username' => '', //账号
        'password' => '', //密码
    ];
    protected $handler;

    public function __construct($options = [])
    {
        if (!extension_loaded('memcached')) {
            set_exception_handler('exception_handler');
            throw new \BadFunctionCallException('不支持：memcached');
        }

        if (!empty($options)) {
            $this->options = $options;
        }
        $this->handler = new \Memcached();
        // 设置连接超时时间（单位：毫秒）
        if ($this->options['timeout'] > 0) {
            $this->handler->setOption(\Memcached::OPT_CONNECT_TIMEOUT, $this->options['timeout']);
        }

        // 支持集群
        $hosts = explode(',', $this->options['host']);
        $ports = explode(',', $this->options['port']);
        if (empty($ports[0])) {
            $ports[0] = 11211;
        }
        // 建立连接
        $servers = [];
        foreach ((array)$hosts as $i => $host) {
            $servers[] = [$host, (isset($ports[$i]) ? $ports[$i] : $ports[0]), 1];
        }
        //$this->handler = new \Memcached();
        $this->handler->addServers($servers);
        $this->handler->connect($this->options['host'], $this->options['port']); //写入缓存地址,port


    }
    /*
     * 加入缓存数据
     * @param string $key 获取数据唯一key
     * @param String||Array $value 缓存数据
     * @param $time memcache生存周期(秒) 0代表永久
     */
    public function set($key,$value,$time=4747){
        $key=$this->options['prefix'].$key;
        if($time==4747){
            $time=$this->options['timeout'];
        }
        return $this->handler->set($key,$value,$time);
        //成功返回真 失败返回假
    }

    /**/

    /*判断缓存是否存在*/
    public function has($key){
        $key=$this->options['prefix'].$key;
        if($this->handler->get($key)!==false){
            return true;
        }
        return false;
        //成功返回真 失败返回假
    }


    /*
     * 获取缓存数据
     * @param string $key
     * @return
     */
    public function get($key){
        $key=$this->options['prefix'].$key;
        return $this->handler->get($key);
    }

    /*
     * 删除相应缓存数据
     * @param string $key
     * @return
     */
    public function del($key){
        $key=$this->options['prefix'].$key;
        return $this->handler->delete($key);
        //成功返回真 失败返回假
    }

    /*
     * 删除全部缓存数据
     */
    public function clear(){
        return $this->handler->flush();
        //成功返回真 失败返回假
    }

    /*
     * 获取服务器统计信息（一般不用）
     */
    public function get_cache_status(){
        return $this->handler->getStats();
    }


}