<?php
namespace app\assets;
use yii\web\AssetBundle;

class InstascanAsset extends AssetBundle
{
    public $sourcePath = '@npm/mybadinstascan';
    public $js = [
        'instascan.min.js',
    ];
    public $depends = [];
}
