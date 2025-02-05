<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Rentals $model */

$this->title = Yii::t('app', 'Create Rentals');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rentals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rentals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
