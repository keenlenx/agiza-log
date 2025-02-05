<?php

namespace frontend\models;

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
 * @property string|null $price PRICE
 * @property string|null $transaction_id TRANSACTION ID
 * @property int|null $partner_id MOVER
 * @property string|null $Start_time BEGINNING
 * @property string|null $End_time END
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
        // Time validation: time_created should be a valid date format
        [['time_created'], 'safe'],
        
        // Required fields: Customer_name and Customer_phone are mandatory
        [['Customer_name', 'Customer_phone'], 'required', 'message' => '{attribute} cannot be blank.'],
        
        // String validation for fields that should be strings
        [['Move_description', 'Moving_status', 'payment_status','assistance','Elevator'], 'string'],
        
        // Number validation for fields that should contain numeric values
        [['Distance', 'price','Floor_no'], 'number', 'message' => '{attribute} must be a valid number.'],
        
        // Integer validation for partner_id, it should be an integer
        [['partner_id'], 'integer', 'message' => '{attribute} must be an integer.'],
        
        // Length validation for Customer_name and Customer_email (max length of 100)
        [['Customer_name', 'Customer_email'], 'string', 'max' => 100],
        
        // Length validation for Customer_phone (max length of 15, considering international formats)
        [['Customer_phone'], 'string', 'max' => 15, 'message' => 'Phone number cannot exceed 15 characters.'],
        
        // Address validation: from_address, to_address, and transaction_id should not exceed 255 characters
        [['from_address', 'to_address', 'transaction_id'], 'string', 'max' => 255, 'message' => '{attribute} cannot exceed 255 characters.'],
        
        // Time-related validation: Start_time and End_time should be valid strings, with a maximum length of 50 characters
        [['Start_time', 'End_time'], 'string', 'max' => 50, 'message' => '{attribute} must not exceed 50 characters.'],
        
        // Optional: Validate email format for Customer_email
        [['Customer_email'], 'email', 'message' => 'Invalid email format.'],
        
        // Optional: Validate phone number format (example: using regex for more specific validation)
        [['Customer_phone'], 'match', 'pattern' => '/^\+?[0-9]{1,4}?[-. ]?(\([0-9]{1,3}\)[-.\s]?)?([0-9]{1,4})[-. ]?([0-9]{1,4})$/', 'message' => 'Invalid phone number format.'],
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
            'Elevator'=> Yii::t('app', 'ELEVATOR/LIFT'),
            'Floor_no'=> Yii::t('app', 'FLOOR'),
            'Move_description' => Yii::t('app', 'DESCRIPTION OF THE MOVE'),
            'Distance' => Yii::t('app', 'DISTANCE'),
            'Moving_status' => Yii::t('app', 'STATUS'),
            'payment_status' => Yii::t('app', 'PAYMENT STATUS'),
            'deposit' => Yii::t('app', 'DEPOSIT'),
            'price' => Yii::t('app', 'PRICE'),
            'transaction_id' => Yii::t('app', 'TRANSACTION ID'),
            'partner_id' => Yii::t('app', 'MOVER'),
            'assistance' => Yii::t('app', 'NEED HELPER?'),
            'Start_time' => Yii::t('app', 'BEGINNING'),
            'End_time' => Yii::t('app', 'END'),
            'Stripe_code' => Yii::t('app', 'PAYMENT CODE'),

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
