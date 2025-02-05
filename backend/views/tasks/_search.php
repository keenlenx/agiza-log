<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TasksSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="moving-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'time_created') ?>

    <?= $form->field($model, 'Customer_name') ?>

    <?php // echo $form->field($model, 'Customer_phone') ?>

    <?php // echo $form->field($model, 'Customer_email') ?>

    <?php // echo $form->field($model, 'from_address') ?>

    <?php // echo $form->field($model, 'to_address') ?>

    <?php // echo $form->field($model, 'Move_description') ?>

    <?php // echo $form->field($model, 'Distance') ?>

    <?php // echo $form->field($model, 'Moving_status') ?>

    <?php // echo $form->field($model, 'payment_status') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'transaction_id') ?>

    <?php // echo $form->field($model, 'partner_id') ?>

    <?php // echo $form->field($model, 'Start_time') ?>

    <?php // echo $form->field($model, 'End_time') ?>

    <?php // echo $form->field($model, 'Stripe_code') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
