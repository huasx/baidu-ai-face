<p align="center"><a href="https://huasx.github.io" target="_blank"><img src="https://huasx.github.io/img/header_img.jpg"></a></p>
<p align="center">
<a href="https://travis-ci.org/huasx/baidu-ai-face"><img src="https://api.travis-ci.org/huasx/baidu-ai-face.svg" alt="Build Status"></a>
<a href="https://github.styleci.io/repos/114102098"><img src="https://github.styleci.io/repos/114102098/shield?branch=master" alt="StyleCI"></a>
<a href="https://packagist.org/packages/huasx/ai-face"><img src="https://poser.pugx.org/huasx/ai-face/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/huasx/ai-face"><img src="https://poser.pugx.org/huasx/ai-face/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/huasx/ai-face"><img src="https://poser.pugx.org/huasx/ai-face/license.svg" alt="License"></a>
</p>

## baidu-ai-face

### baidu ai face sdk for PHP

## Usage

### Download
```
composer require huasx/ai-face
```

### Example
```php
//get token
$ret = $obj->init(
    [
        'client_id'     => 'Your Client Id',
        'client_secret' => 'Your Client Secret',
    ]
)->getToken();
var_dump($ret->getBody());



//seach
$search = [
    'image'         => 'https://ai.bdstatic.com/file/52BC00FFD4754A6298D977EDAD033DA0',
    'image_type'    => 'URL',
    'group_id_list' => 'group1',
];

$obj = new \AIFace\Baidu\Face();

$ret = $obj->init([
    'token' => $token,
])->search($search);

var_dump($ret->getBody());

```

