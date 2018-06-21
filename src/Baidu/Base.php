<?php
/**
 * Created by VeryStar.
 * Author: hsx
 * Create: 2018/6/20 18:00
 * Editor: created by PhpStorm
 */

namespace AIFace\Baidu;

use AIFace\Curl;

abstract class Base
{
    /**
     * @var Curl
     */
    protected $curl;
    protected $api_url = 'https://aip.baidubce.com/rest/2.0/face/';
    protected $version = 'v3';
    protected $token = '';//接口调用凭证

    public function __construct($curl = null)
    {
        if ($curl === null) {
            $this->curl = new Curl();
        } else {
            $this->curl = $curl;
        }
    }

    public function curl()
    {
        return $this->curl;
    }

    public function callApi($action, $param)
    {
        $this->api_url .= $this->version . '/' . $action . '?access_token=' . $this->token;
        $this->curl->setHeader([
            'Content-Type' => 'application/json',
        ]);
        return $this->curl->post($this->api_url, json_encode($param, JSON_UNESCAPED_UNICODE));
    }


}
