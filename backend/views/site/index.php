<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Agiza';
?>
<div class="container text-center col-sm-6 ">
    
    <div class="app-grid row row-cols-3 g-2 mt-4">
        <!-- Example Icon Card -->
        <div class="col text-center">
            <a href="<?= Url::to(['/moving']) ?>" class="btn btn-light shadow rounded-circle p-4">
                <i class="fa-solid fa-people-carry-box text-warning"></i>
            </a>
            <p class="mt-2">Moving</p>
        </div>
        <div class="col text-center">
            <a href="<?= Url::to(['/transport']) ?>" class="btn btn-light shadow rounded-circle p-4">
               <i class="fa-solid fa-truck-fast text-warning"></i>
            </a>
            <p class="mt-2">Transport</p>
        </div>
    </div>
</div>

<?php
// Add custom CSS to style the UI
$this->registerCss("
    .app-grid a {
        text-decoration: none;
    }
    .app-grid i {
        line-height: 1.5;
    }
    .app-grid p {
        font-size: 0.9rem;
        margin: 0;
    }
");
?>


</div>
