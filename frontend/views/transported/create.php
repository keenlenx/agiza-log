<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Transport $model */

$this->title = Yii::t('app', 'Create Transport');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
