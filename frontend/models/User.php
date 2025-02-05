<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'phone_no'], 'required'],
            [['phone_no', 'created_at', 'updated_at'], 'integer'],
            [['Roles'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['usr_address'], 'string', 'max' => 150],
            [['username', 'email', 'password_reset_token'], 'unique'],
            [['email'], 'email'],
            [['phone_no'], 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Phone number must be between 10 and 15 digits.'],
           
            [['password_reset_token'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'phone_no' => 'Phone No',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            // 'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'Roles' => 'Roles',
            'usr_address' => 'Address',
        ];
    }
}
