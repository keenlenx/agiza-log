<?
namespace frontend\assets;

use Yii;

class NavigationHelper
{
    public static function redirectBack($delay = 1)
    {

        // Get the referrer URL (previous page)
        $referrerUrl = Yii::$app->request->referrer;

        // If there's no referrer, default to the home page or index
        if (!$referrerUrl) {
            $referrerUrl = Yii::$app->homeUrl; // Default fallback
        }

        // JavaScript to show countdown and redirect after delay
        $script = <<<JS
        <script type="text/javascript">
            var countdown = $delay;
            alert('You will be redirected back to the previous page in ' + countdown + ' seconds.');
            
            // Countdown Timer
            var interval = setInterval(function() {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = '$referrerUrl'; // Redirect after countdown
                }
            }, 1000);
        </script>
JS;

        // Adding the script to the response to ensure it's executed when the page loads
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        Yii::$app->response->content = $script;
        return Yii::$app->response->send(); // Send the response immediately
    }
}
