<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 20.06.2018
 * Time: 9:23
 *
 * @var $this View
 * @var $banner \floor12\banner\models\AdsBanner[]
 * @var $place \floor12\banner\models\AdsPlace
 * @var $id string
 * @var $targetBlank bool
 * @var $adaptiveBreakpoint integer
 */

use floor12\banner\assets\SlickAsset;
use floor12\banner\models\AdsBanner;
use yii\helpers\Html;
use yii\web\View;

SlickAsset::register($this);

$jsCode = <<< JS

    $('#{$id}').slick({
        vertical :  {$place->vertical},
        arrows: {$place->arrows},
        autoplay: true,
        accessibility: false,
        adaptiveHeight: true,
        autoplaySpeed: {$place->slider_time},
    });


    
JS;

$this->registerJs($jsCode, View::POS_READY, 'floor12-banner-slider-' . $id);

echo "<div id='{$id}'>";

foreach ($banners as $banner) {

    if (
        $banner->type == AdsBanner::TYPE_IMAGE &&
        $banner->file_desktop &&
        is_file($banner->file_desktop->getRootPath())
    )
        if ($banner->file_mobile && is_file($banner->file_mobile->getRootPath()))
            $img = "<picture class='banner-widget'>
                    <source 
                        type='image/webp' 
                        media='(min-width: {$adaptiveBreakpoint}px)' 
                        srcset='
                            {$banner->file_desktop->getPreviewWebPath($place->desktop_width,$place->desktop_height,true)} 1x, 
                            {$banner->file_desktop->getPreviewWebPath(($place->desktop_width * 2),($place->desktop_height * 2),true)} 2x'>
                                          
                    <source 
                        type='image/webp' 
                        media='(max-width: {$adaptiveBreakpoint}px)' 
                        srcset='
                            {$banner->file_mobile->getPreviewWebPath($place->mobile_width, $place->mobile_height)} 1x, 
                            {$banner->file_mobile->getPreviewWebPath(($place->mobile_width * 2), ($place->mobile_height * 2))} 2x'>
                    <source 
                        type='{$banner->file_desktop->content_type}' 
                        media='(min-width: {$adaptiveBreakpoint}px)' 
                        srcset='
                            {$banner->file_desktop->getPreviewWebPath($place->desktop_width, $place->desktop_height)} 1x, 
                            {$banner->file_desktop->getPreviewWebPath(($place->desktop_width * 2), ($place->desktop_height * 2))} 2x'>
                    <source 
                        type='{$banner->file_desktop->content_type}' 
                        media='(max-width: {$adaptiveBreakpoint}px)' 
                        srcset='
                            {$banner->file_mobile->getPreviewWebPath($place->mobile_width,$place->mobile_height)} 1x, 
                            {$banner->file_mobile->getPreviewWebPath(($place->mobile_width * 2), ($place->mobile_height * 2))} 2x'>
                    <img 
                        src='{$banner->file_desktop->getPreviewWebPath($place->desktop_width, $place->desktop_height)}' 
                        class='img-responsive' 
                        alt='{$banner->title}'>
                </picture>";
        else
            $img = "<picture class='banner-widget'>
                    <source 
                        type='image/webp' 
                        srcset='
                            {$banner->file_desktop->getPreviewWebPath($place->desktop_width,$place->desktop_height,true)} 1x, 
                            {$banner->file_desktop->getPreviewWebPath(($place->desktop_width * 2),($place->desktop_height * 2),true)} 2x'>              
                    <source 
                        type='{$banner->file_desktop->content_type}'
                        srcset='
                            {$banner->file_desktop->getPreviewWebPath($place->desktop_width, $place->desktop_height)} 1x, 
                            {$banner->file_desktop->getPreviewWebPath(($place->desktop_width * 2),($place->desktop_height * 2))} 2x'>
                    <img 
                        src='{$banner->file_desktop->getPreviewWebPath($place->desktop_width,$place->desktop_height)}' 
                        class='img-responsive' 
                        alt='{$banner->title}'>
                </picture>";

    else
        $img = Html::tag('iframe', null, [
            'src' => $banner->webPath,
            'class' => 'f12-rich-banner',
            'data-href' => $banner->href ? Url::toRoute(['/banner/redirect', 'id' => $banner->id]) : '',
        ]);


    if ($banner->href && $banner->type == AdsBanner::TYPE_IMAGE)
        echo Html::a($img, ['/banner/redirect', 'id' => $banner->id], [$targetBlank ? ['target' => '_blank', 'id' => ''] : []]);
    else
        echo $img;
}

echo "</div>";
