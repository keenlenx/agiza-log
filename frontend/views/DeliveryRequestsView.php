<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\DeliveryRequests $model */
/** @var ActiveForm $form */
?>
<div class="DeliveryRequestsView">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'sender_name') ?>
        <?= $form->field($model, 'sender_phone') ?>
        <?= $form->field($model, 'recipient_name') ?>
        <?= $form->field($model, 'recipient_phone') ?>
        <?= $form->field($model, 'source_address') ?>
        <?= $form->field($model, 'destination_address') ?>
        <?= $form->field($model, 'distance') ?>
        <?= $form->field($model, 'weight') ?>
        <?= $form->field($model, 'total_cost') ?>
        <?= $form->field($model, 'instructions') ?>
        <?= $form->field($model, 'fragile') ?>
        <?= $form->field($model, 'weekend') ?>
        <?= $form->field($model, 'insurance') ?>
        <?= $form->field($model, 'sender_email') ?>
        <?= $form->field($model, 'recipient_email') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- DeliveryRequestsView -->
