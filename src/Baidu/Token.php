<?php
/**
 * Created by VeryStar.
 * Author: hsx
 * Create: 2018/6/20 18:08
 * Editor: created by PhpStorm
 */

namespace AIFace\Baidu;

class Token extends Base
{
    protected $api_url = 'https://aip.baidubce.com/oauth/2.0/token';
    private $client_id = '';
    private $client_secret = '';
    private $grant_type = 'client_credentials';//固定

    public function __construct($curl = null)
    {
        parent::__construct($curl);
    }

    public function init($options = [])
    {
        if ($options) {
            $this->client_id     = empty($options['client_id']) ? "" : $options['client_id'];
            $this->client_secret = empty($options['client_secret']) ? "" : $options['client_secret'];
        }

        return $this;
    }

    public function getToken()
    {
        $param = [
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    => $this->grant_type,
        ];

        return $this->callApi($param);
    }

}
 