<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Fin_Checklist]].
 *
 * @see Fin_Checklist
 */
class Fin_ChecklistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Fin_Checklist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Fin_Checklist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
