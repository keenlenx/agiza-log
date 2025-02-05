<?
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

class OtpForm extends Model
{
    public $email;
    public $otp;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['otp'], 'string', 'min' => 6, 'max' => 6],
        ];
    }

   public function sendOtp()
{
    $otp = rand(100000, 999999);
    Yii::$app->session->set('otp', $otp);
    Yii::$app->session->set('otp_email', $this->email);
    Yii::$app->session->set('otp_expiry', time() + 120); // 2-minute expiry
    Yii::$app->session->setFlash('success', 'OTP has been sent to your email.');

    $message = "
        <p>Dear User,</p>
        <p>Your One-Time Password (OTP) for verification is:</p>
        <h2 style='text-align:center; font-size:24px; letter-spacing:5px;'>
            <strong>$otp</strong>
        </h2>
        <p>This OTP is valid for <strong>2 minutes</strong>. Do not share this code with anyone.</p>
        <p>If you did not request this code, please ignore this email.</p>
        <br>
        <p>Best regards,<br><strong>AGIZA Team</strong></p>
    ";

    return Yii::$app->mailer->compose()
        ->setTo($this->email)
        ->setFrom(['OTP@agiza.fi' => 'AGIZA'])
        ->setSubject('Your OTP Code for Verification')
        ->setHtmlBody($message)
        ->send();
}


    public function validateOtp()
    {
        $sessionOtp = Yii::$app->session->get('otp');
        $sessionEmail = Yii::$app->session->get('otp_email');
        $expiry = Yii::$app->session->get('otp_expiry');

        if ($sessionOtp && $sessionEmail && $expiry > time()) {
            return $this->otp == $sessionOtp && $this->email == $sessionEmail;
        }
        return false;
    }
}
