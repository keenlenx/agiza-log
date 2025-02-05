<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Moving]].
 *
 * @see Moving
 */
class MovingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Moving[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Moving|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
