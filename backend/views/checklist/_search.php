<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ChecklistSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="checklist-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'report_task') ?>

    <?= $form->field($model, 'frequency') ?>

    <?= $form->field($model, 'deadline') ?>

    <?= $form->field($model, 'submitted') ?>

    <?php // echo $form->field($model, 'submission_date') ?>

    <?php // echo $form->field($model, 'conditions') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
