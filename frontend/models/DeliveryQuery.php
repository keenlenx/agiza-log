<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Delivery]].
 *
 * @see Delivery
 */
class DeliveryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Delivery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Delivery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
