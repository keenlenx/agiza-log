<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "delivery_requests".
 *
 * @property int $id
 * @property string $sender_name
 * @property string $sender_phone
 * @property string|null $sender_email
 * @property string $recipient_name
 * @property string $recipient_phone
 * @property string|null $recipient_email
 * @property string $source_address
 * @property string $destination_address
 * @property string|null $instructions
 * @property float |null $distance
 * @property string|null $delivery_status
 * @property string|null $payment_status
 * @property float |null $price
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_name', 'sender_phone', 'source_address', 'destination_address'], 'required'],
            [['source_address', 'destination_address', 'instructions', 'delivery_status', 'payment_status'], 'string'],
            [['sender_name', 'sender_email', 'recipient_name', 'recipient_email'], 'string', 'max' => 100],
            [['sender_phone', 'recipient_phone'], 'string','min'=>10],
            // the email attribute should be a valid email address
            [['sender_email','recipient_email'], 'email'],
            
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'pickup_time'=>Yii::t('app', 'pickup time'),
            'sender_name' => Yii::t('app', 'Sender Name'),
            'sender_phone' => Yii::t('app', 'Sender Phone'),
            'sender_email' => Yii::t('app', 'Sender Email'),
            'recipient_name' => Yii::t('app', 'Recipient Name'),
            'recipient_phone' => Yii::t('app', 'Recipient Phone'),
            'recipient_email' => Yii::t('app', 'Recipient Email'),
            'source_address' => Yii::t('app', 'Source Address'),
            'destination_address' => Yii::t('app', 'Destination Address'),
            'instructions' => Yii::t('app', 'Instructions'),
            'distance' => Yii::t('app', 'Distance'),
            'delivery_status' => Yii::t('app', 'Delivery Status'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'price' => Yii::t('app', 'Price'),
            
        ];
    }

    /**
     * {@inheritdoc}
     * @return DeliveryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeliveryQuery(get_called_class());
    }
}
