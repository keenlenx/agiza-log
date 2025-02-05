<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ag_rentals".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $car_id
 * @property string $rental_start_date
 * @property string $rental_end_date
 * @property string $pickup_location
 * @property string $dropoff_location
 * @property float $total_price
 * @property string|null $rental_status
 * @property string|null $payment_status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Rentals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ag_rentals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'car_id', 'rental_start_date', 'rental_end_date', 'total_price'], 'required'],
            [['customer_id', 'car_id'], 'integer'],
            [['rental_start_date', 'rental_end_date', 'created_at', 'updated_at'], 'safe'],
            [['total_price'], 'number'],
            [['rental_status', 'payment_status'], 'string'],
            [['pickup_location', 'dropoff_location'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'car_id' => Yii::t('app', 'Car ID'),
            'rental_start_date' => Yii::t('app', 'Rental Start Date'),
            'rental_end_date' => Yii::t('app', 'Rental End Date'),
            'pickup_location' => Yii::t('app', 'Pickup Location'),
            'dropoff_location' => Yii::t('app', 'Dropoff Location'),
            'total_price' => Yii::t('app', 'Total Price'),
            'rental_status' => Yii::t('app', 'Rental Status'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RentalsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RentalsQuery(get_called_class());
    }
}
