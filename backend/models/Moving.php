<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ag_moving".
 *
 * @property int $id
 * @property string $time_created TIME
 * @property string $Customer_name CUSTOMER'S NAME
 * @property string $Customer_phone PHONE
 * @property string|null $Customer_email EMAIL
 * @property string|null $from_address FROM
 * @property string|null $to_address TO
 * @property string|null $Move_description DESCRIPTION OF THE MOVE
 * @property float|null $Distance DISTANCE
 * @property string|null $Moving_status STATUS
 * @property string|null $payment_status PAYMENT STATUS
 * @property float|null $price PRICE
 * @property string|null $transaction_id TRANSACTION ID
 * @property int|null $partner_id MOVER
 * @property string|null $Start_time BEGINNING
 * @property string|null $End_time END
 * @property string|null $Stripe_code STRIPE PAYMENT CODE
 */
class Moving extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ag_moving';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time_created'], 'safe'],
            [['Customer_name', 'Customer_phone'], 'required'],
            [['Move_description', 'Moving_status', 'payment_status'], 'string'],
            [['Distance', 'price'], 'number'],
            [['partner_id'], 'integer'],
            [['Customer_name', 'Customer_email'], 'string', 'max' => 100],
            [['Customer_phone'], 'string', 'max' => 15],
            [['from_address', 'to_address', 'transaction_id'], 'string', 'max' => 255],
            [['Start_time', 'End_time', 'Stripe_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'time_created' => Yii::t('app', 'TIME'),
            'Customer_name' => Yii::t('app', 'CUSTOMER\'S NAME'),
            'Customer_phone' => Yii::t('app', 'PHONE'),
            'Customer_email' => Yii::t('app', 'EMAIL'),
            'from_address' => Yii::t('app', 'FROM'),
            'to_address' => Yii::t('app', 'TO'),
            'Move_description' => Yii::t('app', 'DESCRIPTION OF THE MOVE'),
            'Distance' => Yii::t('app', 'DISTANCE'),
            'Moving_status' => Yii::t('app', 'STATUS'),
            'payment_status' => Yii::t('app', 'PAYMENT STATUS'),
            'price' => Yii::t('app', 'PRICE'),
            'transaction_id' => Yii::t('app', 'TRANSACTION ID'),
            'partner_id' => Yii::t('app', 'MOVER'),
            'assistant_id' => Yii::t('app', 'HELPER'),
            'Start_time' => Yii::t('app', 'BEGINNING'),
            'End_time' => Yii::t('app', 'END'),
            'Stripe_code' => Yii::t('app', 'STRIPE PAYMENT CODE'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return MovingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MovingQuery(get_called_class());
    }
}
