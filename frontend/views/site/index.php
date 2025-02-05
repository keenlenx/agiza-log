<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Agiza';
?>
<div class="container">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
     <div class="container text-center col-md-6">
    
        <div class="app-grid row row-cols-3  g-3 mt-4 shadow rounded">
            <!-- Example Icon Card -->
            <div class="col text-center">
                <a href="<?= Url::to(['/moving/create']) ?>" class="btn btn-light shadow rounded-circle p-4">
                    <i class="fa-solid fa-lg fa-people-carry-box text-warning"></i>
                </a>
                <p class="m-2">Moving</p>
            </div>
          
            <div class="col text-center">
                <a href="<?= Url::to(['/transport/create']) ?>" class="btn btn-light shadow rounded-circle p-4">
                   <i class="fa-solid fa-truck-fast text-warning"></i>
                </a>
                <p class="m-2">Transport</p>
            </div>
              <div class="col text-center">
                <a href="tel:+358452324052" class="btn btn-light shadow rounded-circle p-4">
                   <i class="fa-solid fa-headset text-warning"></i>
                </a>
                <p class="m-2">Help</p>
            </div>
        </div>
    
        <div class="container col-md text-center mt-4">
          <a href="https://play.google.com/store/apps/details?id=com.deliverit.agiza&pcampaignid=web_share" target="_blank"><img src="images/appinstallad.png" class="img img-fluid m-2 rounded-3"></img>
            <img src="images/download.png" class="img img-fluid m-2 rounded-3"></img>
          </a>
        </div>
	
    </div>
</div>
