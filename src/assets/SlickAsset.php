<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 18:07
 */

namespace floor12\banner\assets;

use yii\web\AssetBundle;

class SlickAsset extends AssetBundle
{
    public $css = [
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css'
    ];

    public $js = [
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
