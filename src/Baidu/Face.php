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

    //人脸检测
    public function detect($param)
    {
        return $this->callApi('detect', $param);
    }
}