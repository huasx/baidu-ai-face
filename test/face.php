<?php
/**
 * Created by VeryStar.
 * Author: hsx
 * Create: 2018/6/20 18:37
 * Editor: created by PhpStorm
 */
include 'base.php';

$token  = '24.7065ef43a51b3310374fb82387eeabcc.2592000.1532143268.282335-10527228';
$detect = [
    'image'      => 'https://ai.bdstatic.com/file/52BC00FFD4754A6298D977EDAD033DA0',
    'image_type' => 'URL',
];

$match = [
    [

        'image'      => 'https://ai.bdstatic.com/file/52BC00FFD4754A6298D977EDAD033DA0',
        'image_type' => 'URL',
    ],
    [
        'image'      => 'https://ai.bdstatic.com/file/52BC00FFD4754A6298D977EDAD033DA0',
        'image_type' => 'URL',
    ],

];

$search = [
    'image'         => 'https://ai.bdstatic.com/file/52BC00FFD4754A6298D977EDAD033DA0',
    'image_type'    => 'URL',
    'group_id_list' => 'group1',
];
$obj    = new \AIFace\Baidu\Face();
$ret    = $obj->init([
    'token' => $token,
])->search($search);

var_dump($ret->getBody());