<?php
/**
 * Created by VeryStar.
 * Author: hsx
 * Create: 2018/6/20 17:58
 * Editor: created by PhpStorm
 */

namespace AIFace\Baidu;

class Face extends Base
{

    public function init($options)
    {
        if ($options){
            $this->token     = empty($options['token']) ? "" : $options['token'];
        }

        return $this;
    }

    /**
     * 人脸检测
     *
     * @param $param
     * @return \AIFace\Curl
     */
    public function detect($param)
    {
        return $this->callApi('detect', $param);
    }

    /**
     * 人脸对比
     *
     * @param $param
     * @return \AIFace\Curl
     */
    public function match($param)
    {
        return $this->callApi('match', $param);
    }

    /**
     * 人脸搜索
     *
     * @param $param
     * @return \AIFace\Curl
     */
    public function search($param)
    {
        return $this->callApi('search', $param);
    }


}