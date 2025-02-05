<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Checklist]].
 *
 * @see Checklist
 */
class ChecklistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Checklist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Checklist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
