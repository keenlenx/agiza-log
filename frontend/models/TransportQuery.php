<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Transport]].
 *
 * @see Transport
 */
class TransportQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Transport[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Transport|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
