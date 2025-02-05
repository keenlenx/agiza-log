<?

namespace frontend\assets;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SymfonyMailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($to, $subject, $textBody, $htmlBody)
    {
        try {
            $email = (new Email())
                ->from('info@agiza.fi')  // Your email address
                ->to($to)  // Recipient's email address
                ->subject($subject)  // Subject of the email
                ->text($textBody)  // Plain text content
                ->html($htmlBody);  // HTML content

            // Send the email
            $this->mailer->send($email);

            return true;
        } catch (\Exception $e) {
            Yii::error("Error sending email to {$to}: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
