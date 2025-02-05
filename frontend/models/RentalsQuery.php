<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Rentals]].
 *
 * @see Rentals
 */
class RentalsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rentals[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rentals|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
