<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Checklist $model */

$this->title = Yii::t('app', 'Create Checklist');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Checklists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checklist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
