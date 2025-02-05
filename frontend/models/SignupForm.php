<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $phone_no;

        /**
         * {@inheritdoc}
         */
        public function rules()
        {
            return [
                ['username', 'trim'],
                ['username', 'required'],
                ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
                ['username', 'string', 'min' => 2, 'max' => 255],

                ['email', 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'string', 'max' => 255],
                ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

                ['password', 'required'],
                ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

                ['phone_no', 'trim'],
                ['phone_no', 'required'],
                ['phone_no', 'match', 'pattern' => '/^\+?[0-9]{7,15}$/', 'message' => 'Phone number must be valid.'],
                ['phone_no', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone number has already been taken.'],
            ];
        }

        /**
         * Signs user up.
         *
         * @return bool whether the creating new account was successful and email was sent
         */
   public function signup()
{
    if (!$this->validate()) {
        return false; // Return false if validation fails
    }

    $user = new User();
    $user->username = $this->username;
    $user->email = $this->email;
    $user->phone_no = $this->phone_no; // Assigning phone_no
    $user->setPassword($this->password);
    $user->generateAuthKey();
    $user->generateEmailVerificationToken();

    if ($user->save()) {
        // Flash success message
        // Yii::$app->session->setFlash('success', 'You have been successfully registered.');

        // Redirect to the home page
        return Yii::$app->response->redirect(Yii::$app->homeUrl);
    } else {
        // Flash error message if there was an issue with saving
        Yii::$app->session->setFlash('error', 'There was an issue with the registration.');
    }

    return false; // Return false if the save failed
}



        /**
         * Sends confirmation email to user
         * @param User $user user model to with email should be send
         * @return bool whether the email was sent
         */
      protected function sendEmail($user)
    {
        $verification_link = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);

        return Yii::$app->mailer->compose(
            ['html' => 'emailVerify-html', 'text' => null], // specify both HTML and text versions
            [
                'user_name' => $user->username, // Data to pass to the view
                'verification_link' => $verification_link,
            ]
        )
        ->setFrom('your-email@example.com')
        ->setTo($user->email)
        ->setSubject('Email Verification')
        ->send();
    }

}