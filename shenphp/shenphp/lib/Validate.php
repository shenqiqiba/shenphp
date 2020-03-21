<?php
/**
 * Created by 神奇cms.
 * User: 开发作者：神奇  QQ：97302834  官方网站：http://shenqiyu.com.
 * Date: 2020/3/3  21:39
 *'--------------------------------------------------------
 *'这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *'不允许对程序代码以任何形式任何目的的再发布。
 *'--------------------------------------------------------
 */

namespace shenphp\lib;

use shenphp\lib\Model;

class Validate
{
    public static $ziduan='';
    public static $error='';
    private static $title='';
    /*数组验证*/
    public static function yanZheng($data=[],$rule=[]){
        if(!is_array($data)){
            self::$error='数据必须为数组';
            return false;
        }
        if(!is_array($rule)){
            self::$error='规则必须为数组';
            return false;
        }
        //['title'=>'1,2,3']
        foreach($rule as $kw=>$v){

            //$k=title $v=min|1,max|1
            $ziduans=explode('|',$kw);
            $k=$ziduans[0];
            self::$title=$ziduans[1];
            $rules=explode(',',$v);
            self::$ziduan=$k;
            foreach($rules as $kk=>$vv){
                //$vv=isNum
                if(strpos($vv,"|")!==FALSE){
                    $newru=explode('|',$vv);
                    if($newru[0]=='min'){
                        $is=self::length($data[$k],1,$newru[1]);
                    }else{
                        $is=self::length($data[$k],2,0,$newru[1]);
                    }

                }else{
                    //
                    if(strpos($vv,"unique:")!==FALSE){
                        $newru=explode(':',$vv);
                        $is=self::unique($data[$k],$newru[1],$k);

                    }else{
                        $is=self::$vv($data[$k]);
                    }
                }
                //验证通过不用返回
                if(!$is){
                    return false;
                }
            }
        }
        return true;

    }
    /*尝试开发 验证指定字段数据库是否存在 unique:user*/
    public static function unique($str,$table,$ziduan){
        $db=new Model();
        $res=$db->get($table,$ziduan,[$ziduan=>$str]);
        if($res){
            //存在
            self::$error=self::$title.$str.'已经存在数据库';
            return false;
        }else{
            return true;
        }

    }

    /**
     * 是否为空值
     */
    public static function isEmpty($str){
        $str = trim($str);
        //不能用empty 0会被误判
        self::$error=self::$title.$str.'是空';
        if($str==''){
            return false;
        }
        if(mb_strlen($str)){
            return true;
        }

        if($str==0){
            return true;
        }

        //存在返回真 验证是否存在 不存在返回假 规则就是不能为空
        self::$error=self::$title.$str.'是空';
        if(empty($str))
            return !empty($str) ? true : false;
    }
    /**
     * 数字验证
     * param:$flag : int是否是整数，float是否是浮点型
     */
    public static function isNum($str,$flag = 'int'){
        self::$error=self::$ziduan.$str.'不为整数';
        return is_numeric($str);
    }
    /*是否是浮点*/
    public static function isfloat($str,$flag = 'float'){
        self::$error=self::$title.$str.'不为浮点型';
        return preg_match("/^[0-9\.]+$/", $str);

    }

    /**
     * 邮箱验证
     */
    public static function isEmail($str){
        self::$error=self::$title.$str.'不是邮箱';
        if(!self::isEmpty($str)) return false;
        return preg_match("/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i",$str) ? true : false;
    }
    //手机号码验证
    public static function isMobile($str){
        self::$error=self::$title.$str.'不是手机号';
        $exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]$/";
        if(preg_match($exp,$str)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * URL验证，纯网址格式，不支持IP验证

    public static function isUrl($str){
    self::$error=$str.'不为URL';
    if(!self::isEmpty($str)) return false;
    return preg_match('#(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?#i',$str) ? true : false;
    }*/
    /**
     * 验证是否为网址
     *
     * @access public
     * @param string $str
     * @return boolean
     */
    public static function isUrl($str)
    {
        self::$error=self::$title.$str.'不为URL';
        $parts = @parse_url($str);
        if (!$parts) {
            return false;
        }

        return isset($parts['scheme']) &&
        in_array($parts['scheme'], array('http', 'https', 'ftp')) &&
        !preg_match('/(\(|\)|\\\|"|<|>|[\x00-\x08]|[\x0b-\x0c]|[\x0e-\x19])/', $str);
    }


    /**
     * 验证长度
     * @param: string $str
     * @param: int $type(方式，默认min <= $str <= max)
     * @param: int $min,最小值;$max,最大值;
     * @param: string $charset 字符
     */
    public static function length($str,$type=3,$min=0,$max=0,$charset = 'utf-8'){
        if(!self::isEmpty($str)) return false;
        $len = mb_strlen($str,$charset);
        switch($type){
            case 1: //只匹配最小值
                self::$error=self::$title.$str.'长度最小只能为'.$min;
                return ($len >= $min) ? true : false;
                break;
            case 2: //只匹配最大值
                self::$error=self::$title.$str.'长度最大只能为'.$max;
                return ($max >= $len) ? true : false;
                break;
            default: //min <= $str <= max
                return (($min <= $len) && ($len <= $max)) ? true : false;
        }
    }
}