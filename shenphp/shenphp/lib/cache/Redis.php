<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/18  22:54
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib\cache;


class Redis
{
    protected $options = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'select' => 0,
        'timeout' => 0,
        'expire' => 0,
        'persistent' => false,
        'prefix' => '',
    ];
    protected $handler;

    /**
     * 连接到redis缓存
     */
    public function __construct($options = []){
        //$GLOBALS['cache']['redis']['host'];
        //判断扩展是否开启s
        if (!extension_loaded('redis')) {
            set_exception_handler('exception_handler');
            throw new \BadFunctionCallException('不支持: redis');
        }
        if (!empty($options)) {
            $this->options=$options;
        }
        $func = $this->options['persistent'] ? 'pconnect' : 'connect';
        $this->handler = new \Redis();
        $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);
        if ('' != $this->options['password']) {
            $this->handler->auth($this->options['password']);
        }
        if (0 != $this->options['select']) {
            $this->handler->select($this->options['select']);
        }
    }




    /**
     * 检测redis键是否存在
     */
    public function has($key){
        $key=$this->options['expire'].$key;
        return $this->handler->exists($key);
    }


    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($key){
        $key=$this->options['expire'].$key;
        $value = $this->handler->get($key);
        if (is_null($value)) {
            return false;
        }
        $jsonData = json_decode($value, true);
        // 检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
        return (null === $jsonData) ? $value : $jsonData;
    }


    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value 存储数据
     * @param integer $expire 有效时间（秒）
     * @return boolean
     */
    public function set($key, $value,$expire=4747){
        $key=$this->options['expire'].$key;
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        //$key = $name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if (is_int($expire) && $expire) {
            $result = $this->handler->setex($key, $expire, $value);
        }
        else {
            $result = $this->handler->set($key, $value);
        }
        return $result;
    }


    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */

    public function del($key){
        $key=$this->options['expire'].$key;
        return $this->handler->delete($key);
        //return $this->handler->del($key);
    }


    /**
     * 清除所有缓存
     * @access public
     * @return boolean
     */
    public function clear(){
        return $this->handler->flushDB();
    }
}