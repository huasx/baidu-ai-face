<?php
/**
 * Created by VeryStar.
 * Author: hsx
 * Create: 2018/6/20 18:37
 * Editor: created by PhpStorm
 */
include 'base.php';

$token = '24.f4a05c0ff9c4e3f58905e11dc7ec273a.2592000.1532139014.282335-10527228';
$param = [
    'image' => 'https://ai.bdstatic.com/file/52BC00FFD4754A6298D977EDAD033DA0',
    'image_type' => 'URL',
];
$obj = new \AIFace\Baidu\Face();
$ret = $obj->init([
    'token' => $token,
])->detect($param);

var_dump($ret->getBody());