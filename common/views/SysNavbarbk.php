<?php
    // Set application name (displayed in brand label)
    Yii::$app->name = 'AGIZA';  

    // Begin the navigation bar
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/icon.png', ['width' => '35px', 'alt' => Yii::$app->name]) . ' ' . Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-dark navbar-expand-md bg-dark fixed-top',
        ],
    ]);

    // Get the user's role if logged in, or null for guests
    $role = Yii::$app->user->isGuest ? null : Yii::$app->user->identity->Roles ?? null;

    // Default menu items available to all users
    $menuItems = [
        ['label' => 'Delivery', 'class' => 'btn btn-light border border-primary', 'url' => ['/delivery/create']],
        ['label' => 'Moving', 'class' => 'btn btn-light border border-primary', 'url' => ['/moving/create']]
    ];

    // Admin-specific menu items
    if ($role === 'admin') {
        $menuItems = array_merge($menuItems, [
            ['label' => 'Users', 'url' => ['/user']],
            ['label' => 'Reports', 'url' => ['admin/reports']],
        ]);
    }
    // Partner-specific menu items
    elseif ($role === 'partner') {
        $menuItems = array_merge($menuItems, [
            ['label' => 'My Tasks', 'url' => ['/partner/tasks']],
            ['label' => 'My Earnings', 'url' => ['/partner/earnings']],
        ]);
    }
    // Customer-specific menu items
    elseif ($role === 'customer') {
        // Get the logged-in user's phone number
        $sender_phone = Yii::$app->user->identity->phone_no ?? null;

        // Generate the dynamic URL for the 'My Orders' page
        if ($sender_phone) {
            $myOrdersUrl = Url::to([
                'delivery/index',
                'DeliverySearch[sender_phone]' => $sender_phone, // Include dynamic phone number
            ]);
        } else {
            // Fallback if phone number is not available
            $myOrdersUrl = ['/site/error']; // Adjust to your fallback route
        }

        // Get the user's ID for the profile link
        $userId = Yii::$app->user->identity->id;

        // Add menu items specific to customers
        $menuItems = array_merge($menuItems, [
            ['label' => 'My Orders', 'url' => $myOrdersUrl],  // Dynamic URL
            ['label' => 'Profile', 'url' => 'https://app.agiza.fi/index.php?r=user%2Fview&id=' . $userId], // Link to external profile
        ]);
    }
    // Menu items for guest users (not logged in)
    elseif (Yii::$app->user->isGuest) {
        // Optionally add Signup or Login for guests
    }

    // Render the navigation menu
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);

    // Render the login/signup buttons for guest users
    if (Yii::$app->user->isGuest) {
        echo Html::tag(
            'div',
            Html::a('Login', ['/site/login'], ['class' => 'btn btn-light login border border-warning']),
            ['class' => 'd-flex']
        );
        echo('&nbsp');
        echo Html::tag(
            'div',
            Html::a('Signup', ['/site/signup'], ['class' => 'btn btn-light login border border-success']),
            ['class' => 'd-flex']
        );
    } else {
        // Render the logout button for logged-in users
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }

    // End the navigation bar
    NavBar::end();
    ?>