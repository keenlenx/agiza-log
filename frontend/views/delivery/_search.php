<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\DeliverySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="delivery-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sender_name') ?>

    <?= $form->field($model, 'sender_phone') ?>

    <?= $form->field($model, 'sender_email') ?>

    <?= $form->field($model, 'recipient_name') ?>

    <?php // echo $form->field($model, 'recipient_phone') ?>

    <?php // echo $form->field($model, 'recipient_email') ?>

    <?php // echo $form->field($model, 'source_address') ?>

    <?php // echo $form->field($model, 'destination_address') ?>

    <?php // echo $form->field($model, 'instructions') ?>

    <?php // echo $form->field($model, 'distance') ?>

    <?php // echo $form->field($model, 'delivery_status') ?>

    <?php // echo $form->field($model, 'payment_status') ?>

    <?php // echo $form->field($model, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
