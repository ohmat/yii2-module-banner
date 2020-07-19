<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 12:13
 */

namespace floor12\banner\widgets;

use floor12\banner\models\AdsPlace;
use Yii;
use yii\base\Widget;

class BannerWidget extends Widget
{
    public $place_id;
    public $targetBlank = true;
    private $_place;
    private $_banners;
    private $_view;

    /**
     * @return bool
     */
    public function init(): bool
    {
        parent::init();
        // Некоторые браузеры любят посылать HEAD запросы, что ошибочно увеличивает счетчик просмотров
        if (Yii::$app->request->method == 'HEAD')
            return false;

        $this->_place = AdsPlace::findOne($this->place_id);

        // Если не найдена площадка - ничего не делаем
        if (!$this->_place)
            return false;

        // Если не найдены активные баннеры -  тоже ничего не делаем
        if (!$this->_place->bannersActive)
            return false;

        // Если площадка в режиме слайдера - выбираем все баннеры. В противном случае - рандомно выбираем 1 баннер из актиыных
        if ($this->_place->slider == AdsPlace::SLIDER_ENABLED) {
            $this->_banners = $this->_place->bannersActive;
            foreach ($this->_banners as $banner)
                $banner->increaseViews();
            $this->_view = 'bannerWidgetSlider';
        } else {
            $this->_banners = $this->_place->bannersActive[rand(0, sizeof($this->_place->bannersActive) - 1)];
            $this->_banners->increaseViews();
            $this->_view = 'bannerWidgetSingle';
        }

        // Если не найдены баннеры - ничего не делаем
        if (!$this->_banners)
            return false;

        return true;
    }

    /** Рендерим вьюху
     * @return string
     */
    public function run(): string
    {
        if (!$this->_banners)
            return "";
        return $this->render($this->_view, [
            'banners' => $this->_banners,
            'place' => $this->_place,
            'id' => "banner" . rand(99999, 9999999),
            'targetBlank' => $this->targetBlank,
            'adaptiveBreakpoint' => Yii::$app->getModule('banner')->adaptiveBreakpoint
        ]);
    }

}
