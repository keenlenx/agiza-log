<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use frontend\models\User;


class SysNavbar extends Widget
{
    public function run()
    {
        // Get the user's role if logged in, or null for guests
        $role = \Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->Roles ?? null;
        $user = \Yii::$app->user->identity ?? null;
        $userId = \Yii::$app->user->identity->id ?? null;
        $myTasksUrl =  Url::to(['tasks/tasks']);
        $myOrdersUrl =  Url::to(['tasks/orders']);
        // Default menu items available to all users
        $menuItems = [
            ['label' => 'New transport', 'class' => 'btn btn-light border border-primary', 'url' => ['/transport/create']],
            ['label' => 'New Moving', 'class' => 'btn btn-light border border-primary', 'url' => ['/moving/create']],
        ];

        // Admin-specific menu items
        if ($role === 'admin') {      
            $menuItems = [
                ['label' => 'Transport', 'url' => ['/transport']],
                ['label' => 'Moving', 'url' => ['/moving']],
                ['label' => 'Users', 'url' => ['/user']],
                // ['label' => 'Reports', 'url' => ['admin/reports']],
                ['label' => 'My Tasks', 'url' => $myTasksUrl],  // Dynamic URL

            ];
            
        }
        // Partner-specific menu items
        elseif ($role === 'partner') {// Get the logged-in user's id

            // Generate the dynamic URL for the 'My Orders' page
            if ($user->id) {
                $myTasksUrl = $myOrdersUrl = Url::to(['tasks/orders']);
                // You can print the URL for testing purposes
                // Yii::$app->session->setFlash('alert', 'Url: ' . $myTasksUrl);


            } else {
                // Fallback if phone number is not available
                $myTasksUrl = ['/site/error']; // Adjust to your fallback route
            }

            // Add menu items specific to customers
            $menuItems = [
                ['label' => 'My Tasks', 'url' => $myTasksUrl],  // Dynamic URL
                
            ];
        }
        // Customer-specific menu items
        elseif ($role === 'customer') {
            // Get the logged-in user's phone number
            $sender_email = \Yii::$app->user->identity->email ?? null;

            // Generate the dynamic URL for the 'My Orders' page
            if ($sender_email) {
                $myOrdersUrl = Url::to(['tasks/orders']);
                // You can print the URL for testing purposes
                // Yii::$app->session->setFlash('alert', 'Url: ' . $myTasksUrl);

            } else {
                // Fallback if the email is not available
                $myOrdersUrl = ['/site/error']; // Adjust to your fallback route
            }

            // Add menu items specific to customers
            $menuItems = array_merge($menuItems, [
                ['label' => 'My Orders', 'url' => $myOrdersUrl],  // Dynamic URL
                ['label' => 'Profile', 'url' => '?r=user/view&id=' . $userId], // Link to external profile
            ]);
        }

        // Render the navigation menu
        NavBar::begin([
            'brandLabel' => Html::img('@web/images/icon.png', ['width' => '35']) . ' ' . \Yii::$app->name,
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => ['class' => 'navbar navbar-dark navbar-expand-md bg-dark fixed-top'],
        ]);


        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => $menuItems,
        ]);

        // Render login/signup buttons for guests
        if (\Yii::$app->user->isGuest) {
            echo Html::tag(
                'div',
                Html::a('Login', ['/site/login'], ['class' => 'btn btn-light login border border-warning']),
                ['class' => 'd-flex']
            );
         
        } else {
            // Get the user's ID for the profile link
            $userId = \Yii::$app->user->identity->id;
            // Render the logout button for logged-in users
             $menuItems = array_merge($menuItems, [
                ['label' => 'Profile', 'url' => '?r=user/view&id=' . $userId], // Link to external profile
            ]);
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    'Logout (' . Html::encode(\Yii::$app->user->identity->email) . ')',
                    ['class' => 'btn btn-link logout text-decoration-none']
                )
                . Html::endForm();
        }

        NavBar::end();
    }
}
