<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Moving $model */

$this->title = Yii::t('app', 'Create Moving');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moving-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
