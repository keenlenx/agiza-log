<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "financial_reports_checklist".
 *
 * @property int $id
 * @property string $report_task
 * @property string $frequency
 * @property string $deadline
 * @property string|null $submitted
 * @property string|null $submission_date
 * @property string $conditions
 */
class Checklist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'financial_reports_checklist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['report_task', 'frequency', 'deadline', 'conditions'], 'required'],
            [['deadline', 'submission_date'], 'safe'],
            [['submitted', 'conditions'], 'string'],
            [['report_task'], 'string', 'max' => 255],
            [['frequency'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'report_task' => Yii::t('app', 'Report Task'),
            'frequency' => Yii::t('app', 'Frequency'),
            'deadline' => Yii::t('app', 'Deadline'),
            'submitted' => Yii::t('app', 'Submitted'),
            'submission_date' => Yii::t('app', 'Submission Date'),
            'conditions' => Yii::t('app', 'Conditions'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChecklistQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChecklistQuery(get_called_class());
    }
}
