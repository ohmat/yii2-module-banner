<?php

namespace floor12\banner\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Author]].
 *
 * @see Author
 */
class AdsBannerQuery extends ActiveQuery
{

    public function byId(int $id)
    {
        return $this->andWhere(['id' => $id]);
    }

    public function hasLink()
    {
        return $this->andWhere(['!=', 'href', '']);
    }

    public function active()
    {
        $today = date('Y-m-d');
        return $this
            ->andWhere(['status' => AdsBanner::STATUS_ACTIVE])
            ->andWhere(['OR', 'ISNULL(show_start)', ['<=', 'show_start', $today]])
            ->andWhere(['OR', 'ISNULL(show_end)', ['>=', 'show_end', $today]]);
    }

    /**
     * {@inheritdoc}
     * @return AdsBanner[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AdsBanner|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
