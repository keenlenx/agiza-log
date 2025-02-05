<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use common\widgets\SysNavbar;

$role = \Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->Roles ?? null;
AppAsset::register($this);
 $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [
        'integrity' => 'sha512-<INSERT_INTEGRITY_HASH_HERE>',
        'crossorigin' => 'anonymous',
    ]);
?>

   


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags(); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7E5197E05R"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-7E5197E05R');
    </script>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?= SysNavbar::widget(); ?>
</header>
<?php
if (Yii::$app->session->hasFlash('redirectBack')): ?>
    <script>
        window.onload = function() {
            // Redirect to the previous page
            window.history.back();
        };
    </script>
<?php endif; ?>

<main role="main" class="flex-shrink-0">
   <div class="container">
    <?php if ($role === 'admin'): ?>
        <!-- Display breadcrumbs only for admin users -->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    <?php endif; ?>

    <!-- Render the page content -->
    <?= $content ?>
</div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; Agiza <?= date('Y') ?></p>
    </br>
        <p class="float-end"><?= \yii\helpers\Html::a('Privacy Policy', ['site/privacy-policy']) ?></p>
           <!-- Display Footer Debug Widget -->
        
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
