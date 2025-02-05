<?php

use yii\web\UserEvent;  // Add this line to use UserEvent

return [
    'name' => 'Agiza',
    'aliases' => [
        '@common' => dirname(__DIR__, 2) . '/common',  // Navigate two levels up
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
      
    ],

    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => 'session', // Make sure this table exists in your database
            'timeout' => 3600, // Timeout in seconds (1 hour)
        ],
         'user' => [
        'identityClass' => 'yii\web\User',
        'enableAutoLogin' => true,
        ],
        // 'user' => [
        //     'class' => 'yii\web\User',
        //     'on afterLogin' => function (UserEvent $event) {
        //         $user = $event->identity;
        //         $user->session_id = Yii::$app->session->id;
        //         $user->save(false); // Save session ID to user table
        //     },
        //     'on beforeLogout' => function (UserEvent $event) {
        //         $user = $event->identity;
        //         $user->session_id = null;
        //         $user->save(false); // Clear session ID when logging out
        //     },
        // ],
    ],
];
