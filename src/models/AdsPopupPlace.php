<?php

namespace floor12\banner\models;

/**
 * This is the model class for table "ads_popup_place".
 *
 * @property int $id
 * @property string $title Название площадки
 * @property int $status Выключить
 *
 * @property AdsPopup[] $popups Сязанные баннеры
 * @property AdsPopup[] $popupsActive Активные связанные баннеры
 */
class AdsPopupPlace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ads_popup_place';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название площадки',
            'status' => 'Выключить',
        ];
    }

    /** Связь площадки с баннерами
     * @return ActiveQuery
     */
    public function getPopups(): AdsPopupQuery
    {
        return $this
            ->hasMany(AdsPopup::class, ['id' => 'popup_id'])
            ->viaTable('ads_popup_place_popup', ['place_id' => 'id']);
    }


    /** Активные баннеры.
     *  Проверяем, активен ли баннер, есть если у него выставлены даты - сравниваем с текущей датой
     * @return ActiveQuery
     */
    public function getPopupsActive(): AdsPopupQuery
    {
        return $this->getPopups()->orderBy('RAND()')->active();
    }

}
