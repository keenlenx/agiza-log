<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use common\models\OtpForm;
use common\models\User;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use yii\di\Instance;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private $mailer;
    public function __construct($id, $module, MailerInterface $mailer, $config = [])
    {
        $this->mailer = $mailer;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Simple reusable mailer function to send emails.
     * 
     * @param string $toEmail The recipient email address
     * @param string $subject The email subject
     * @param string $body The email body content
     * @return bool Whether the email was sent successfully
     */
    protected function sendEmail($toEmail, $subject, $body)
    {
        try {
            // Send email using Yii's mailer component
            Yii::$app->mailer->compose()
                ->setTo($toEmail)
                ->setFrom(Yii::$app->params['adminEmail']) // Replace with the sender's email if needed
                ->setSubject($subject)
                ->setTextBody($body) // Send plain text body (can also use setHtmlBody for HTML)
                ->send();

            // Log email sent
            Yii::info('Email sent to ' . $toEmail . ' with subject: ' . $subject);
            return true;
        } catch (\Exception $e) {
            // Log the error if email failed to send
            Yii::error('Error sending email: ' . $e->getMessage());
            return false;
        }
    }


    public function actionIndex()
    {
        return $this->render('index');
    }
 //OTP Login
     public function actionLogin()
    {
        $model = new OtpForm();

        if ($model->load(Yii::$app->request->post()) && $model->sendOtp()) {
            // Yii::$app->session->setFlash('success', 'OTP has been sent to your email.');
            return $this->redirect(['site/verify-otp']);
        }

        return $this->render('login', ['model' => $model]);
    }

public function actionVerifyOtp() {
    $model = new OtpForm();

    // Set the email from the session
    $model->email = Yii::$app->session->get('otp_email');

    if ($model->load(Yii::$app->request->post())) {
        if ($model->validateOtp()) {
            $user = User::findOne(['email' => $model->email]);

            if (!$user) {
                // Register new user if not found
                $user = new User();
                $user->email = $model->email;
                $user->status = 10;
                $user->auth_key = Yii::$app->security->generateRandomString();

                if (!$user->save()) {
                    Yii::$app->session->setFlash('error', 'Failed to register user.');
                      return $this->render('login', ['model' => $model]);
                }
            }

            try {
                // Log the user in
                Yii::$app->user->login($user);
                return $this->goHome();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Login failed.');
                 return $this->render('login', ['model' => $model]);
            }
        } else {
            // Show error message only when OTP is submitted incorrectly
            Yii::$app->session->setFlash('error', 'Invalid OTP. Please try again.');
            return $this->render('verify-otp', ['model' => $model]);
        }
    }

    return $this->render('verify-otp', ['model' => $model]);
}


// DEFAULT LOGIN FOR YII2
    // public function actionLogin()
    // {
    //     if (!Yii::$app->user->isGuest) {
    //         return $this->goHome();
    //     }

    //     $model = new LoginForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->login()) {
    //         return $this->goBack();
    //     }

    //     $model->password = '';

    //     return $this->render('login', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    public function actionWelcomemail()
    {
        // Email content
        $message = "
            <p>Dear User,</p>
            <p>Welcome to <strong>Agiza</strong>! We are excited to have you on board.</p>
            <p>At Agiza, we are committed to providing you with the best service experience. Whether you need transport or moving assistance, we've got you covered.</p>
            <p>Feel free to explore our platform and let us know if you need any assistance.</p>
            <br>
            <p>Best regards,<br><strong>The AGIZA Team</strong></p>
        ";

        // Try sending the email and handle any potential errors
        try {
            $emailSent = Yii::$app->mailer->compose()
                ->setTo('brianndiwa@agiza.fi')
                ->setFrom(['info@agiza.fi' => 'AGIZA'])
                ->setSubject('Welcome')
                ->setHtmlBody($message)
                ->send();

            if ($emailSent) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                Yii::info('Welcome email successfully sent to brianndiwa@agiza.fi', __METHOD__);
                return 'Email sent successfully!';
            } else {
                Yii::error('Failed to send welcome email to brianndiwa@agiza.fi', __METHOD__);
                return 'Failed to send email.';
            }

        } catch (\Exception $e) {
            // Catch any exceptions during the email sending process
            Yii::error('Error while sending welcome email: ' . $e->getMessage(), __METHOD__);
            return 'An error occurred while sending the email.';
        }
    }

public function actionContact()
{
    // Create a new instance of the ContactForm model
    $model = new ContactForm();

    // Check if the form is submitted and validated
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        try {
            // Attempt to send the email to the admin email
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                // If successful, set a success message in the session
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                // If email sending failed, set an error message in the session
                Yii::$app->session->setFlash('error', 'There was an error sending your message. Please try again later.');
            }
        } catch (\yii\base\InvalidConfigException $e) {
            // Catch and handle any InvalidConfigException
            Yii::$app->session->setFlash('error', 'Configuration error: ' . $e->getMessage());
            Yii::error('Configuration error: ' . $e->getMessage(), __METHOD__);
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            // Catch and handle email transport related errors
            Yii::$app->session->setFlash('error', 'Email sending failed: ' . $e->getMessage());
            Yii::error('Email transport error: ' . $e->getMessage(), __METHOD__);
        } catch (\Exception $e) {
            // Catch any other exceptions and log the error
            Yii::$app->session->setFlash('error', 'An unexpected error occurred: ' . $e->getMessage());
            Yii::error('Unexpected error: ' . $e->getMessage(), __METHOD__);
        }

        // Refresh the page after attempting to send the email
        return $this->refresh();
    }

    // Render the contact form view if the form is not submitted or invalid
    return $this->render('contact', [
        'model' => $model,
    ]);
}


    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionVerifyEmail($token)
    {
        $user = User::findOne(['verification_token' => $token]);

        if ($user) {
            $user->status = User::STATUS_ACTIVE; // or whatever logic you need
            $user->email_verification_token = null; // Clear the token after verification
            $user->save(false);

            Yii::$app->session->setFlash('success', 'Your email has been verified.');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Invalid or expired verification link.');
        return $this->goHome();
    }


    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model,
        ]);
    }

    public function actionPrivacyPolicy()
    {
        return $this->render('privacy-policy');
    }
        public function actionPricing()
    {
        return $this->render('pricing');
    }

    public function actionAccountDeletion()
    {
        return $this->render('account-deletion');
    }
}
